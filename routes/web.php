<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    RegisterController, 
    GestionUsuarioController, 
    LoginController, 
    ResetPasswordController2, 
    GestionCursoController, 
    BusquedaCursoController, 
    BibliotecaController, 
    CursoController,
    PerfilController
};

// --- PÚBLICAS ---
Route::get('/', function () {
    return Auth::check() ? redirect('/principal') : redirect('/login');
});

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('/register')->group(function () {
    Route::get('/', [RegisterController::class, 'create'])->name('register.create'); 
    Route::post('/post', [RegisterController::class, 'store'])->name('register.store'); 
    Route::get('/{correo}/confirmar', [RegisterController::class, 'ConfirmMail'])->name('register.confirmmail'); 
});

Route::get('/resetpass', function () { return view('ResetPasswordViews/olvidosucontrasennia'); })->name('password.request');
Route::put('/resetpass', [ResetPasswordController2::class, 'sendResetLinkEmail'])->name('pass');

// --- PROTEGIDAS ---
Route::middleware(['auth'])->group(function () {

    Route::get('/principal', function () {
        return view('principal');
    })->name('principal');


   Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');

    Route::resource('gestioncurso', GestionCursoController::class);

    Route::resource('gestionusuario', GestionUsuarioController::class);

    Route::controller(BibliotecaController::class)->group(function () {
        Route::get('/biblioteca', 'index')->name('biblioteca.index');
        Route::get('/biblioteca/subir', 'create')->name('biblioteca.create');
        Route::post('/biblioteca/guardar', 'store')->name('biblioteca.guardar');
        Route::delete('/biblioteca/eliminar/{id}', 'destroy')->name('biblioteca.eliminar');
        Route::get('/biblioteca/editar/{id}', 'edit')->name('biblioteca.edit');
        Route::put('/biblioteca/actualizar/{id}', 'update')->name('biblioteca.actualizar');
    });

    Route::get('/buscarcurso', [CursoController::class, 'index'])->name('cursos.index');
    Route::resource('cursos', CursoController::class)->except(['index']);
    Route::get('/modificarperfil', function () { return view('GestionUsuarioViews/perfil'); });
    Route::get('/agenda', function () { return view('Agenda'); });
});