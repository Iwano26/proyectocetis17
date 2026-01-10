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

        // 4. Lógica de Redirección según el rol de tu DB
        if ($user->rol === 'Administrador') {
            return redirect()->intended('/principal');
        } 
        
        // Si es Estudiante o Asesor (maestro), van a /menu
        if ($user->rol === 'Estudiante' || $user->rol === 'Asesor') {
            return redirect()->intended('/menu');
        }

        // Redirección por defecto si no coincide ninguno de los anteriores
        return redirect()->intended('/home');
    }

    // Error si fallan las credenciales
    return back()->withErrors([
        'login' => 'Correo o contraseña incorrectos.'
    ])->withInput();
}
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/LoginViews/inicio');
    }
}
