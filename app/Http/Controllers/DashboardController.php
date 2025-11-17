<?php

namespace App\Http\Controllers;

use App\Models\evento;
use App\Models\inscricao;
use App\Models\convite;
use App\Models\notificacoes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Mostra a dashboard principal
     */
    public function index()
    {
        $user = Auth::user();

        // Estatísticas gerais
        $stats = [
            'atividades_em_curso' => evento::where('status', 'aprovado')
                ->where('data_hora', '>=', now())
                ->count(),
            'candidaturas_submetidas' => inscricao::where('user_id', $user->id)
                ->where('is_active', true)
                ->count(),
            'candidaturas_aprovadas' => inscricao::where('user_id', $user->id)
                ->where('estado', 'aprovada')
                ->where('is_active', true)
                ->count(),
            'mensagens_nao_lidas' => notificacoes::where('user_id', $user->id)
                ->where('is_seen', false)
                ->count(),
        ];

        // Inscrições recentes do utilizador
        $inscricoes_recentes = inscricao::where('user_id', $user->id)
            ->with('evento')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Convites recebidos recentes
        $convites_recentes = convite::where('for_user', $user->id)
            ->with(['evento', 'remetente'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Notificações recentes
        $notificacoes_recentes = notificacoes::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Eventos próximos
        $eventos_proximos = evento::where('status', 'aprovado')
            ->where('data_hora', '>=', now())
            ->orderBy('data_hora', 'asc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'inscricoes_recentes',
            'convites_recentes',
            'notificacoes_recentes',
            'eventos_proximos'
        ));
    }
}
