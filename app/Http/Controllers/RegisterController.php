<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function RegistroVista(){
        return view('AuthViews/InicioSesion');
    }
    public function RegistrarUsuarios(){

        return null;
    }

    
}