<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrincipalController extends Controller
{
    public function index()
    {
        // Esta función es el "index" al que se refiere la ruta
        return view('principal'); // Nombre de tu archivo principal.blade.php
    }
}
