<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GestionUsuarioController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ResetPasswordController2;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\GestionCursoController; 
use App\Http\Controllers\PrincipalController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BusquedaCursoController; 
use App\Http\Controllers\BibliotecaController;
use App\Http\Controllers\CursoController;

use Illuminate\Http\Request;

// --- RUTAS DE AUTENTICACIÓN ---
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

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

Route::get('/cambiarpass', function () {    
    return view('ResetPasswordViews/cambiarcontrasennia');
});

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


Route::get('/test-user', function() {
    return [
        'ID_en_Sesion' => Auth::id(),
        'Nombre_Detectado' => Auth::user()->nombre,
        'Email_Detectado' => Auth::user()->correo,
    ];
})->middleware('auth');



// Pantalla principal
Route::get('/buscarcurso', [CursoController::class, 'index'])->name('cursos.index');
// Crear
// Crear (Vista y Proceso)
Route::get('/cursos/crear', [CursoController::class, 'create'])->name('cursos.create');
Route::post('/cursos/guardar', [CursoController::class, 'store'])->name('cursos.store');

// Ver Detalle
Route::get('/cursos/{id}', [CursoController::class, 'show'])->name('cursos.show');
// Editar (Vista y Proceso)
Route::get('/cursos/{id}/editar', [CursoController::class, 'edit'])->name('cursos.edit');
Route::put('/cursos/{id}/actualizar', [CursoController::class, 'update'])->name('cursos.update');

Route::get('/curso', [BusquedaCursoController::class, 'index'])->name('busqueda.curso');

// Eliminar
Route::delete('/cursos/{id}', [CursoController::class, 'destroy'])->name('cursos.destroy');



Route::get('/biblioteca', function () {
    return view('Biblioteca');
});

Route::get('/subirarchivo', function () {
    return view('BibliotecaViews.SubirArchivo');
});



// Todas las rutas de la biblioteca protegidas por login
Route::middleware(['auth'])->group(function () {

    // 1. Ver la lista de archivos (Vista Principal)
    Route::get('/biblioteca', [BibliotecaController::class, 'index'])->name('biblioteca.index');

    // 2. Ver el formulario de subida
    Route::get('/biblioteca/subir', [BibliotecaController::class, 'create'])->name('biblioteca.create');

    // 3. Procesar el guardado del archivo (Acción del formulario)
    Route::post('/biblioteca/guardar', [BibliotecaController::class, 'store'])->name('biblioteca.guardar');

    // Ruta para eliminar
    Route::delete('/biblioteca/eliminar/{id}', [BibliotecaController::class, 'destroy'])->name('biblioteca.eliminar');

    // Rutas para modificar (una para ver el formulario y otra para guardar el cambio)
    Route::get('/biblioteca/editar/{id}', [BibliotecaController::class, 'edit'])->name('biblioteca.edit');
    Route::put('/biblioteca/actualizar/{id}', [BibliotecaController::class, 'update'])->name('biblioteca.actualizar');

});

Route::get('/agenda', function () {
    return view('Agenda');
});

Route::get('/vercurso', function () {
    return view('CursosViews/vercurso');
});

use App\Http\Controllers\MenuController;
Route::get('/menu', [MenuController::class, 'index'])->name('menu')->middleware('auth');


Route::get('/curso', [BusquedaCursoController::class, 'index'])->name('busqueda.curso');

// El nombre 'cursos.show' es el estándar para mostrar un recurso
Route::get('/cursos/{id}', [App\Http\Controllers\CursoController::class, 'show'])->name('cursos.show');



Route::get('/pruebas',function(Request $request){

    $correo=$request->correo;
    $res=DB::table('persona')
                ->select(columns: "correo")
                ->where("correo","=",$correo)
                ->first();

    return$res;
});