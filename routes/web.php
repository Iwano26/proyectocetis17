<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('LoginViews/inicio');
});

Route::get('/registrar', function () {
    return view('RegisterViews/registrarusuario');
});

Route::get('/resetpass', function () {
    return view('ResetPasswordViews/olvidosucontrasennia');
});

Route::get('/gestionusuario', function () {
    return view('GestionUsuarioViews/usuarios');
});

Route::get('/gestionbiblioteca', function () {
    return view('GestionBibliotecaViews/biblioteca');
});

Route::get('/gestioncurso', function () {
    return view('GestionCursoViews/curso');
});

Route::get('/gestionasesoria', function () {
    return view('GestionAsesoriaViews/asesoria');
});
