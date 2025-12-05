<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GestionUsuarioController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ResetPasswordController2;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\GestionCursoController; 
// 1. IMPORTANTE: Importamos el nuevo controlador aquí
use App\Http\Controllers\BusquedaCursoController; 
use Illuminate\Http\Request;

// --- RUTAS DE AUTENTICACIÓN ---
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// --- RUTAS DE REGISTRO ---
Route::prefix('/register')->group(function () {
    /*Paso 1*/Route::get('/', [RegisterController::class, 'create'])->name('register.create'); 
    /*Paso 2*/Route::post('/post', [RegisterController::class, 'store'])->name('register.store'); 
    /*Paso 4*/Route::get('/{user}', [RegisterController::class, 'show'])->name('register.show');
    /*Paso 3*/Route::get('/{correo}/confirmar', [RegisterController::class, 'ConfirmMail'])->name('register.confirmmail'); 
    /*Paso 5*/Route::get('/{user}/edit', [RegisterController::class, 'edit'])->name('register.edit');
    /*Paso 6*/Route::put('/{user}', [RegisterController::class, 'update'])->name('register.update');
    /*Paso 7*/Route::delete('/{user}', [RegisterController::class, 'destroy'])->name('register.destroy');
});

// --- RUTA DE RESET PASSWORD ---
Route::get('/resetpass', function () {
    return view('ResetPasswordViews/olvidosucontrasennia');
})->name('password.request'); 

Route::put('/resetpass', [ResetPasswordController2::class,'sendResetLinkEmail'])->name ('pass'); 

// --- RUTAS DE GESTIÓN (ADMINISTRACIÓN) ---
Route::resource('gestioncurso', GestionCursoController::class)->only([
    'index',   // GET /gestioncurso (Listar)
    'store',   // POST /gestioncurso (Crear)
    'update',  // PUT/PATCH /gestioncurso/{id} (Editar)
    'destroy'  // DELETE /gestioncurso/{id} (Eliminar)
]);

Route::resource('gestionusuario', GestionUsuarioController::class)->only([
    'index', 
    'store', 
    'update', 
    'destroy' 
]);

// --- RUTAS DE VISTAS ESTÁTICAS ---
Route::get('/gestionbiblioteca', function () {
    return view('GestionBibliotecaViews/biblioteca');
});

Route::get('/gestionasesoria', function () {
    return view('GestionAsesoriaViews/asesoria');
});

Route::get('/principal', function () {
    return view('principal');
});

// --- RUTA CORREGIDA PARA BÚSQUEDA DE CURSOS ---
// Antes tenías una función anónima que no enviaba datos.
// Ahora apunta al controlador que SÍ envía la variable $cursos.
Route::get('/curso', [BusquedaCursoController::class, 'index'])->name('busqueda.curso');

Route::get('/pruebas',function(Request $request){

    $correo=$request->correo;
    $res=DB::table('persona')
                ->select(columns: "correo")
                ->where("correo","=",$correo)
                ->first();

    return$res;
});