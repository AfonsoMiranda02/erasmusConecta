<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdatePreferencesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ]);

        return redirect()->route('settings.edit')->with('success', 'Perfil atualizado com sucesso!');
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

        return redirect()->route('settings.edit')->with('success', 'Preferências atualizadas com sucesso!');
    }
}
