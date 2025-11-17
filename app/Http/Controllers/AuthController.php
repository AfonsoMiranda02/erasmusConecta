<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

            // Verificar se o utilizador está ativo
            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'A sua conta está inativa. Contacte o administrador.',
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
     * Processa o registo
     */
    public function register(RegisterRequest $request)
    {
        $data = [
            'nome' => $request->nome,
            'email' => $request->email,
            'num_processo' => $request->num_processo,
            'password' => Hash::make($request->password),
            'is_active' => true,
            'is_aprovado' => false, // Intercambistas começam como não aprovados
        ];

        // Se for intercambista, adicionar código ou documento
        if ($request->tipo === 'intercambista') {
            if ($request->filled('codigo_mobilidade')) {
                $data['codigo_mobilidade'] = $request->codigo_mobilidade;
            }

            if ($request->hasFile('documento')) {
                $path = $request->file('documento')->store('documentos', 'public');
                $data['documento_path'] = $path;
            }

            // Intercambistas começam como não aprovados
            $data['is_aprovado'] = false;
        } else {
            // Estudantes e professores são aprovados automaticamente
            $data['is_aprovado'] = true;
        }

        $user = User::create($data);

        // Nota: As roles serão configuradas posteriormente pelo administrador

        // Login automático após registo
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Conta criada com sucesso!');
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
