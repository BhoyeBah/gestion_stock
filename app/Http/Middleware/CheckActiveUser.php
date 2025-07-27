<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class CheckActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Si l'utilisateur est connecté mais désactivé
        if ($user && !$user->is_active) {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte a été désactivé. Contactez l’administrateur.',
            ]);
        }

        if ($user && !$user->tenant->is_active && $user->tenant->slug !== "platform") {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Votre Entreprise a été désactivé. Contactez l’administrateur.',
            ]);
        }

        

        return $next($request);
    }
}
