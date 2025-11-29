<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;

Route::get('/login', function () {
    return view('LoginViews/inicio');
});

Route::prefix('/register')->group(function () {
    // Rutas para el CRUD de Usuarios
    /*Paso 1*/Route::get('/', [RegisterController::class, 'create'])->name('register.create'); //Reemplaza showRegistrationForm
    /*Paso 2*/Route::post('/post', [RegisterController::class, 'store'])->name('register.store'); //Reemplaza register
    /*Paso 4*/Route::get('/{user}', [RegisterController::class, 'show'])->name('register.show');
    /*Paso 3*/Route::get('/{correo}/confirmar', [RegisterController::class, 'ConfirmMail'])->name('register.confirmmail'); //Reemplaza showRegistrationForm
    /*Paso 5*/Route::get('/{user}/edit', [RegisterController::class, 'edit'])->name('register.edit');
    /*Paso 6*/Route::put('/{user}', [RegisterController::class, 'update'])->name('register.update');
    /*Paso 7*/Route::delete('/{user}', [RegisterController::class, 'destroy'])->name('register.destroy');
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
