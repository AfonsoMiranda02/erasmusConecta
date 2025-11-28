<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\PendingRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Mostra o formulário de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Processa o login
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Verificar se o utilizador está ativo
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'A sua conta está inativa. Contacte o administrador.',
                ])->onlyInput('email');
            }

            // Verificar se o email foi verificado (apenas se a coluna existir)
            if (Schema::hasColumn('users', 'email_verified_at') && !$user->email_verified_at) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Por favor, verifica o teu email antes de fazer login. Verifica a tua caixa de entrada.',
                ])->onlyInput('email');
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registos.',
        ])->onlyInput('email');
    }

    /**
     * Mostra o formulário de registo
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Processa o registo - guarda dados temporários e envia código
     */
    public function register(RegisterRequest $request)
    {
        // Verificar se já existe um registo pendente para este email
        $existingPending = PendingRegistration::where('email', $request->email)->first();
        if ($existingPending) {
            // Apagar registo pendente antigo
            $existingPending->delete();
        }

        // Preparar dados para guardar temporariamente
        $pendingData = [
            'nome' => $request->nome,
            'email' => $request->email,
            'num_processo' => $request->num_processo,
            'password' => Hash::make($request->password),
            'tipo' => $request->tipo,
        ];

        // Se for intercambista, adicionar código ou documento
        if ($request->tipo === 'intercambista') {
            if ($request->filled('codigo_mobilidade')) {
                $pendingData['codigo_mobilidade'] = $request->codigo_mobilidade;
            }

            if ($request->hasFile('documento')) {
                $path = $request->file('documento')->store('documentos', 'public');
                $pendingData['documento_path'] = $path;
            }
        }

        // Gerar código de verificação de 6 dígitos
        $verificationCode = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $pendingData['verification_code'] = $verificationCode;
        $pendingData['expires_at'] = now()->addMinutes(15);

        // Guardar registo pendente
        $pendingRegistration = PendingRegistration::create($pendingData);

        // Enviar email com código
        Mail::to($request->email)
            ->send(new \App\Mail\EmailVerificationMail($verificationCode, $request->nome));

        // Redirecionar para página de verificação
        return redirect()->route('register.verify', ['email' => $request->email])
            ->with('success', 'Foi enviado um código de verificação para ' . $request->email . '. Por favor, verifica o teu email.');
    }

    /**
     * Mostra a página de verificação de código
     */
    public function showVerifyForm(Request $request)
    {
        $email = $request->query('email');
        
        if (!$email) {
            return redirect()->route('register')
                ->with('error', 'Email não fornecido.');
        }

        $pendingRegistration = PendingRegistration::where('email', $email)->first();

        if (!$pendingRegistration) {
            return redirect()->route('register')
                ->with('error', 'Registo pendente não encontrado. Por favor, regista-te novamente.');
        }

        if ($pendingRegistration->isExpired()) {
            $pendingRegistration->delete();
            return redirect()->route('register')
                ->with('error', 'O código de verificação expirou. Por favor, regista-te novamente.');
        }

        return view('auth.verify-code', compact('email'));
    }

    /**
     * Verifica o código e cria a conta
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:6'],
        ], [
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser válido.',
            'code.required' => 'O código é obrigatório.',
            'code.size' => 'O código deve ter 6 dígitos.',
        ]);

        $pendingRegistration = PendingRegistration::where('email', $request->email)
            ->where('verification_code', $request->code)
            ->first();

        if (!$pendingRegistration) {
            return back()->withErrors([
                'code' => 'Código inválido. Verifica o código enviado para o teu email.',
            ])->withInput();
        }

        if ($pendingRegistration->isExpired()) {
            $pendingRegistration->delete();
            return redirect()->route('register')
                ->with('error', 'O código de verificação expirou. Por favor, regista-te novamente.');
        }

        // Criar conta do utilizador
        $userData = [
            'nome' => $pendingRegistration->nome,
            'email' => $pendingRegistration->email,
            'num_processo' => $pendingRegistration->num_processo,
            'password' => $pendingRegistration->password,
            'is_active' => true,
            'is_aprovado' => $pendingRegistration->tipo === 'intercambista' ? false : true,
        ];

        // Adicionar campos opcionais
        if ($pendingRegistration->codigo_mobilidade) {
            $userData['codigo_mobilidade'] = $pendingRegistration->codigo_mobilidade;
        }

        if ($pendingRegistration->documento_path) {
            $userData['documento_path'] = $pendingRegistration->documento_path;
        }

        // Se as colunas de verificação existem, marcar email como verificado
        if (Schema::hasColumn('users', 'email_verified_at')) {
            $userData['email_verified_at'] = now();
        }

        // Criar utilizador
        $user = User::create($userData);

        // Apagar registo pendente
        $pendingRegistration->delete();

        // Fazer login automático
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Conta criada e verificada com sucesso! Bem-vindo ao ErasmusConecta!');
    }

    /**
     * Processa o logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sessão terminada com sucesso.');
    }
}
