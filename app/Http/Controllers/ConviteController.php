<?php

namespace App\Http\Controllers;

use App\Models\evento;
use App\Models\convite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ConviteController extends Controller
{
    /**
     * Mostra o formulário para criar convites para uma atividade
     */
    public function create($eventoId)
    {
        $user = Auth::user();
        $evento = evento::findOrFail($eventoId);

        // Verificar permissões - verifica diretamente
        $primeiroChar = !empty($user->num_processo) ? strtoupper(trim($user->num_processo)[0]) : '';
        
        if ($primeiroChar === 'I') {
            abort(403, 'Intercambistas não podem criar convites.');
        }
        
        if (!in_array($primeiroChar, ['A', 'P', 'E'])) {
            abort(403, 'Não tens permissão para criar convites.');
        }
        
        // Verificar se pode criar convites para esta atividade
        if ($primeiroChar !== 'A' && $evento->created_by !== $user->id) {
            abort(403, 'Só podes criar convites para atividades que criaste.');
        }

        // Buscar utilizadores intercambistas para convidar
        $intercambistas = User::where('num_processo', 'LIKE', 'I%')
            ->where('is_active', true)
            ->where('is_aprovado', true)
            ->orderBy('nome')
            ->get();

        // Buscar convites já enviados para esta atividade
        $convitesExistentes = convite::where('evento_id', $eventoId)
            ->pluck('for_user')
            ->toArray();

        return view('convites.create', compact('evento', 'intercambistas', 'convitesExistentes'));
    }

    /**
     * Guarda um novo convite
     */
    public function store(Request $request, $eventoId)
    {
        $user = Auth::user();
        $evento = evento::findOrFail($eventoId);

        // Verificar permissões - verifica diretamente
        $primeiroChar = !empty($user->num_processo) ? strtoupper(trim($user->num_processo)[0]) : '';
        
        if ($primeiroChar === 'I') {
            abort(403, 'Intercambistas não podem criar convites.');
        }
        
        if (!in_array($primeiroChar, ['A', 'P', 'E'])) {
            abort(403, 'Não tens permissão para criar convites.');
        }
        
        // Verificar se pode criar convites para esta atividade
        if ($primeiroChar !== 'A' && $evento->created_by !== $user->id) {
            abort(403, 'Só podes criar convites para atividades que criaste.');
        }

        $validated = $request->validate([
            'for_user' => ['required', 'array', 'min:1'],
            'for_user.*' => ['required', 'exists:users,id'],
            'descricao' => ['nullable', 'string', 'max:1000'],
        ], [
            'for_user.required' => 'Deve selecionar pelo menos um intercambista.',
            'for_user.array' => 'Formato inválido.',
            'for_user.min' => 'Deve selecionar pelo menos um intercambista.',
            'for_user.*.required' => 'Utilizador inválido.',
            'for_user.*.exists' => 'Um dos utilizadores selecionados não existe.',
            'descricao.max' => 'A descrição não pode exceder 1000 caracteres.',
        ]);

        // Buscar convites já existentes para esta atividade
        $convitesExistentes = convite::where('evento_id', $eventoId)
            ->whereIn('for_user', $validated['for_user'])
            ->pluck('for_user')
            ->toArray();

        if (!empty($convitesExistentes)) {
            $usersExistentes = User::whereIn('id', $convitesExistentes)->pluck('nome')->toArray();
            return back()->withErrors(['for_user' => 'Já existem convites para: ' . implode(', ', $usersExistentes)])->withInput();
        }

        // Verificar se todos os utilizadores são intercambistas
        $usersConvidados = User::whereIn('id', $validated['for_user'])->get();
        $naoIntercambistas = [];
        
        foreach ($usersConvidados as $userConvidado) {
            $primeiroChar = !empty($userConvidado->num_processo) ? strtoupper(trim($userConvidado->num_processo)[0]) : '';
            if ($primeiroChar !== 'I') {
                $naoIntercambistas[] = $userConvidado->nome;
            }
        }

        if (!empty($naoIntercambistas)) {
            return back()->withErrors(['for_user' => 'Só podes convidar intercambistas. Os seguintes não são intercambistas: ' . implode(', ', $naoIntercambistas)])->withInput();
        }

        // Criar múltiplos convites
        $convitesCriados = 0;
        foreach ($validated['for_user'] as $userId) {
            convite::create([
                'sent_by' => $user->id,
                'evento_id' => $eventoId,
                'for_user' => $userId,
                'titulo' => $evento->titulo,
                'descricao' => $validated['descricao'] ?? null,
                'data_hora' => $evento->data_hora,
                'estado' => 'pendente',
                'time_until_expire' => $evento->data_hora, // Expira na data da atividade
            ]);
            $convitesCriados++;
        }

        $mensagem = $convitesCriados === 1 
            ? 'Convite enviado com sucesso!' 
            : $convitesCriados . ' convites enviados com sucesso!';

        return redirect()->route('atividades.show', $eventoId)
            ->with('success', $mensagem);
    }

    /**
     * Aceita um convite
     */
    public function accept($id)
    {
        $user = Auth::user();
        $convite = convite::with('evento')->findOrFail($id);

        // Verificar permissões
        if (Gate::denies('accept', $convite)) {
            abort(403, 'Não tens permissão para aceitar este convite.');
        }

        $convite->update(['estado' => 'aceite']);

        return back()->with('success', 'Convite aceite com sucesso!');
    }

    /**
     * Rejeita um convite
     */
    public function reject($id)
    {
        $user = Auth::user();
        $convite = convite::findOrFail($id);

        // Verificar permissões
        if (Gate::denies('reject', $convite)) {
            abort(403, 'Não tens permissão para rejeitar este convite.');
        }

        $convite->update(['estado' => 'recusado']);

        return back()->with('success', 'Convite rejeitado.');
    }

    /**
     * Elimina um convite
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $convite = convite::findOrFail($id);

        // Verificar permissões
        if (Gate::denies('delete', $convite)) {
            abort(403, 'Não tens permissão para eliminar este convite.');
        }

        $eventoId = $convite->evento_id;
        $convite->delete();

        return redirect()->route('atividades.show', $eventoId)
            ->with('success', 'Convite eliminado com sucesso!');
    }
}
