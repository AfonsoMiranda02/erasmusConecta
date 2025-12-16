<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RejectDocumentoRequest;
use App\Models\documentos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    /**
     * Lista todos os documentos submetidos com filtros e paginação
     */
    public function index(Request $request)
    {
        $query = documentos::with('user')->orderBy('created_at', 'desc');

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por utilizador
        if ($request->filled('user_id')) {
            $query->where('entregue_por_user_id', $request->user_id);
        }

        $documentos = $query->paginate(15)->withQueryString();

        // Lista de utilizadores para o filtro
        $users = User::whereIn('cargo', ['estudante', 'intercambista'])
            ->orderBy('nome')
            ->get();

        return view('admin.documentos.index', compact('documentos', 'users'));
    }

    /**
     * Mostra detalhes de um documento específico
     */
    public function show($id)
    {
        $documento = documentos::with('user')->findOrFail($id);
        return view('admin.documentos.show', compact('documento'));
    }

    /**
     * Aprova um documento
     */
    public function approve($id)
    {
        $documento = documentos::findOrFail($id);
        
        $documento->update([
            'estado' => 'aprovado',
            'mensagem_rejeicao' => null,
        ]);

        return redirect()->route('admin.documentos.index')
            ->with('success', 'Documento aprovado com sucesso!');
    }

    /**
     * Rejeita um documento com mensagem obrigatória
     */
    public function reject(RejectDocumentoRequest $request, $id)
    {
        $documento = documentos::findOrFail($id);
        
        $documento->update([
            'estado' => 'rejeitado',
            'mensagem_rejeicao' => $request->mensagem_rejeicao,
        ]);

        return redirect()->route('admin.documentos.index')
            ->with('success', 'Documento rejeitado com sucesso!');
    }

    /**
     * Faz download do ficheiro do documento
     */
    public function download($id)
    {
        $documento = documentos::findOrFail($id);

        if (!Storage::disk('public')->exists($documento->file_path)) {
            abort(404, 'Ficheiro não encontrado.');
        }

        return Storage::disk('public')->download($documento->file_path, $documento->file_name);
    }
}
