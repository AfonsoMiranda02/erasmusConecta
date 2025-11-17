<?php

namespace App\Http\Controllers;

use App\Models\evento;
use App\Models\inscricao;
use App\Models\tipo;
use App\Models\convite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AtividadeController extends Controller
{
    /**
     * Mostra a lista de atividades
     */
    public function index()
    {
        $user = Auth::user();
        $cargo = $user->cargo;

        // Query base de eventos
        $query = evento::with(['tipo', 'criador', 'inscricoes']);

        // Verifica diretamente o num_processo
        $primeiroChar = !empty($user->num_processo) ? strtoupper(trim($user->num_processo)[0]) : '';

        // Filtros baseados no cargo
        if ($primeiroChar === 'A') {
            // Admin vê todas as atividades (pendentes, aprovadas, rejeitadas)
            $eventos = $query->orderBy('data_hora', 'desc')->get();
        } elseif ($primeiroChar === 'P') {
            // Professor vê as suas atividades + atividades aprovadas
            $eventos = $query->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('status', 'aprovado');
            })->orderBy('data_hora', 'desc')->get();
        } else {
            // Estudante e Intercambista: apenas atividades aprovadas
            // Se for intercambista, só vê atividades públicas OU atividades para as quais foi convidado
            if ($primeiroChar === 'I') {
                // Buscar IDs de atividades para as quais foi convidado
                $eventosConvidados = convite::where('for_user', $user->id)
                    ->where('estado', 'aceite')
                    ->pluck('evento_id')
                    ->toArray();
                
                $eventos = $query->where('status', 'aprovado')
                    ->where('data_hora', '>=', now())
                    ->where(function($q) use ($eventosConvidados) {
                        $q->where('is_public', true)
                          ->orWhereIn('id', $eventosConvidados);
                    })
                    ->orderBy('data_hora', 'asc')
                    ->get();
            } else {
                // Estudante: apenas atividades públicas e aprovadas
                $eventos = $query->where('status', 'aprovado')
                    ->where('is_public', true)
                    ->where('data_hora', '>=', now())
                    ->orderBy('data_hora', 'asc')
                    ->get();
            }
        }

        // Obter tipos para filtros (se necessário)
        $tipos = tipo::all();

        // Verificar inscrições do utilizador
        $inscricoesUser = inscricao::where('user_id', $user->id)
            ->where('is_active', true)
            ->pluck('evento_id')
            ->toArray();

        return view('atividades.index', compact('eventos', 'tipos', 'cargo', 'inscricoesUser'));
    }

    /**
     * Mostra os detalhes de uma atividade
     */
    public function show($id)
    {
        $user = Auth::user();
        $evento = evento::with(['tipo', 'criador', 'aprovador', 'inscricoes.user'])
            ->findOrFail($id);

        // Verificar permissões usando a Policy
        if (Gate::denies('view', $evento)) {
            abort(403, 'Não tens permissão para ver esta atividade.');
        }

        // Verificar se o utilizador está inscrito
        $inscricao = inscricao::where('evento_id', $id)
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        // Verificar se pode inscrever-se (apenas estudantes e intercambistas)
        $podeInscrever = false;
        $primeiroChar = !empty($user->num_processo) ? strtoupper(trim($user->num_processo)[0]) : '';
        if (in_array($primeiroChar, ['E', 'I']) && $evento->status === 'aprovado') {
            $inscricoesCount = inscricao::where('evento_id', $id)
                ->where('is_active', true)
                ->where('estado', 'aprovada')
                ->count();
            
            $podeInscrever = !$inscricao && 
                            $evento->data_hora >= now() && 
                            ($evento->capacidade == 0 || $inscricoesCount < $evento->capacidade);
        }

        // Buscar convites da atividade
        $convites = convite::where('evento_id', $id)
            ->with(['destinatario', 'remetente'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Verificar se pode criar convites
        $podeCriarConvite = false;
        if (in_array($primeiroChar, ['A', 'P', 'E'])) {
            if ($primeiroChar === 'A') {
                $podeCriarConvite = true;
            } else {
                $podeCriarConvite = $evento->created_by === $user->id;
            }
        }

        // Verificar convites recebidos pelo utilizador atual
        $conviteRecebido = convite::where('evento_id', $id)
            ->where('for_user', $user->id)
            ->first();

        return view('atividades.show', compact('evento', 'inscricao', 'podeInscrever', 'convites', 'podeCriarConvite', 'conviteRecebido'));
    }

    /**
     * Mostra o formulário de criação de atividade
     */
    public function create()
    {
        $user = Auth::user();

        // Debug: verificar dados do utilizador
        \Log::info('AtividadeController::create - Dados do utilizador', [
            'user_id' => $user->id,
            'nome' => $user->nome,
            'num_processo' => $user->num_processo,
            'num_processo_vazio' => empty($user->num_processo),
            'primeiro_char' => !empty($user->num_processo) ? strtoupper(trim($user->num_processo)[0]) : 'VAZIO',
        ]);

        // Verificar permissões usando a Policy
        $canCreate = Gate::allows('create', evento::class);
        \Log::info('AtividadeController::create - Resultado da Policy', [
            'can_create' => $canCreate,
            'gate_allows' => $canCreate,
            'gate_denies' => Gate::denies('create', evento::class),
        ]);

        if (!$canCreate) {
            \Log::warning('AtividadeController::create - Acesso negado', [
                'user_id' => $user->id,
                'num_processo' => $user->num_processo,
            ]);
            abort(403, 'Não tens permissão para criar atividades. Verifica se o teu num_processo está correto (deve começar com A, P ou E).');
        }

        $tipos = tipo::all();
        return view('atividades.create', compact('tipos'));
    }

    /**
     * Guarda uma nova atividade
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Verificar permissões usando a Policy
        if (Gate::denies('create', evento::class)) {
            abort(403, 'Não tens permissão para criar atividades.');
        }

        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'tipo_id' => ['required', 'exists:tipo,id'],
            'descricao' => ['required', 'string'],
            'data_hora' => ['required', 'date', 'after:now'],
            'local' => ['required', 'string', 'max:255'],
            'capacidade' => ['nullable', 'integer', 'min:0'],
            'is_public' => ['nullable'],
        ], [
            'titulo.required' => 'O título é obrigatório.',
            'tipo_id.required' => 'Deve selecionar um tipo de atividade.',
            'descricao.required' => 'A descrição é obrigatória.',
            'data_hora.required' => 'A data e hora são obrigatórias.',
            'data_hora.after' => 'A data e hora devem ser futuras.',
            'local.required' => 'O local é obrigatório.',
            'capacidade.min' => 'A capacidade não pode ser negativa.',
        ]);

        // Verifica se é admin diretamente pelo num_processo
        $primeiroChar = !empty($user->num_processo) ? strtoupper(trim($user->num_processo)[0]) : '';
        $isAdmin = $primeiroChar === 'A';

        $evento = evento::create([
            'titulo' => $validated['titulo'],
            'tipo_id' => $validated['tipo_id'],
            'descricao' => $validated['descricao'],
            'data_hora' => $validated['data_hora'],
            'local' => $validated['local'],
            'capacidade' => $validated['capacidade'] ?? 0,
            'created_by' => $user->id,
            'status' => $isAdmin ? 'aprovado' : 'pendente', // Admin aprova automaticamente
            'is_public' => $request->input('is_public', true) == '1' || $request->input('is_public') === true,
        ]);

        if ($isAdmin) {
            $evento->update(['aprovado_por' => $user->id]);
        }

        return redirect()->route('atividades.show', $evento->id)
            ->with('success', 'Atividade criada com sucesso!');
    }

    /**
     * Mostra o formulário de edição de atividade
     */
    public function edit($id)
    {
        $user = Auth::user();
        $evento = evento::findOrFail($id);

        // Verificar permissões usando a Policy
        if (Gate::denies('update', $evento)) {
            abort(403, 'Não tens permissão para editar esta atividade.');
        }

        $tipos = tipo::all();
        return view('atividades.edit', compact('evento', 'tipos'));
    }

    /**
     * Atualiza uma atividade
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $evento = evento::findOrFail($id);

        // Verificar permissões usando a Policy
        if (Gate::denies('update', $evento)) {
            abort(403, 'Não tens permissão para editar esta atividade.');
        }

        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'tipo_id' => ['required', 'exists:tipo,id'],
            'descricao' => ['required', 'string'],
            'data_hora' => ['required', 'date'],
            'local' => ['required', 'string', 'max:255'],
            'capacidade' => ['nullable', 'integer', 'min:0'],
            'is_public' => ['nullable'],
        ], [
            'titulo.required' => 'O título é obrigatório.',
            'tipo_id.required' => 'Deve selecionar um tipo de atividade.',
            'descricao.required' => 'A descrição é obrigatória.',
            'data_hora.required' => 'A data e hora são obrigatórias.',
            'local.required' => 'O local é obrigatório.',
            'capacidade.min' => 'A capacidade não pode ser negativa.',
        ]);

        $evento->update([
            'titulo' => $validated['titulo'],
            'tipo_id' => $validated['tipo_id'],
            'descricao' => $validated['descricao'],
            'data_hora' => $validated['data_hora'],
            'local' => $validated['local'],
            'capacidade' => $validated['capacidade'] ?? 0,
            'is_public' => $request->input('is_public', true) == '1' || $request->input('is_public') === true,
        ]);

        // Se não for admin e a atividade estava aprovada, volta a pendente após edição
        if (!$user->isAdmin() && $evento->status === 'aprovado') {
            $evento->update([
                'status' => 'pendente',
                'aprovado_por' => null,
            ]);
        }

        return redirect()->route('atividades.show', $evento->id)
            ->with('success', 'Atividade atualizada com sucesso!');
    }

    /**
     * Elimina uma atividade
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $evento = evento::findOrFail($id);

        // Verificar permissões usando a Policy
        if (Gate::denies('delete', $evento)) {
            abort(403, 'Não tens permissão para eliminar esta atividade.');
        }

        $evento->delete();

        return redirect()->route('atividades.index')
            ->with('success', 'Atividade eliminada com sucesso!');
    }

    /**
     * Arquiva uma atividade
     */
    public function archive($id)
    {
        $user = Auth::user();
        $evento = evento::findOrFail($id);

        // Verificar permissões usando a Policy
        if (Gate::denies('archive', $evento)) {
            abort(403, 'Não tens permissão para arquivar esta atividade.');
        }

        // Por agora, arquivar significa mudar o status para 'rejeitado'
        // Podes ajustar conforme a lógica de negócio
        $evento->update(['status' => 'rejeitado']);

        return redirect()->route('atividades.show', $evento->id)
            ->with('success', 'Atividade arquivada com sucesso!');
    }

    /**
     * Aprova uma atividade (apenas Admin)
     */
    public function approve($id)
    {
        $user = Auth::user();
        $evento = evento::findOrFail($id);

        // Verificar permissões usando a Policy
        if (Gate::denies('approve', $evento)) {
            abort(403, 'Não tens permissão para aprovar atividades.');
        }

        $evento->update([
            'status' => 'aprovado',
            'aprovado_por' => $user->id,
        ]);

        return redirect()->route('atividades.show', $evento->id)
            ->with('success', 'Atividade aprovada com sucesso!');
    }

    /**
     * Rejeita uma atividade (apenas Admin)
     */
    public function reject($id)
    {
        $user = Auth::user();
        $evento = evento::findOrFail($id);

        // Verificar permissões usando a Policy
        if (Gate::denies('reject', $evento)) {
            abort(403, 'Não tens permissão para rejeitar atividades.');
        }

        $evento->update([
            'status' => 'rejeitado',
        ]);

        return redirect()->route('atividades.show', $evento->id)
            ->with('success', 'Atividade rejeitada.');
    }
}
