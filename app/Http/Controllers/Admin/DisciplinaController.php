<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\disciplina;
use Illuminate\Http\Request;

class DisciplinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $disciplinas = disciplina::with('cursos')->orderBy('nome')->paginate(15);
        return view('admin.disciplinas.index', compact('disciplinas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.disciplinas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'sigla' => ['required', 'string', 'max:50'],
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'sigla.required' => 'A sigla é obrigatória.',
        ]);

        disciplina::create($validated);

        return redirect()->route('admin.disciplinas.index')
            ->with('success', 'Disciplina criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $disciplina = disciplina::with(['cursos', 'professores.professor', 'professores.escola'])->findOrFail($id);
        return view('admin.disciplinas.show', compact('disciplina'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $disciplina = disciplina::findOrFail($id);
        return view('admin.disciplinas.edit', compact('disciplina'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $disciplina = disciplina::findOrFail($id);

        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'sigla' => ['required', 'string', 'max:50'],
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'sigla.required' => 'A sigla é obrigatória.',
        ]);

        $disciplina->update($validated);

        return redirect()->route('admin.disciplinas.index')
            ->with('success', 'Disciplina atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $disciplina = disciplina::findOrFail($id);
        
        // Verificar se há professores ou cursos associados
        if ($disciplina->professores()->count() > 0 || $disciplina->cursos()->count() > 0) {
            return redirect()->route('admin.disciplinas.index')
                ->with('error', 'Não é possível eliminar a disciplina porque existem professores ou cursos associados.');
        }

        $disciplina->delete();

        return redirect()->route('admin.disciplinas.index')
            ->with('success', 'Disciplina eliminada com sucesso!');
    }
}
