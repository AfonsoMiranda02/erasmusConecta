<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\evento;
use App\Models\tipo;
use App\Models\User;
use Illuminate\Http\Request;

class AtividadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eventos = evento::with(['tipo', 'criador'])->orderBy('data_hora', 'desc')->paginate(15);
        return view('admin.atividades.index', compact('eventos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos = tipo::orderBy('nome')->get();
        $users = User::orderBy('nome')->get();
        return view('admin.atividades.create', compact('tipos', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'tipo_id' => ['required', 'exists:tipo,id'],
            'descricao' => ['nullable', 'string'],
            'data_hora' => ['required', 'date'],
            'local' => ['nullable', 'string', 'max:255'],
            'capacidade' => ['nullable', 'integer', 'min:0'],
            'created_by' => ['required', 'exists:users,id'],
            'is_public' => ['boolean'],
            'status' => ['required', 'in:pendente,aprovado,rejeitado'],
        ], [
            'titulo.required' => 'O título é obrigatório.',
            'tipo_id.required' => 'O tipo é obrigatório.',
            'tipo_id.exists' => 'O tipo selecionado não existe.',
            'data_hora.required' => 'A data e hora são obrigatórias.',
            'data_hora.date' => 'A data e hora devem ser válidas.',
            'created_by.required' => 'O criador é obrigatório.',
            'created_by.exists' => 'O utilizador selecionado não existe.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser pendente, aprovado ou rejeitado.',
        ]);

        $validated['is_public'] = $request->has('is_public') ? true : false;
        
        // Se for aprovado, definir aprovado_por como o admin atual
        if ($validated['status'] === 'aprovado') {
            $validated['aprovado_por'] = auth()->id();
        }

        evento::create($validated);

        return redirect()->route('admin.atividades.index')
            ->with('success', 'Atividade criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $evento = evento::with(['tipo', 'criador', 'aprovador', 'inscricoes.user', 'convites.destinatario', 'convites.remetente'])->findOrFail($id);
        return view('admin.atividades.show', compact('evento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $evento = evento::findOrFail($id);
        $tipos = tipo::orderBy('nome')->get();
        $users = User::orderBy('nome')->get();
        return view('admin.atividades.edit', compact('evento', 'tipos', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $evento = evento::findOrFail($id);

        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'tipo_id' => ['required', 'exists:tipo,id'],
            'descricao' => ['nullable', 'string'],
            'data_hora' => ['required', 'date'],
            'local' => ['nullable', 'string', 'max:255'],
            'capacidade' => ['nullable', 'integer', 'min:0'],
            'created_by' => ['required', 'exists:users,id'],
            'is_public' => ['boolean'],
            'status' => ['required', 'in:pendente,aprovado,rejeitado'],
        ], [
            'titulo.required' => 'O título é obrigatório.',
            'tipo_id.required' => 'O tipo é obrigatório.',
            'tipo_id.exists' => 'O tipo selecionado não existe.',
            'data_hora.required' => 'A data e hora são obrigatórias.',
            'data_hora.date' => 'A data e hora devem ser válidas.',
            'created_by.required' => 'O criador é obrigatório.',
            'created_by.exists' => 'O utilizador selecionado não existe.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser pendente, aprovado ou rejeitado.',
        ]);

        $validated['is_public'] = $request->has('is_public') ? true : false;
        
        // Se mudou para aprovado e ainda não tinha aprovado_por, definir
        if ($validated['status'] === 'aprovado' && !$evento->aprovado_por) {
            $validated['aprovado_por'] = auth()->id();
        }

        $evento->update($validated);

        return redirect()->route('admin.atividades.index')
            ->with('success', 'Atividade atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $evento = evento::findOrFail($id);
        
        // Verificar se há inscrições ou convites
        if ($evento->inscricoes()->count() > 0 || $evento->convites()->count() > 0) {
            return redirect()->route('admin.atividades.index')
                ->with('error', 'Não é possível eliminar a atividade porque existem inscrições ou convites associados.');
        }

        $evento->delete();

        return redirect()->route('admin.atividades.index')
            ->with('success', 'Atividade eliminada com sucesso!');
    }
}
