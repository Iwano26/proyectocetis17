<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->rol !== 'Administrador') {
            return redirect('/principal')->with('mensaje', 'No tienes permisos de administrador.');
        }

        return $next($request);
    }
}