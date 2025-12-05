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
        // Validación
        $request->validate([
            'correo' => 'required',
            'pass' => 'required'
        ], [
            'correo.required' => 'El campo usuario es obligatorio.',
            'pass.required' => 'El campo contraseña es obligatorio.'
        ]);

        // Credenciales para Auth::attempt
        $credenciales = [
            'correo' => $request->correo,
            'password' => $request->pass  // "pass" se mapea a getAuthPassword()
        ];

        if (Auth::attempt($credenciales)) {
            $request->session()->regenerate();
            return redirect()->intended('/principal');
        }

        // Error personalizado
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
