<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\curso;
use App\Models\disciplina;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cursos = curso::with('disciplinas')->orderBy('nome')->paginate(15);
        return view('admin.cursos.index', compact('cursos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $disciplinas = disciplina::orderBy('nome')->get();
        return view('admin.cursos.create', compact('disciplinas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'disciplinas' => ['nullable', 'array'],
            'disciplinas.*' => ['exists:disciplinas,id'],
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'disciplinas.*.exists' => 'Uma das disciplinas selecionadas não existe.',
        ]);

        $curso = curso::create(['nome' => $validated['nome']]);

        if (isset($validated['disciplinas'])) {
            $curso->disciplinas()->sync($validated['disciplinas']);
        }

        return redirect()->route('admin.cursos.index')
            ->with('success', 'Curso criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $curso = curso::with(['disciplinas', 'alunos.aluno', 'alunos.escola'])->findOrFail($id);
        return view('admin.cursos.show', compact('curso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $curso = curso::with('disciplinas')->findOrFail($id);
        $disciplinas = disciplina::orderBy('nome')->get();
        return view('admin.cursos.edit', compact('curso', 'disciplinas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $curso = curso::findOrFail($id);

        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'disciplinas' => ['nullable', 'array'],
            'disciplinas.*' => ['exists:disciplinas,id'],
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'disciplinas.*.exists' => 'Uma das disciplinas selecionadas não existe.',
        ]);

        $curso->update(['nome' => $validated['nome']]);

        if (isset($validated['disciplinas'])) {
            $curso->disciplinas()->sync($validated['disciplinas']);
        } else {
            $curso->disciplinas()->sync([]);
        }

        return redirect()->route('admin.cursos.index')
            ->with('success', 'Curso atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $curso = curso::findOrFail($id);
        
        // Verificar se há alunos associados
        if ($curso->alunos()->count() > 0) {
            return redirect()->route('admin.cursos.index')
                ->with('error', 'Não é possível eliminar o curso porque existem alunos associados.');
        }

        $curso->disciplinas()->detach();
        $curso->delete();

        return redirect()->route('admin.cursos.index')
            ->with('success', 'Curso eliminado com sucesso!');
    }
}
