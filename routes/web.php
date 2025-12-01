<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GestionUsuarioController;

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

Route::resource('gestionusuario', GestionUsuarioController::class)->only([
    'index',   // GET /gestionusuario (Listar)
    'store',   // POST /gestionusuario (Crear)
    'update',  // PUT/PATCH /gestionusuario/{gestionusuario} (Editar/Actualizar)
    'destroy'  // DELETE /gestionusuario/{gestionusuario} (Eliminar)
]);

Route::get('/gestionbiblioteca', function () {
    return view('GestionBibliotecaViews/biblioteca');
});

Route::get('/gestioncurso', function () {
    return view('GestionCursoViews/curso');
});

Route::get('/gestionasesoria', function () {
    return view('GestionAsesoriaViews/asesoria');
});
