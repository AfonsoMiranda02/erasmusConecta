<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordResetCode;
use App\Mail\PasswordResetCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
{
    /**
     * Mostra o formulário para solicitar reset de password
     */
    public function showRequestForm()
    {
        return view('auth.password.request');
    }

    /**
     * Envia o código de reset por email
     */
    public function sendCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser válido.',
            'email.exists' => 'Não encontramos nenhuma conta com este email.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $email = $request->email;

        // Verifica se o utilizador existe
        $user = User::where('email', $email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Não encontramos nenhuma conta com este email.'])->withInput();
        }

        // Apaga códigos antigos para este email
        PasswordResetCode::deleteExpiredForEmail($email);
        PasswordResetCode::deleteAllForEmail($email);

        // Gera código de 6 dígitos
        $code = str_pad((string) mt_rand(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Cria o registo na base de dados
        $resetCode = PasswordResetCode::create([
            'email' => $email,
            'code' => $code,
            'expires_at' => now()->addMinutes(15),
        ]);

        // Envia o email
        try {
            Mail::to($email)->send(new PasswordResetCodeMail($code, $user->nome));
        } catch (\Exception $e) {
            // Se falhar o envio, apaga o código e retorna erro
            $resetCode->delete();
            return back()->withErrors(['email' => 'Erro ao enviar o email. Tenta novamente mais tarde.'])->withInput();
        }

        // Redireciona para a página de reset com o email
        return redirect()->route('password.reset')->with('email_sent', true)->with('email', $email);
    }

    /**
     * Mostra o formulário para inserir código e nova password
     */
    public function showResetForm(Request $request)
    {
        $email = $request->session()->get('email') ?? old('email');
        return view('auth.password.reset', compact('email'));
    }

    /**
     * Processa o reset da password
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
            'code' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser válido.',
            'email.exists' => 'Não encontramos nenhuma conta com este email.',
            'code.required' => 'O código é obrigatório.',
            'code.size' => 'O código deve ter 6 dígitos.',
            'password.required' => 'A palavra-passe é obrigatória.',
            'password.min' => 'A palavra-passe deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'As palavras-passe não coincidem.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $email = $request->email;
        $code = $request->code;

        // Verifica se existe um código válido
        $resetCode = PasswordResetCode::where('email', $email)
            ->where('code', $code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$resetCode) {
            return back()->withErrors(['code' => 'Código inválido ou expirado.'])->withInput();
        }

        // Atualiza a password do utilizador
        $user = User::where('email', $email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Utilizador não encontrado.'])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Apaga todos os códigos para este email
        PasswordResetCode::deleteAllForEmail($email);

        // Redireciona para o login com mensagem de sucesso
        return redirect()->route('login')->with('password_reset', 'A tua palavra-passe foi redefinida com sucesso. Podes agora fazer login.');
    }
}
