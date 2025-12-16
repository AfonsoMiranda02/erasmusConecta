<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PushNotificationController extends Controller
{
    /**
     * Subscrever utilizador para push notifications
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'endpoint' => 'required|url',
            'keys' => 'required|array',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados de subscrição inválidos'
            ], 400);
        }

        try {
            $user = Auth::user();
            
            // Armazenar a subscrição na base de dados
            $subscription = $user->pushSubscriptions()->updateOrCreate(
                [
                    'endpoint' => $request->endpoint,
                ],
                [
                    'user_id' => $user->id,
                    'endpoint' => $request->endpoint,
                    'public_key' => $request->keys['p256dh'],
                    'auth_token' => $request->keys['auth'],
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Subscrição realizada com sucesso'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao subscrever push notification: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar subscrição'
            ], 500);
        }
    }

    /**
     * Cancelar subscrição de push notifications
     */
    public function unsubscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'endpoint' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Endpoint inválido'
            ], 400);
        }

        try {
            $user = Auth::user();
            
            $user->pushSubscriptions()
                ->where('endpoint', $request->endpoint)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Subscrição cancelada com sucesso'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao cancelar subscrição: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar cancelamento'
            ], 500);
        }
    }

    /**
     * Obter notificações do utilizador
     */
    public function getNotifications(Request $request)
    {
        try {
            $user = Auth::user();
            
            $notifications = $user->notificacoes()
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'titulo' => $notification->titulo,
                        'mensagem' => $notification->mensagem,
                        'is_seen' => $notification->is_seen,
                        'created_at' => $notification->created_at->diffForHumans(),
                        'created_at_full' => $notification->created_at->toISOString(),
                    ];
                });

            return response()->json($notifications, 200);

        } catch (\Exception $e) {
            Log::error('Erro ao buscar notificações: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar notificações'
            ], 500);
        }
    }

    /**
     * Obter contagem de notificações não lidas
     */
    public function getUnreadCount(Request $request)
    {
        try {
            $user = Auth::user();
            
            $count = $user->notificacoes()
                ->where('is_seen', false)
                ->count();

            return response()->json([
                'count' => $count
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao buscar contagem de notificações: ' . $e->getMessage());
            return response()->json([
                'count' => 0
            ], 200);
        }
    }

    /**
     * Marcar notificação como lida
     */
    public function markAsRead(Request $request, $id)
    {
        try {
            $user = Auth::user();
            
            $notification = $user->notificacoes()->findOrFail($id);
            $notification->update(['is_seen' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Notificação marcada como lida'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao marcar notificação como lida: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar notificação'
            ], 500);
        }
    }

    /**
     * Marcar todas as notificações como lidas
     */
    public function markAllAsRead(Request $request)
    {
        try {
            $user = Auth::user();
            
            $user->notificacoes()
                ->where('is_seen', false)
                ->update(['is_seen' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Todas as notificações foram marcadas como lidas'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao marcar todas as notificações como lidas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar notificações'
            ], 500);
        }
    }
}

