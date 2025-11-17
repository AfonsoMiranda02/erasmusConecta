<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se o utilizador está autenticado
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Verificar se o utilizador é admin
        $user = auth()->user();
        if ($user->cargo !== 'admin') {
            abort(403, 'Acesso negado. Apenas administradores podem aceder a esta área.');
        }

        return $next($request);
    }
}
