<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function index()
    {
        // Al estar protegida por el middleware 'auth', 
        // Auth::user() ya contiene todos los datos del usuario (nombre, correo, rol, etc.)
        $user = Auth::user();

        return view('Menu', compact('user'));
    }
}