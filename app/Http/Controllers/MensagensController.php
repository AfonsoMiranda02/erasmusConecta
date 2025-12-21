<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MensagensController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Buscar todas as notificações do utilizador, ordenadas por data (mais recentes primeiro)
        $notificacoes = \App\Models\notificacoes::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Contar notificações não lidas
        $naoLidas = \App\Models\notificacoes::where('user_id', $user->id)
            ->where('is_seen', false)
            ->count();
        
        return view('mensagens.index', [
            'notificacoes' => $notificacoes,
            'naoLidas' => $naoLidas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user();
        
        // Buscar a notificação e verificar se pertence ao utilizador
        $notificacao = \App\Models\notificacoes::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['user', 'morphable'])
            ->firstOrFail();
        
        // Marcar como lida se ainda não estiver
        if (!$notificacao->is_seen) {
            $notificacao->update(['is_seen' => true]);
        }
        
        // Buscar informações sobre o modelo relacionado
        $morphable = null;
        $morphableInfo = null;
        
        if ($notificacao->morph_type && $notificacao->morph_id) {
            try {
                // Resolver o namespace completo do modelo
                $morphClass = 'App\\Models\\' . $notificacao->morph_type;
                
                if (class_exists($morphClass)) {
                    $morphable = $morphClass::find($notificacao->morph_id);
                } else {
                    $morphable = $notificacao->morphable;
                }
                
                if ($morphable) {
                    // Informações específicas baseadas no tipo
                    if ($notificacao->morph_type === 'evento') {
                        $morphable->load(['tipo', 'criador', 'aprovador']);
                        $morphableInfo = [
                            'tipo' => 'Atividade',
                            'titulo' => $morphable->titulo,
                            'descricao' => $morphable->descricao,
                            'data_hora' => $morphable->data_hora,
                            'local' => $morphable->local,
                            'status' => $morphable->status,
                            'criado_por' => $morphable->criador,
                            'aprovado_por' => $morphable->aprovador,
                            'tipo_atividade' => $morphable->tipo,
                            'link' => route('atividades.show', $morphable->id),
                        ];
                    } elseif ($notificacao->morph_type === 'convite') {
                        $morphable->load(['evento', 'remetente', 'destinatario']);
                        $morphableInfo = [
                            'tipo' => 'Convite',
                            'titulo' => $morphable->titulo,
                            'descricao' => $morphable->descricao,
                            'data_hora' => $morphable->data_hora,
                            'estado' => $morphable->estado,
                            'remetente' => $morphable->remetente,
                            'destinatario' => $morphable->destinatario,
                            'evento' => $morphable->evento,
                            'link' => $morphable->evento ? route('atividades.show', $morphable->evento->id) : null,
                        ];
                    } else {
                        // Tipo genérico
                        $morphableInfo = [
                            'tipo' => ucfirst($notificacao->morph_type),
                            'id' => $morphable->id ?? $notificacao->morph_id,
                        ];
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('Erro ao buscar morphable da notificação', [
                    'notificacao_id' => $notificacao->id,
                    'morph_type' => $notificacao->morph_type,
                    'morph_id' => $notificacao->morph_id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return view('mensagens.show', [
            'notificacao' => $notificacao,
            'morphable' => $morphable,
            'morphableInfo' => $morphableInfo,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
