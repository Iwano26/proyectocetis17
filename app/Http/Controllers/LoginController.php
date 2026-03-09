<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin() {
        return view("LoginViews/inicio");
    }

    public function login(Request $request) {
        $request->validate([
            'correo' => 'required|email',
            'pass' => 'required'
        ]);

        $credenciales = [
            'correo' => $request->correo,
            'password' => $request->pass
        ];

        if (Auth::attempt($credenciales)) {
            $request->session()->regenerate();
            $request->session()->save(); 

            return redirect()->intended('/principal'); 
        }

        return back()->withErrors([
            'login' => 'Correo o contraseña incorrectos.'
        ])->withInput($request->only('correo'));
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}