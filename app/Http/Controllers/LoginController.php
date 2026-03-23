<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator; // Añadido para validación manual en API

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

    /**
     * Versión para App Móvil (React Native)
     */
    public function loginMovil(Request $request) {
        // 1. Validar que lleguen los datos (Si fallan, mandamos JSON, no redirección)
        $validator = Validator::make($request->all(), [
            'correo' => 'required|email',
            'pass' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos incompletos o formato inválido.',
                'errors' => $validator->errors()
            ], 422);
        }

        $credenciales = [
            'correo' => $request->correo,
            'password' => $request->pass
        ];

        // 2. Intentar el login
        if (Auth::attempt($credenciales)) {
            $user = Auth::user();
            
            // Opcional: Si usas Sanctum (instalado con artisan install:api), 
            // aquí podrías generar un token:
            // $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'user' => [
                    'nombre' => $user->nombre,
                    'apellidoPa' => $user->apellidoPa,
                    'rol' => $user->rol,
                    'correo' => $user->correo,
                ],
                'message' => 'Acceso concedido'
            ], 200);
        }

        // 3. Fallo de credenciales
        return response()->json([
            'success' => false,
            'message' => 'El correo o la contraseña son incorrectos.'
        ], 401);
    }
}