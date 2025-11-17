<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\escolas;
use Illuminate\Http\Request;

class EscolaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $escolas = escolas::orderBy('nome')->paginate(15);
        return view('admin.escolas.index', compact('escolas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.escolas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'morada' => ['required', 'string', 'max:255'],
            'codigo_postal' => ['required', 'string', 'max:20'],
            'instituicao' => ['required', 'string', 'max:255'],
            'abreviacao' => ['required', 'string', 'max:50'],
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'morada.required' => 'A morada é obrigatória.',
            'codigo_postal.required' => 'O código postal é obrigatório.',
            'instituicao.required' => 'A instituição é obrigatória.',
            'abreviacao.required' => 'A abreviação é obrigatória.',
        ]);

        escolas::create($validated);

        return redirect()->route('admin.escolas.index')
            ->with('success', 'Escola criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $escola = escolas::with(['professores.professor', 'alunos.aluno', 'alunos.curso'])->findOrFail($id);
        return view('admin.escolas.show', compact('escola'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $escola = escolas::findOrFail($id);
        return view('admin.escolas.edit', compact('escola'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $escola = escolas::findOrFail($id);

        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'morada' => ['required', 'string', 'max:255'],
            'codigo_postal' => ['required', 'string', 'max:20'],
            'instituicao' => ['required', 'string', 'max:255'],
            'abreviacao' => ['required', 'string', 'max:50'],
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'morada.required' => 'A morada é obrigatória.',
            'codigo_postal.required' => 'O código postal é obrigatório.',
            'instituicao.required' => 'A instituição é obrigatória.',
            'abreviacao.required' => 'A abreviação é obrigatória.',
        ]);

        $escola->update($validated);

        return redirect()->route('admin.escolas.index')
            ->with('success', 'Escola atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $escola = escolas::findOrFail($id);
        
        // Verificar se há professores ou alunos associados
        if ($escola->professores()->count() > 0 || $escola->alunos()->count() > 0) {
            return redirect()->route('admin.escolas.index')
                ->with('error', 'Não é possível eliminar a escola porque existem professores ou alunos associados.');
        }

        $escola->delete();

        return redirect()->route('admin.escolas.index')
            ->with('success', 'Escola eliminada com sucesso!');
    }
}
