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

        // Buscar utilizadores para convidar (todos os utilizadores ativos e aprovados)
        // Pode convidar estudantes (E), professores (P), intercambistas (I)
        $utilizadores = User::where('is_active', true)
            ->where('is_aprovado', true)
            ->where(function($q) {
                $q->where('num_processo', 'LIKE', 'E%')
                  ->orWhere('num_processo', 'LIKE', 'P%')
                  ->orWhere('num_processo', 'LIKE', 'I%');
            })
            ->orderBy('nome')
            ->get();

        // Buscar convites já enviados para esta atividade
        $convitesExistentes = convite::where('evento_id', $eventoId)
            ->pluck('for_user')
            ->toArray();

        return view('convites.create', compact('evento', 'utilizadores', 'convitesExistentes'));
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
            'for_user.required' => 'Deve selecionar pelo menos um utilizador.',
            'for_user.array' => 'Formato inválido.',
            'for_user.min' => 'Deve selecionar pelo menos um utilizador.',
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

        // Verificar se todos os utilizadores são válidos (E, P ou I, mas não A)
        $usersConvidados = User::whereIn('id', $validated['for_user'])->get();
        $utilizadoresInvalidos = [];
        
        foreach ($usersConvidados as $userConvidado) {
            $primeiroChar = !empty($userConvidado->num_processo) ? strtoupper(trim($userConvidado->num_processo)[0]) : '';
            // Não se pode convidar admins
            if ($primeiroChar === 'A' || empty($primeiroChar)) {
                $utilizadoresInvalidos[] = $userConvidado->nome;
            }
        }

        if (!empty($utilizadoresInvalidos)) {
            return back()->withErrors(['for_user' => 'Não podes convidar os seguintes utilizadores: ' . implode(', ', $utilizadoresInvalidos)])->withInput();
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

        // Opcional: criar inscrição automaticamente ao aceitar o convite
        // Verificar se já existe inscrição
        $inscricaoExistente = \App\Models\inscricao::where('evento_id', $convite->evento_id)
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$inscricaoExistente && $convite->evento) {
            \App\Models\inscricao::create([
                'evento_id' => $convite->evento_id,
                'user_id' => $user->id,
                'estado' => 'aprovada', // Aceitar convite = inscrição aprovada
                'date_until_cancelation' => $convite->evento->data_hora,
                'is_active' => true,
            ]);
        }

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
     * Mostra a lista de convites recebidos pelo utilizador
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Query base
        $query = convite::where('for_user', $user->id)
            ->with(['evento.tipo', 'evento.criador', 'remetente']);
        
        // Filtrar por estado se fornecido
        if ($request->has('estado') && $request->estado) {
            $query->where('estado', $request->estado);
        }
        
        // Buscar todos os convites recebidos pelo utilizador
        $convites = $query->orderBy('created_at', 'desc')->get();
        
        // Contar convites pendentes (sempre, independente do filtro)
        $convitesPendentes = convite::where('for_user', $user->id)
            ->where('estado', 'pendente')
            ->count();
        
        return view('convites.index', compact('convites', 'convitesPendentes'));
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
