<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view("LoginViews/inicio");
    }

public function login(Request $request)
{
    // 1. Validación
    $request->validate([
        'correo' => 'required',
        'pass' => 'required'
    ], [
        'correo.required' => 'El campo usuario es obligatorio.',
        'pass.required' => 'El campo contraseña es obligatorio.'
    ]);

    // 2. Credenciales
    $credenciales = [
        'correo' => $request->correo,
        'password' => $request->pass
    ];

    // 3. Intento de Login
    if (Auth::attempt($credenciales)) {
        $request->session()->regenerate();

        // Obtenemos el usuario que acaba de entrar
        $user = Auth::user();

        return match($user->rol){
        'Administrador' => redirect('/principal')
        ->with('bienvenido', $user->nombre),
        'Estudiante' => redirect('/menu')
        ->with('bienvenido', $user->nombre),
        default => redirect('/home')
         ->with('bienvenido', $user->nombre),
        };                
    }

    // Error si fallan las credenciales
    return back()->withErrors([
        'login' => 'Correo o contraseña incorrectos.'
    ])->withInput();
}
    public function logout(Request $request)
{
    Auth::logout();

    // Invalida la sesión del usuario
    $request->session()->invalidate();

    // Regenera el token CSRF para evitar ataques de fijación de sesión
    $request->session()->regenerateToken();

    // Redirige a la vista de login
    return redirect('/login'); 
}
}
