<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentoRequest;
use App\Models\documentos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    /**
     * Mostra a lista de documentos do utilizador autenticado
     */
    public function index()
    {
        $user = Auth::user();
        
        // Verificar se é estudante em mobilidade
        if (!in_array($user->cargo, ['estudante', 'intercambista'])) {
            abort(403, 'Acesso negado. Apenas estudantes em mobilidade podem aceder a esta página.');
        }

        $documentos = documentos::where('entregue_por_user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('documentos.index', compact('documentos'));
    }

    /**
     * Guarda um novo documento
     */
    public function store(StoreDocumentoRequest $request)
    {
        $user = Auth::user();

        // Guardar o ficheiro
        $file = $request->file('documento');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->store('documentos', 'public');

        // Criar o documento na base de dados
        // Usar o tipo de documento como morph_type (string) e user id como morph_id
        documentos::create([
            'morph_type' => $request->tipo_documento, // Guardar o tipo de documento aqui
            'morph_id' => $user->id,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'dh_entrega' => now(),
            'entregue_por_user_id' => $user->id,
            'estado' => 'pendente',
        ]);

        return redirect()->route('documentos.index')
            ->with('success', __('documentos.messages.submitted_success'));
    }

    /**
     * Permite reenviar um documento rejeitado
     */
    public function resubmit(Request $request, $id)
    {
        $user = Auth::user();
        
        $documento = documentos::findOrFail($id);

        // Verificar se o documento pertence ao utilizador e está rejeitado
        if ($documento->entregue_por_user_id !== $user->id) {
            abort(403, 'Não tens permissão para reenviar este documento.');
        }

        if ($documento->estado !== 'rejeitado') {
            return redirect()->route('documentos.index')
                ->with('error', 'Apenas documentos rejeitados podem ser reenviados.');
        }

        // Validar o novo ficheiro
        $request->validate([
            'documento' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
        ]);

        // Eliminar o ficheiro antigo
        if ($documento->file_path && Storage::disk('public')->exists($documento->file_path)) {
            Storage::disk('public')->delete($documento->file_path);
        }

        // Guardar o novo ficheiro
        $file = $request->file('documento');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->store('documentos', 'public');

        // Atualizar o documento
        $documento->update([
            'file_name' => $fileName,
            'file_path' => $filePath,
            'dh_entrega' => now(),
            'estado' => 'pendente',
            'mensagem_rejeicao' => null, // Limpar mensagem de rejeição
        ]);

        return redirect()->route('documentos.index')
            ->with('success', __('documentos.messages.resubmitted_success'));
    }
}
