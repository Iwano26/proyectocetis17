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
    AsesoriaController,
    ExamenController,
    RespuestaExamenController,
    AgendaController,
    SolicitudController,
    EvidenciaController,
    GestionBibliotecaController
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
    Route::post('/gestionusuario/{correo}/toggle-status', [App\Http\Controllers\GestionUsuarioController::class, 'toggleStatus'])->name('gestionusuario.toggleStatus');

    // Ruta de recursos para el Gestor de Biblioteca
    Route::resource('gestionbiblioteca', GestionBibliotecaController::class);
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

    // RUTA PARA ELIMINAR: Borra el evento (y por cascada borra la asesoría y asistencias)
    Route::delete('/evento/{id_evento}/eliminar', [AsesoriaController::class, 'destroy'])->name('eventos.destroy');

    // RUTAS PARA EDICIÓN:
    // 1. Muestra la pantalla con los datos llenos para editar
    Route::get('/evento/{id_evento}/editar-asesoria', [AsesoriaController::class, 'edit'])->name('asesorias.edit');
    // 2. Procesa la actualización en la Base de Datos
    Route::put('/evento/{id_evento}/update', [AsesoriaController::class, 'update'])->name('asesorias.update');

    // RUTA PARA UNIRSE A LA ASESORÍA: El alumno se inscribe a la asesoría (tabla asistencia_asesoria)
    Route::post('/asesoria/{id_evento}/unirse', [AsesoriaController::class, 'unirse'])->name('asesorias.unirse');

    // RUTA PARA VER LA LISTA DE ASISTENCIA: Muestra quiénes se unieron a la asesoría
    Route::get('/evento/{id_evento}/asistencia', [AsesoriaController::class, 'listaAsistencia'])->name('asesorias.lista');

    // RUTA PARA CANCELAR ASISTENCIA: El alumno cancela su asistencia a la asesoría
    Route::delete('/asesoria/{id_evento}/cancelar', [AsesoriaController::class, 'cancelarAsistencia'])->name('asesorias.cancelar');

    // RUTA PARA AGREGAR MANUALMENTE A UN ESTUDIANTE: El asesor agrega a un estudiante a la asesoría
    Route::post('/asesoria/{id_asesoria}/agregar-manualmente', [AsesoriaController::class, 'agregarManualmente'])->name('asesorias.agregarManualmente');



    // ===== EVIDENCIAS =====
    Route::post('/asesoria/{id_asesoria}/evidencia', [EvidenciaController::class, 'store'])->name('evidencia.store');
    Route::get('/asesoria/{id_asesoria}/evidencias', [EvidenciaController::class, 'verEvidencias'])->name('evidencia.ver');
    Route::delete('/evidencia/{id_evidencia}', [EvidenciaController::class, 'destroy'])->name('evidencia.destroy');

    // RUTA PARA QUITAR MANUALMENTE A UN ESTUDIANTE: El asesor quita a un estudiante de la asesoría
    Route::delete('/asistencia/{id_asistencia}/quitar', [AsesoriaController::class, 'quitarAsistente'])->name('asesorias.quitarAsistente');

    //Examenes
    // ===== EXÁMENES =====

    // ASESOR: Crear examen
    Route::get('/curso/{id_curso}/examen/create', [ExamenController::class, 'create'])->name('examenes.create');
    Route::post('/curso/{id_curso}/examen/store', [ExamenController::class, 'store'])->name('examenes.store');

    // ASESOR: Resultados y revisión
    Route::get('/examen/{id_cuestionario}/resultados', [ExamenController::class, 'resultados'])->name('examenes.resultados');
    Route::get('/intento/{id_intento}/ver', [ExamenController::class, 'verIntento'])->name('examenes.verIntento');
    Route::post('/respuesta/{id_respuesta}/revisar', [ExamenController::class, 'revisarRespuesta'])->name('examenes.revisarRespuesta');

    // ALUMNO: Flujo del examen
    Route::get('/examen/{id_cuestionario}/inicio', [RespuestaExamenController::class, 'inicio'])->name('examen.inicio');
    Route::post('/examen/{id_cuestionario}/iniciar', [RespuestaExamenController::class, 'iniciarIntento'])->name('examen.iniciar');
    Route::get('/intento/{id_intento}/responder', [RespuestaExamenController::class, 'responder'])->name('examen.responder');
    Route::post('/intento/{id_intento}/guardar', [RespuestaExamenController::class, 'guardar'])->name('examen.guardar');
    Route::get('/examen/{id_cuestionario}/mis-resultados', [RespuestaExamenController::class, 'misResultados'])->name('examen.misResultados');
    Route::get('/intento/{id_intento}/mi-intento', [RespuestaExamenController::class, 'verMiIntento'])->name('examen.verMiIntento');

    Route::get('/examen/{id_cuestionario}/editar-config', [ExamenController::class, 'editarConfig'])->name('examenes.editarConfig');
    Route::put('/examen/{id_cuestionario}/actualizar-config', [ExamenController::class, 'actualizarConfig'])->name('examenes.actualizarConfig');



    // ===== SOLICITUDES =====
    Route::get('/solicitud/crear', [SolicitudController::class, 'create'])->name('solicitud.create');
    Route::post('/solicitud/guardar', [SolicitudController::class, 'store'])->name('solicitud.store');
    Route::get('/solicitud/bandeja', [SolicitudController::class, 'bandeja'])->name('solicitud.bandeja');
    Route::post('/solicitud/{id}/aceptar', [SolicitudController::class, 'aceptar'])->name('solicitud.aceptar');
    Route::post('/solicitud/{id}/rechazar', [SolicitudController::class, 'rechazar'])->name('solicitud.rechazar');
    Route::get('/mis-solicitudes', [SolicitudController::class, 'misSolicitudes'])->name('solicitud.mis');
    Route::get('/solicitud/horarios/{id_curso}', [SolicitudController::class, 'horariosCurso'])->name('solicitud.horarios');


    Route::get('/curso/{id}/reportes/lista-asistencia', [ReportesController::class, 'generarListaAsistencia'])->name('reportes.listaAsistencia');


    Route::get('/modificarperfil', function () { return view('GestionUsuarioViews/perfil'); });

    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda');
});