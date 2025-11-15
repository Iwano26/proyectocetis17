<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('inicio');
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
