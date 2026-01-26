<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrincipalController extends Controller
{
    public function index()
    {

        $user = Auth::user();
        // Esta función es el "index" al que se refiere la ruta
        return view('principal', compact('user')); // Nombre de tu archivo principal.blade.php
    }
}
