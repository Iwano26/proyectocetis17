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
    PerfilController,
    EventoController,
    ForoController,
    ReportesController,
    AsesoriaController
};

// --- PÚBLICAS ---
Route::get('/', function () {
    return Auth::check() ? redirect('/principal') : redirect('/login');
});

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
// Nota: Brandon, aquí registramos el login.post para la autenticación
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('/register')->group(function () {
    Route::get('/', [RegisterController::class, 'create'])->name('register.create'); 
    Route::post('/post', [RegisterController::class, 'store'])->name('register.store'); 
    Route::get('/{correo}/confirmar', [RegisterController::class, 'ConfirmMail'])->name('register.confirmmail'); 
});


// --- RECUPERACIÓN DE CONTRASEÑA ---
Route::get('/olvido-contrasennia',           [ResetPasswordController::class, 'showResetForm'])->name('password.request');
Route::post('/resetpass',                 [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/cambiarpass/{token}',        [ResetPasswordController::class, 'showResetFormWithToken'])->name('password.reset');
Route::post('/actualizar-contrasennia',   [ResetPasswordController::class, 'resetPassword'])->name('password.update');

// --- VERIFICACIÓN DE CUENTA ---
Route::get('/confirmar-cuenta/{token}', [RegisterController::class, 'confirmar'])->name('correo.confirmar');


// --- PROTEGIDAS ---
Route::middleware(['auth'])->group(function () {

    Route::get('/principal', function () {
        return view('principal');
    })->name('principal');


   Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
    Route::get('/perfil/editar', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil/actualizar', [PerfilController::class, 'update'])->name('perfil.update');

    Route::resource('gestioncurso', GestionCursoController::class);

    // --- SECCIÓN PROTEGIDA PARA ADMINISTRADORES ---
    Route::group(['middleware' => function ($request, $next) {
        if (Auth::user()->rol !== 'Administrador') {
            return redirect('/principal')->with('mensaje', 'No tienes permisos de administrador.');
        }
        return $next($request);
    }], function () {
        Route::resource('gestionusuario', GestionUsuarioController::class);
    });

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
    Route::post('/cursos/guardar', [CursoController::class, 'store'])->name('cursos.store');
    Route::get('/cursos/crear', [CursoController::class, 'create'])->name('cursos.create');
    Route::get('/cursos/{id}/editar', [CursoController::class, 'edit'])->name('cursos.edit');
    Route::put('/cursos/{id}', [CursoController::class, 'update'])->name('cursos.update');
    Route::delete('/cursos/{id}', [CursoController::class, 'destroy'])->name('cursos.destroy');

    Route::get('/cursos/ver/{id}', [App\Http\Controllers\CursoController::class, 'show'])->name('cursos.show');
    Route::get('/curso/{id}/eventos', [EventoController::class, 'index'])->name('curso.eventos');

    // Rutas para los Reportes de Asesoría
    Route::get('/curso/{id}/reportes', [ReportesController::class, 'index'])->name('reportes.index');
    Route::get('/curso/{id}/reportes/pdf', [ReportesController::class, 'generarPDF'])->name('reportes.pdf');

    // Rutas del Foro Q&A
    Route::prefix('curso/{id}/foro')->group(function () {
        Route::get('/', [ForoController::class, 'index'])->name('foro.index');
        Route::get('/pregunta/{id_pregunta}', [ForoController::class, 'show'])->name('foro.show');
        Route::post('/pregunta', [ForoController::class, 'storePregunta'])->name('pregunta.store');
        Route::post('/pregunta/{id_pregunta}/respuesta', [ForoController::class, 'storeRespuesta'])->name('respuesta.store');
    });

   // Ruta para que el alumno se inscriba a un curso
    Route::post('/curso/{id}/inscribir', [CursoController::class, 'inscribir'])->name('cursos.inscribir');
    Route::delete('/curso/{id}/salir', [CursoController::class, 'salir'])->name('cursos.salir');

    //Rutas de asesorías
    // Ruta para mostrar el formulario de creación (Pasa el id del curso)
    Route::get('/curso/{id_curso}/asesoria/create', [AsesoriaController::class, 'create'])->name('asesorias.create');

    // Ruta para guardar los datos en la BD (Procesa el formulario)
    Route::post('/curso/{id_curso}/asesoria/store', [AsesoriaController::class, 'store'])->name('asesorias.store');



    Route::get('/modificarperfil', function () { return view('GestionUsuarioViews/perfil'); });
    Route::get('/agenda', function () { return view('Agenda'); });
});