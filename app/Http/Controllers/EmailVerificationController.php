<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmailVerificationController extends Controller
{
    /**
     * Mostra o formulário para reenviar email de verificação
     */
    public function showResendForm()
    {
        return view('auth.email-resend');
    }

    /**
     * Verifica o email do utilizador
     */
    public function verify(Request $request, $token)
    {
        // Buscar utilizador com token não verificado
        $users = User::whereNull('email_verified_at')
            ->whereNotNull('email_verification_token')
            ->get();

        $user = null;
        foreach ($users as $u) {
            if (Hash::check($token, $u->email_verification_token)) {
                $user = $u;
                break;
            }
        }

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Link de verificação inválido ou já utilizado.');
        }

        // Verificar se o token expirou (24 horas)
        $tokenCreatedAt = $user->created_at; // Token criado quando o user foi criado
        $expiresAt = $tokenCreatedAt->copy()->addHours(24);
        
        if (now()->greaterThan($expiresAt)) {
            return redirect()->route('login')
                ->with('error', 'O link de verificação expirou. Por favor, solicita um novo link.');
        }

        // Verificar o email
        $user->update([
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);

        return redirect()->route('login')
            ->with('success', 'Email verificado com sucesso! Podes agora fazer login.');
    }

    /**
     * Reenvia o email de verificação
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser válido.',
            'email.exists' => 'Este email não está registado.',
        ]);

        $user = User::where('email', $request->email)
            ->whereNull('email_verified_at')
            ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Este email já foi verificado ou não existe.',
            ])->onlyInput('email');
        }

        // Gerar novo token
        $token = Str::random(64);
        $user->update([
            'email_verification_token' => Hash::make($token),
        ]);

        // Enviar email
        $verificationUrl = route('email.verify', ['token' => $token]);
        \Illuminate\Support\Facades\Mail::to($user->email)
            ->send(new \App\Mail\EmailVerificationMail($verificationUrl, $user->nome));

        return back()->with('success', 'Email de verificação reenviado! Verifica a tua caixa de entrada.');
    }
}
