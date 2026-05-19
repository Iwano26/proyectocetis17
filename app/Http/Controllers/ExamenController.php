<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Cuestionario;
use App\Models\Pregunta;
use App\Models\OpcionPregunta;
use App\Models\ConfiguracionExamen;
use App\Models\IntentoExamen;
use App\Models\RespuestaAlumno;

class ExamenController extends Controller
{
    // =============================================
    // ASESOR: Muestra formulario para crear examen
    // =============================================
    public function create($id_curso)
    {
        return view('ExamenesViews.crearExamen', compact('id_curso'));
    }

    // =============================================
    // ASESOR: Guarda el examen completo
    // =============================================
    public function store(Request $request, $id_curso)
    {
        $request->validate([
            'nombre_cuestionario' => 'required|string|max:255',
            'fecha_examen'        => 'required|date',
            'hora_inicio'         => 'required',
            'hora_fin'            => 'required',
            'oportunidades'       => 'required|integer|min:1|max:5',
            'preguntas'           => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {
            // 1. Insert en evento
            $id_evento = DB::table('evento')->insertGetId([
                'id_curso'      => $id_curso,
                'nombre_evento' => $request->nombre_cuestionario,
                'fecha'         => Carbon::now()->toDateString(),
                'hora'          => Carbon::now()->toTimeString(),
                'tipo'          => 'cuestionario'
            ]);

            // 2. Insert en cuestionario
            $id_cuestionario = DB::table('cuestionario')->insertGetId([
                'id_evento'           => $id_evento,
                'nombre_cuestionario' => $request->nombre_cuestionario,
                'fecha_creacion'      => Carbon::now(),
            ]);

            // 3. Insert configuracion_examen
            DB::table('configuracion_examen')->insert([
                'id_cuestionario' => $id_cuestionario,
                'fecha_examen'    => $request->fecha_examen,
                'hora_inicio'     => $request->hora_inicio,
                'hora_fin'        => $request->hora_fin,
                'oportunidades'   => $request->oportunidades,
                'estado'          => 'PENDIENTE',
            ]);

            // 4. Insert preguntas y opciones
            foreach ($request->preguntas as $orden => $pregunta) {
                $id_pregunta = DB::table('pregunta')->insertGetId([
                    'id_cuestionario' => $id_cuestionario,
                    'texto_pregunta'  => $pregunta['texto'],
                    'tipo'            => $pregunta['tipo'],
                    'orden'           => $orden + 1,
                ]);

                // Si tiene opciones (no es abierta)
                if ($pregunta['tipo'] !== 'abierta' && isset($pregunta['opciones'])) {
                    foreach ($pregunta['opciones'] as $opcion) {
                        DB::table('opcion_pregunta')->insert([
                            'id_pregunta'  => $id_pregunta,
                            'texto_opcion' => $opcion['texto'],
                            'es_correcta'  => isset($opcion['correcta']) ? 1 : 0,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect('/curso/' . $id_curso . '/eventos')
                ->with('success', '¡Examen creado con éxito!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    // =============================================
    // ASESOR: Lista de alumnos y sus intentos
    // =============================================
    public function resultados($id_cuestionario)
    {
        $cuestionario = Cuestionario::with('configuracion', 'evento')->findOrFail($id_cuestionario);

        // Todos los intentos agrupados por alumno
        $intentos = DB::table('intento_examen')
            ->join('persona', 'intento_examen.correo_estudiante', '=', 'persona.correo')
            ->where('intento_examen.id_cuestionario', $id_cuestionario)
            ->select(
                'persona.nombre',
                'persona.apellidoPa',
                'persona.apellidoMa',
                'persona.correo',
                'intento_examen.id_intento',
                'intento_examen.numero_intento',
                'intento_examen.calificacion',
                'intento_examen.completado',
                'intento_examen.fecha_inicio'
            )
            ->orderBy('persona.apellidoPa')
            ->orderBy('intento_examen.numero_intento')
            ->get()
            ->groupBy('correo');

        return view('ExamenesViews.resultados', compact('cuestionario', 'intentos'));
    }

    // =============================================
    // ASESOR: Ver respuestas de un intento
    // =============================================
    public function verIntento($id_intento)
    {
        $intento = IntentoExamen::with([
            'respuestas.pregunta.opciones',
            'respuestas.opcionesMultiples.opcion'
        ])->findOrFail($id_intento);

        $cuestionario = Cuestionario::findOrFail($intento->id_cuestionario);

        return view('ExamenesViews.verIntento', compact('intento', 'cuestionario'));
    }

    // =============================================
    // ASESOR: Revisar pregunta abierta
    // =============================================
    public function revisarRespuesta(Request $request, $id_respuesta)
    {
        $request->validate([
            'es_correcta' => 'required|in:0,1',
        ]);

        DB::table('respuesta_alumno')
            ->where('id_respuesta', $id_respuesta)
            ->update([
                'es_correcta' => $request->es_correcta,
                'revisada'    => 1,
            ]);

        // Recalcular calificación del intento
        $respuesta = RespuestaAlumno::findOrFail($id_respuesta);
        $this->recalcularCalificacion($respuesta->id_intento);

        return back()->with('success', 'Respuesta revisada correctamente.');
    }

    // =============================================
    // PRIVADO: Recalcula la calificación del intento
    // =============================================
    private function recalcularCalificacion($id_intento)
    {
        $respuestas = DB::table('respuesta_alumno')
            ->where('id_intento', $id_intento)
            ->get();

        $total = $respuestas->count();
        if ($total === 0) return;

        $correctas = $respuestas->where('es_correcta', 1)->count();
        $calificacion = round(($correctas / $total) * 10, 2);

        DB::table('intento_examen')
            ->where('id_intento', $id_intento)
            ->update(['calificacion' => $calificacion]);
    }

    // =============================================
    // ASESOR: Actualizar estados de examenes
    // =============================================
    public function actualizarEstados()
    {
        // PENDIENTE → ACTIVO
        DB::table('configuracion_examen')
            ->where('estado', 'PENDIENTE')
            ->whereRaw('fecha_examen = CURDATE()')
            ->whereRaw('hora_inicio <= CURTIME()')
            ->whereRaw('hora_fin > CURTIME()')
            ->update(['estado' => 'ACTIVO']);

        // ACTIVO → CERRADO
        DB::table('configuracion_examen')
            ->where('estado', 'ACTIVO')
            ->where(function($q) {
                $q->whereRaw('fecha_examen < CURDATE()')
                  ->orWhere(function($q2) {
                      $q2->whereRaw('fecha_examen = CURDATE()')
                         ->whereRaw('hora_fin <= CURTIME()');
                  });
            })
            ->update(['estado' => 'CERRADO']);

        // PENDIENTE → CERRADO si ya pasó la fecha
        DB::table('configuracion_examen')
            ->where('estado', 'PENDIENTE')
            ->whereRaw('fecha_examen < CURDATE()')
            ->update(['estado' => 'CERRADO']);
    }

    public function editarConfig($id_cuestionario)
    {
        $cuestionario = Cuestionario::with('configuracion')->findOrFail($id_cuestionario);
        $config = $cuestionario->configuracion;

        if (!$config) {
            return back()->with('error', 'Este examen no tiene configuración.');
        }

        $idCurso = DB::table('evento')
            ->where('id_evento', $cuestionario->id_evento)
            ->value('id_curso');

        return view('ExamenesViews.editarConfig', compact('cuestionario', 'config', 'idCurso'));
    }

    public function actualizarConfig(Request $request, $id_cuestionario)
    {
        $request->validate([
            'fecha_examen'  => 'required|date',
            'hora_inicio'   => 'required',
            'hora_fin'      => 'required|after:hora_inicio',
            'oportunidades' => 'required|integer|min:1|max:5',
            'estado'        => 'required|in:PENDIENTE,ACTIVO,CERRADO',
        ]);

        DB::table('configuracion_examen')
            ->where('id_cuestionario', $id_cuestionario)
            ->update([
                'fecha_examen'  => $request->fecha_examen,
                'hora_inicio'   => $request->hora_inicio,
                'hora_fin'      => $request->hora_fin,
                'oportunidades' => $request->oportunidades,
                'estado'        => $request->estado,
            ]);

        // También actualizamos el nombre del evento si cambió
        if ($request->filled('nombre_cuestionario')) {
            $cuestionario = Cuestionario::findOrFail($id_cuestionario);
            DB::table('evento')
                ->where('id_evento', $cuestionario->id_evento)
                ->update(['nombre_evento' => $request->nombre_cuestionario]);
            DB::table('cuestionario')
                ->where('id_cuestionario', $id_cuestionario)
                ->update(['nombre_cuestionario' => $request->nombre_cuestionario]);
        }

        $cuestionario = Cuestionario::findOrFail($id_cuestionario);
        $idCurso = DB::table('evento')
            ->where('id_evento', $cuestionario->id_evento)
            ->value('id_curso');

        return redirect('/curso/' . $idCurso . '/eventos')
            ->with('success', '¡Configuración actualizada con éxito!');
    }
}