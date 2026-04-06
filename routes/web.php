<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    RegisterController, 
    GestionUsuarioController, 
    LoginController, 
    ResetPasswordController, 
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


// --- RECUPERACIÓN DE CONTRASEÑA ---
Route::get('/olvido-contrasennia',       [ResetPasswordController::class, 'showResetForm'])->name('password.request');
Route::post('/resetpass',                 [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/cambiarpass/{token}',        [ResetPasswordController::class, 'showResetFormWithToken'])->name('password.reset');
Route::post('/actualizar-contrasennia',   [ResetPasswordController::class, 'resetPassword'])->name('password.update');

// --- VERIFICACIÓN DE CUENTA ---
Route::get('/confirmar-cuenta/{correo}',  [ResetPasswordController::class, 'confirmarCuenta'])->name('correo.confirmar');




// --- PROTEGIDAS ---
Route::middleware(['auth'])->group(function () {

    Route::get('/principal', function () {
        return view('principal');
    })->name('principal');


   Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
    Route::get('/perfil/editar', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil/actualizar', [PerfilController::class, 'update'])->name('perfil.update');

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


    // Rutas para cursos
    Route::get('/buscarcurso', [CursoController::class, 'index'])->name('cursos.index');
    // Esta es la ruta que procesa el formulario
    Route::post('/cursos/guardar', [CursoController::class, 'store'])->name('cursos.store');
    // Esta es la que muestra el formulario
    Route::get('/cursos/crear', [CursoController::class, 'create'])->name('cursos.create');
    // Si usas rutas individuales, asegúrate de que tengan el ->name()
    Route::get('/cursos/{id}/editar', [CursoController::class, 'edit'])->name('cursos.edit');
    Route::put('/cursos/{id}', [CursoController::class, 'update'])->name('cursos.update');

    Route::get('/modificarperfil', function () { return view('GestionUsuarioViews/perfil'); });
    Route::get('/agenda', function () { return view('Agenda'); });
});