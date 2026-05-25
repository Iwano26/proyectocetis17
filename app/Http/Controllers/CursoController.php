<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Curso;
use App\Models\Horario;
use App\Models\Inscripcion;
use App\Models\Evento;

class CursoController extends Controller
{
    protected $tableName = 'curso';
    protected $primaryKey = 'id_curso';
     protected $indexRoute = 'cursos.index';

    /**
     * Muestra la lista de cursos con filtros de búsqueda.
     */
    public function index(Request $request)
    {
        try {
            $buscar         = $request->input('buscar');
            $materia        = $request->input('materia');
            $dia            = $request->input('dia');
            $bloque_horario = $request->input('bloque_horario'); // NUEVO PARAMETRO
            
            $rol    = Auth::user()->rol;
            $correo = Auth::user()->correo;

            // NUEVO: Definimos el filtro de horario de forma reutilizable para no repetir código
            // Cambia la función anónima al inicio de tu index por esta:
            $filtroHorario = function($q) use ($bloque_horario) {
                return $q->whereHas('horarios', function($h) use ($bloque_horario) {
                    if ($bloque_horario === 'matutino') {
                        // Cursos que inicien antes de las 12:00 PM
                        $h->where('hora_inicio', '<', '12:00:00');
                    } elseif ($bloque_horario === 'vespertino') {
                        // Cursos que inicien entre las 12:00 PM y las 8:00 PM
                        $h->where('hora_inicio', '>=', '12:00:00')
                        ->where('hora_inicio', '<=', '20:00:00');
                    }
                });
            };

            // ── MIS CURSOS ────────────────────────────────────────────────────────
            if ($rol === 'Estudiante') {
                // Cursos donde está inscrito
                $misIds = \App\Models\Inscripcion::where('correo_estudiante', $correo)
                            ->pluck('id_curso')->toArray();

                $misCursos = Curso::query()->with('horarios')
                    ->whereIn('id_curso', $misIds)
                    ->whereIn('estado', ['ACTIVO', 'COMPLETADO'])
                    ->when($buscar, fn($q) => $q->where(function($q) use ($buscar) {
                        $q->where('nombre_curso', 'LIKE', "%{$buscar}%")
                        ->orWhere('materia', 'LIKE', "%{$buscar}%");
                    }))
                    ->when($materia, fn($q) => $q->where('materia', 'LIKE', "%{$materia}%"))
                    ->when($dia, fn($q) => $q->whereHas('horarios', fn($h) => $h->where('dia_semana', $dia)))
                    ->when($bloque_horario, $filtroHorario) // <- APLICADO
                    ->get();

                // Otros cursos ACTIVOS donde NO está inscrito
                $otrosCursos = Curso::query()->with('horarios')
                    ->whereNotIn('id_curso', $misIds)
                    ->where('estado', 'ACTIVO')
                    ->when($buscar, fn($q) => $q->where(function($q) use ($buscar) {
                        $q->where('nombre_curso', 'LIKE', "%{$buscar}%")
                        ->orWhere('materia', 'LIKE', "%{$buscar}%");
                    }))
                    ->when($materia, fn($q) => $q->where('materia', 'LIKE', "%{$materia}%"))
                    ->when($dia, fn($q) => $q->whereHas('horarios', fn($h) => $h->where('dia_semana', $dia)))
                    ->when($bloque_horario, $filtroHorario) // <- APLICADO
                    ->get();

            } elseif ($rol === 'Asesor') {
                // Mis cursos: los que yo creé (todos los estados)
                $misCursos = Curso::query()->with('horarios')
                    ->where('correo_persona', $correo)
                    ->when($buscar, fn($q) => $q->where(function($q) use ($buscar) {
                        $q->where('nombre_curso', 'LIKE', "%{$buscar}%")
                        ->orWhere('materia', 'LIKE', "%{$buscar}%");
                    }))
                    ->when($materia, fn($q) => $q->where('materia', 'LIKE', "%{$materia}%"))
                    ->when($dia, fn($q) => $q->whereHas('horarios', fn($h) => $h->where('dia_semana', $dia)))
                    ->when($bloque_horario, $filtroHorario) // <- APLICADO
                    ->get();

                // Otros cursos: ACTIVO y COMPLETADO de otros asesores
                $otrosCursos = Curso::query()->with('horarios')
                    ->where('correo_persona', '!=', $correo)
                    ->whereIn('estado', ['ACTIVO', 'COMPLETADO'])
                    ->when($buscar, fn($q) => $q->where(function($q) use ($buscar) {
                        $q->where('nombre_curso', 'LIKE', "%{$buscar}%")
                        ->orWhere('materia', 'LIKE', "%{$buscar}%");
                    }))
                    ->when($materia, fn($q) => $q->where('materia', 'LIKE', "%{$materia}%"))
                    ->when($dia, fn($q) => $q->whereHas('horarios', fn($h) => $h->where('dia_semana', $dia)))
                    ->when($bloque_horario, $filtroHorario) // <- APLICADO
                    ->get();

            } else {
                // Administrador ve todo
                $misCursos = collect();
                $otrosCursos = Curso::query()->with('horarios')
                    ->when($buscar, fn($q) => $q->where(function($q) use ($buscar) {
                        $q->where('nombre_curso', 'LIKE', "%{$buscar}%")
                        ->orWhere('materia', 'LIKE', "%{$buscar}%");
                    }))
                    ->when($materia, fn($q) => $q->where('materia', 'LIKE', "%{$materia}%"))
                    ->when($dia, fn($q) => $q->whereHas('horarios', fn($h) => $h->where('dia_semana', $dia)))
                    ->when($bloque_horario, $filtroHorario) // <- APLICADO
                    ->get();
            }

            // Inscripciones del estudiante para saber botones
            $misInscripciones = [];
            if ($rol === 'Estudiante') {
                $misInscripciones = \App\Models\Inscripcion::where('correo_estudiante', $correo)
                    ->pluck('id_curso')->toArray();
            }

            return view('BuscarCurso', compact('misCursos', 'otrosCursos', 'misInscripciones'));

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error en index de cursos: ' . $e->getMessage());
            return view('BuscarCurso', [
                'misCursos'        => collect(),
                'otrosCursos'      => collect(),
                'misInscripciones' => []
            ])->with('mensaje', 'Error al cargar cursos.');
        }
    }

    public function create() {
        return view('CursosViews/crear');
    }

    /**
     * Guarda un nuevo curso y sus múltiples horarios.
     */
    public function store(Request $request)
    {
        // 1. Quita el dd($request->all()); para que pueda seguir el proceso

        try {
            DB::beginTransaction();

            $id_nuevo_curso = DB::table($this->tableName)->insertGetId([
                'correo_persona'    => Auth::user()->correo, // <--- FALTABA ESTE
                'nombre_curso'      => $request->nombre_curso,
                'descripcion'       => $request->descripcion,
                'fecha_inicio'      => $request->fecha_inicio,
                'fecha_fin'         => $request->fecha_fin,
                'materia'           => $request->materia,
                'horas_disponibles' => $request->horas_disponibles,
                'estado'            => $request->estado,
                'acceso'            => $request->acceso ?? '', // Si es null, manda cadena vacía
            ]);

            // 2. Insertar horarios (asegúrate que el nombre de la tabla sea curso_horarios)
            if ($request->has('dia')) {
                foreach ($request->dia as $key => $valorDia) {
                    DB::table('curso_horarios')->insert([
                        'id_curso'    => $id_nuevo_curso,
                        'dia_semana'  => $valorDia,
                        'hora_inicio' => $request->hora_inicio[$key],
                        'hora_fin'    => $request->hora_fin[$key],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route($this->indexRoute)->with('mensaje', 'Curso guardado con éxito');

        } catch (\Exception $e) {
            DB::rollBack();
            // Esto te dirá el error exacto en pantalla si algo falla
            return "Error al guardar: " . $e->getMessage(); 
        }
    }

    /**
     * Mostrar formulario de edición con horarios reales.
     */
    public function edit($id) {
        // 1. Buscamos el curso por id_curso
        $curso = DB::table($this->tableName)->where($this->primaryKey, $id)->first();

        if (!$curso) {
            return redirect()->route('cursos.index')->with('mensaje', 'Curso no encontrado');
        }

        // --- BLOQUE DE SEGURIDAD PARA ASESORES ---
        // Si el usuario NO es Administrador Y el correo del curso NO coincide con su correo
        if (auth()->user()->rol !== 'Administrador' && $curso->correo_persona !== auth()->user()->correo) {
            return redirect()->route('cursos.index')
                ->with('error', 'No tienes permiso para editar este curso porque no eres el creador.');
        }
        // -----------------------------------------

        // 2. Obtenemos los horarios de la tabla correcta
        $curso->horarios = DB::table('curso_horarios')->where('id_curso', $id)->get();

        return view('CursosViews/editar', compact('curso'));
    }

    /**
     * Actualiza curso y reemplaza horarios.
     */
    public function update(Request $request, $id)
    {
        try {
            // 1. PRIMERO validamos la propiedad del curso antes de cualquier otra cosa
            $curso = DB::table($this->tableName)->where('id_curso', $id)->first();

            if (!$curso) {
                return redirect()->route('cursos.index')->with('mensaje', 'Curso no encontrado');
            }

            // BLOQUE DE SEGURIDAD: Solo el dueño o el Admin pueden actualizar
            if (auth()->user()->rol !== 'Administrador' && $curso->correo_persona !== auth()->user()->correo) {
                return redirect()->route('cursos.index')
                    ->with('error', 'No tienes permiso para actualizar este curso.');
            }

            DB::beginTransaction();

            // 2. Actualizar el curso
            DB::table($this->tableName)
                ->where('id_curso', $id)
                ->update([
                    'nombre_curso'      => $request->nombre_curso,
                    'descripcion'       => $request->descripcion,
                    'materia'           => $request->materia,
                    'estado'            => $request->estado,
                    'acceso'            => $request->password_curso ?? '',
                    'fecha_inicio'      => $request->fecha_inicio,
                    'fecha_fin'         => $request->fecha_fin,
                ]);

            // 3. Gestionar Horarios (Borrar y Reinsertar)
            DB::table('curso_horarios')->where('id_curso', $id)->delete();

            if ($request->has('dia')) {
                foreach ($request->dia as $key => $valorDia) {
                    DB::table('curso_horarios')->insert([
                        'id_curso'    => $id,
                        'dia_semana'  => $valorDia,
                        'hora_inicio' => $request->hora_inicio[$key],
                        'hora_fin'    => $request->hora_fin[$key],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('cursos.index')->with('mensaje', 'Curso actualizado con éxito');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en update de curso: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // 1. Buscamos el curso y lo unimos con la tabla 'persona'
        $curso = DB::table($this->tableName)
            ->join('persona', 'curso.correo_persona', '=', 'persona.correo') 
            ->select(
                'curso.*', 
                DB::raw("CONCAT(persona.nombre, ' ', persona.apellidoPa, ' ', persona.apellidoMa) as nombre_asesor"),
                'persona.rol as rol_persona'
            )
            ->where('curso.id_curso', $id)
            ->first();

        if (!$curso) {
            return redirect()->route($this->indexRoute)->with('mensaje', 'Curso no encontrado');
        }

        // 2. Traemos los horarios
        $curso->horarios = DB::table('curso_horarios')->where('id_curso', $id)->get();

        // --- NUEVA LÓGICA DE VERIFICACIÓN DE INSCRIPCIÓN ---
        $yaInscrito = false;
        
        // Verificamos si el usuario está logueado y es estudiante
        if (auth()->check() && auth()->user()->rol === 'Estudiante') {
            $yaInscrito = DB::table('inscripcion')
                ->where('id_curso', $id)
                ->where('correo_estudiante', auth()->user()->correo)
                ->exists(); // Devuelve true si encuentra el registro
        }
        // --------------------------------------------------

        // --- NUEVA CONSULTA DE EVENTOS CON ASISTENCIAS Y ESTADOS ---
        // Traemos todos los eventos amarrados a este curso y calculamos los datos en caliente
       $correoUsuario = auth()->check() ? auth()->user()->correo : '';

    $eventos = DB::table('evento')
        ->leftJoin('asesoria', 'evento.id_evento', '=', 'asesoria.id_evento')
        ->select(
            'evento.id_evento',
            'evento.id_curso',
            'evento.nombre_evento',
            'evento.fecha',
            'evento.hora',
            'evento.tipo',
            'asesoria.id_asesoria',
            'asesoria.lugar as ases_lugar',
            'asesoria.fecha_asesoria as ases_fecha',
            'asesoria.hora_inicio as ases_inicio',
            'asesoria.hora_fin as ases_fin',
            'asesoria.estado as ases_estado',
            DB::raw('(SELECT COUNT(*) FROM asistencia_asesoria aa WHERE aa.id_asesoria = asesoria.id_asesoria) as total_asistentes'),
            DB::raw('(SELECT COUNT(*) FROM asistencia_asesoria aa WHERE aa.id_asesoria = asesoria.id_asesoria AND aa.correo_persona = "' . $correoUsuario . '") as ya_inscrito')
        )
        ->where('evento.id_curso', $id)
        ->orderBy('evento.id_evento', 'desc')
        ->get();
        // ------------------------------------------------------------

        // 3. Retornamos la vista incluyendo ahora los 'eventos' en el compact
        return view('CursosViews/vercurso', compact('curso', 'yaInscrito', 'eventos'));
    }

    public function destroy($id) {
        // 1. Buscamos el curso para verificar quién es el dueño
        $curso = DB::table($this->tableName)->where($this->primaryKey, $id)->first();

        if (!$curso) {
            return redirect()->route('cursos.index')->with('mensaje', 'Curso no encontrado.');
        }

        // 2. BLOQUE DE SEGURIDAD (El candado)
        if (auth()->user()->rol !== 'Administrador' && $curso->correo_persona !== auth()->user()->correo) {
            return redirect()->route('cursos.index')
                ->with('error', 'No tienes permiso para eliminar este curso.');
        }

        try {
            DB::beginTransaction();

            // 3. AGREGAR AQUÍ: Borrar horarios asociados primero
            DB::table('curso_horarios')->where('id_curso', $id)->delete();

            // 4. Borrar el curso
            DB::table($this->tableName)->where($this->primaryKey, $id)->delete();

            DB::commit();
            return redirect()->route('cursos.index')->with('mensaje', 'Curso y sus horarios eliminados correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cursos.index')->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function inscribir(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Buscar el curso
        $curso = DB::table('curso')->where('id_curso', $id)->first();

        if (!$curso) {
            return back()->with('error', 'Curso no encontrado.');
        }

        // Verificar clave si el curso la tiene
        if (!empty($curso->acceso)) {
            $claveIngresada = $request->input('clave_acceso');
            if ($claveIngresada !== $curso->acceso) {
                return back()->with('error_clave_' . $id, 'Clave incorrecta. Intenta de nuevo.');
            }
        }

        // Inscribir
        Inscripcion::create([
            'id_curso'          => $id,
            'correo_estudiante' => Auth::user()->correo,
            'fecha_inscripcion' => now()->format('Y-m-d'),
        ]);

        return back()->with('success', '¡Te has unido al curso exitosamente!');
    }

    public function salir($id)
    {
        // Buscamos la inscripción del usuario logueado en este curso
        $inscripcion = \App\Models\Inscripcion::where('id_curso', $id)
                        ->where('correo_estudiante', Auth::user()->correo)
                        ->first();

        if ($inscripcion) {
            $inscripcion->delete();
            return redirect()->route('cursos.index')->with('success', 'Te has salido del curso correctamente.');
        }

        return back()->with('error', 'No se encontró tu inscripción.');
    }
}