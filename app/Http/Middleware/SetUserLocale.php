<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetUserLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Se o utilizador estiver autenticado, usar o idioma das suas preferências
        if (Auth::check()) {
            $user = Auth::user();
            $locale = $user->locale ?? 'pt_PT';
            
            // Verificar se o locale é suportado
            $supportedLocales = ['pt_PT', 'en'];
            if (in_array($locale, $supportedLocales)) {
                App::setLocale($locale);
            } else {
                // Se o locale não for suportado, usar o padrão
                App::setLocale('pt_PT');
            }
        } else {
            // Se não estiver autenticado, usar o locale padrão
            App::setLocale('pt_PT');
        }

        return $next($request);
    }
}
