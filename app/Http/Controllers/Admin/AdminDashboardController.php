<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\evento;
use App\Models\convite;
use App\Models\notificacoes;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Mostra a dashboard de administração
     */
    public function index()
    {
        $user = auth()->user(); // deve ser admin (protegido pelo middleware)

        // Estatísticas de utilizadores
        $totalUsers = User::count();
        $totalAdmins = User::whereRaw("UPPER(LEFT(num_processo, 1)) = 'A'")->count();
        $totalProfessores = User::whereRaw("UPPER(LEFT(num_processo, 1)) = 'P'")->count();
        $totalIntercambistas = User::whereRaw("UPPER(LEFT(num_processo, 1)) = 'I'")->count();
        $totalEstudantes = User::whereRaw("UPPER(LEFT(num_processo, 1)) = 'E'")->count();

        // Estatísticas de atividades
        $totalAtividades = evento::count();
        $totalAtividadesPublic = evento::where('is_public', true)->count();
        $totalAtividadesPrivadas = evento::where('is_public', false)->count();
        $atividadesRecentes = evento::with(['criador', 'tipo'])
            ->latest()
            ->take(5)
            ->get();

        // Convites pendentes
        $convitesPendentes = convite::with(['evento', 'destinatario', 'remetente'])
            ->where('estado', 'pendente')
            ->latest()
            ->take(5)
            ->get();

        // Mensagens não lidas do admin
        $mensagensNaoLidas = notificacoes::where('user_id', $user->id)
            ->where('is_seen', false)
            ->count();

        return view('admin.dashboard', compact(
            'user',
            'totalUsers',
            'totalAdmins',
            'totalProfessores',
            'totalIntercambistas',
            'totalEstudantes',
            'totalAtividades',
            'totalAtividadesPublic',
            'totalAtividadesPrivadas',
            'atividadesRecentes',
            'convitesPendentes',
            'mensagensNaoLidas'
        ));
    }
}
