<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUsuarioActivo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        dd(Auth::user()->estado);
        if (Auth::check() && Auth::user()->estado == 1) {

            return $next($request);
        }

        // Si no, redirigir o mostrar un mensaje de error
        //return response()->view('errors.403');
        Auth::logout();
    }
}
