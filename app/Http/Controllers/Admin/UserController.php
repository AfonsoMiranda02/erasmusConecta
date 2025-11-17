<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('nome')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255', 'unique:users,nome'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'num_processo' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
            'is_aprovado' => ['boolean'],
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'nome.unique' => 'Este nome já está em uso.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser válido.',
            'email.unique' => 'Este email já está em uso.',
            'num_processo.required' => 'O número de processo é obrigatório.',
            'password.required' => 'A palavra-passe é obrigatória.',
            'password.min' => 'A palavra-passe deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'As palavras-passe não coincidem.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['is_aprovado'] = $request->has('is_aprovado') ? true : false;

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilizador criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(['eventosCriados', 'inscricoes.evento', 'convitesEnviados', 'convitesRecebidos'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255', 'unique:users,nome,' . $id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'num_processo' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
            'is_aprovado' => ['boolean'],
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'nome.unique' => 'Este nome já está em uso.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser válido.',
            'email.unique' => 'Este email já está em uso.',
            'num_processo.required' => 'O número de processo é obrigatório.',
            'password.min' => 'A palavra-passe deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'As palavras-passe não coincidem.',
        ]);

        // Só atualiza a password se foi fornecida
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['is_aprovado'] = $request->has('is_aprovado') ? true : false;

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilizador atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        
        // Verificar se há atividades criadas
        if ($user->eventosCriados()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Não é possível eliminar o utilizador porque existem atividades associadas.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilizador eliminado com sucesso!');
    }
}
