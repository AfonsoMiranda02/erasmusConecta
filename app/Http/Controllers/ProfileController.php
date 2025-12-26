<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\ProfilePasswordRequest;
use App\Http\Requests\ProfileAvatarRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Mostra a página de perfil
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Atualiza os dados do perfil
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'nome' => $request->nome,
            'telefone' => $request->telefone,
        ]);

        return redirect()->route('profile.show')->with('success', __('common.messages.success.profile_updated'));
    }

    /**
     * Atualiza a palavra-passe
     */
    public function updatePassword(ProfilePasswordRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')->with('success', __('common.messages.success.password_updated'));
    }

    /**
     * Atualiza o avatar
     */
    public function updateAvatar(ProfileAvatarRequest $request)
    {
        $user = Auth::user();

        // Eliminar avatar antigo se existir
        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        // Guardar novo avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        
        $user->update([
            'avatar_path' => $path,
        ]);

        return redirect()->route('profile.show')->with('success', __('common.messages.success.avatar_updated'));
    }
}
