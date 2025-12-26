<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePreferencesRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    /**
     * Mostra a página de configurações
     */
    public function edit()
    {
        $user = Auth::user();
        return view('settings.edit', compact('user'));
    }

    /**
     * Atualiza os dados do perfil
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'nome' => $request->nome,
            'telefone' => $request->telefone,
        ]);

        return redirect()->route('settings.edit')->with('success', __('common.messages.success.profile_updated'));
    }

    /**
     * Atualiza a palavra-passe
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('settings.edit')->with('success', __('common.messages.success.password_updated'));
    }

    /**
     * Atualiza as preferências do utilizador
     */
    public function updatePreferences(UpdatePreferencesRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'prefer_email' => $request->has('prefer_email') && $request->prefer_email == '1',
            'locale' => $request->locale ?? 'pt_PT',
        ]);

        // Aplicar o novo locale imediatamente
        if ($user->locale) {
            App::setLocale($user->locale);
        }

        return redirect()->route('settings.edit')->with('success', __('settings.updated_successfully'));
    }
}
