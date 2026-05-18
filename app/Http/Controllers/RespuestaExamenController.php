<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Cuestionario;
use App\Models\IntentoExamen;
use App\Models\RespuestaAlumno;
use App\Models\RespuestaOpcionMultiple;

class RespuestaExamenController extends Controller
{
    // =============================================
    // ALUMNO: Pantalla previa al examen
    // =============================================
    public function inicio($id_cuestionario)
    {
        $cuestionario = Cuestionario::with('configuracion', 'preguntas')->findOrFail($id_cuestionario);
        $config = $cuestionario->configuracion;

        // Intentos que ya hizo este alumno
        $intentosHechos = IntentoExamen::where('id_cuestionario', $id_cuestionario)
            ->where('correo_estudiante', Auth::user()->correo)
            ->orderBy('numero_intento')
            ->get();

        $puedeIntentar = $intentosHechos->count() < $config->oportunidades
                      && $config->estado === 'ACTIVO';

        return view('ExamenesViews.inicioExamen', compact(
            'cuestionario', 'config', 'intentosHechos', 'puedeIntentar'
        ));
    }

    // =============================================
    // ALUMNO: Empieza un nuevo intento
    // =============================================
    public function iniciarIntento($id_cuestionario)
    {
        $cuestionario = Cuestionario::with('configuracion')->findOrFail($id_cuestionario);
        $config = $cuestionario->configuracion;

        // Verificaciones
        if ($config->estado !== 'ACTIVO') {
            return back()->with('error', 'El examen no está disponible en este momento.');
        }

        $intentosHechos = IntentoExamen::where('id_cuestionario', $id_cuestionario)
            ->where('correo_estudiante', Auth::user()->correo)
            ->count();

        if ($intentosHechos >= $config->oportunidades) {
            return back()->with('error', 'Ya usaste todas tus oportunidades.');
        }

        // Crear el intento
        $intento = IntentoExamen::create([
            'id_cuestionario'   => $id_cuestionario,
            'correo_estudiante' => Auth::user()->correo,
            'numero_intento'    => $intentosHechos + 1,
            'fecha_inicio'      => Carbon::now(),
            'completado'        => 0,
        ]);

        return redirect()->route('examen.responder', $intento->id_intento);
    }

    // =============================================
    // ALUMNO: Pantalla de responder examen
    // =============================================
    public function responder($id_intento)
    {
        $intento = IntentoExamen::findOrFail($id_intento);

        // Seguridad: solo el dueño del intento
        if ($intento->correo_estudiante !== Auth::user()->correo) {
            abort(403);
        }

        if ($intento->completado) {
            return redirect()->route('examen.misResultados', $intento->id_cuestionario)
                ->with('info', 'Ya completaste este intento.');
        }

        $cuestionario = Cuestionario::with([
            'preguntas.opciones',
            'configuracion'
        ])->findOrFail($intento->id_cuestionario);

        // Tiempo restante
        $config = $cuestionario->configuracion;
        $horaFin = Carbon::parse($config->fecha_examen . ' ' . $config->hora_fin);
        $segundosRestantes = max(0, Carbon::now()->diffInSeconds($horaFin, false));

        return view('ExamenesViews.responderExamen', compact(
            'intento', 'cuestionario', 'segundosRestantes'
        ));
    }

    // =============================================
    // ALUMNO: Guardar respuestas y calcular nota
    // =============================================
    public function guardar(Request $request, $id_intento)
    {
        $intento = IntentoExamen::findOrFail($id_intento);

        if ($intento->correo_estudiante !== Auth::user()->correo) {
            abort(403);
        }

        if ($intento->completado) {
            return redirect()->route('examen.misResultados', $intento->id_cuestionario);
        }

        $cuestionario = Cuestionario::with('preguntas.opciones')->findOrFail($intento->id_cuestionario);

        DB::beginTransaction();

        try {
            $totalPreguntas = $cuestionario->preguntas->count();
            $correctas = 0;
            $tieneAbiertas = false;

            foreach ($cuestionario->preguntas as $pregunta) {
                $respuestaInput = $request->input('pregunta_' . $pregunta->id_pregunta);

                $esCorrecta = null;
                $idOpcion = null;
                $textoRespuesta = null;

                switch ($pregunta->tipo) {

                    case 'abierta':
                        $textoRespuesta = $respuestaInput;
                        $esCorrecta = null; // Necesita revisión manual
                        $tieneAbiertas = true;
                        break;

                    case 'verdadero_falso':
                    case 'opcion_multiple':
                        $idOpcion = $respuestaInput;
                        $opcionElegida = $pregunta->opciones->firstWhere('id_opcion', $idOpcion);
                        $esCorrecta = $opcionElegida ? $opcionElegida->es_correcta : 0;
                        if ($esCorrecta) $correctas++;
                        break;

                    case 'multiple_correcta':
                        // Se guarda abajo con tabla auxiliar
                        $esCorrecta = null;
                        break;
                }

                // Insert respuesta_alumno
                $id_respuesta = DB::table('respuesta_alumno')->insertGetId([
                    'id_intento'      => $id_intento,
                    'id_pregunta'     => $pregunta->id_pregunta,
                    'id_opcion'       => $idOpcion,
                    'texto_respuesta' => $textoRespuesta,
                    'es_correcta'     => $esCorrecta,
                    'revisada'        => ($pregunta->tipo === 'abierta') ? 0 : 1,
                ]);

                // Para multiple_correcta guardamos cada opción seleccionada
                if ($pregunta->tipo === 'multiple_correcta') {
                    $opcionesSeleccionadas = $request->input('pregunta_' . $pregunta->id_pregunta, []);
                    if (!is_array($opcionesSeleccionadas)) $opcionesSeleccionadas = [];

                    $opcionesCorrectas = $pregunta->opciones->where('es_correcta', 1)->pluck('id_opcion')->toArray();
                    $todasCorrectas = !array_diff($opcionesCorrectas, $opcionesSeleccionadas)
                                   && !array_diff($opcionesSeleccionadas, $opcionesCorrectas);

                    // Actualizar es_correcta en respuesta_alumno
                    DB::table('respuesta_alumno')
                        ->where('id_respuesta', $id_respuesta)
                        ->update(['es_correcta' => $todasCorrectas ? 1 : 0]);

                    if ($todasCorrectas) $correctas++;

                    foreach ($opcionesSeleccionadas as $idOpc) {
                        DB::table('respuesta_opcion_multiple')->insert([
                            'id_respuesta' => $id_respuesta,
                            'id_opcion'    => $idOpc,
                        ]);
                    }
                }
            }

            // Calcular calificación (si hay abiertas queda pendiente de revisión)
            $preguntasAutoCalificables = $cuestionario->preguntas
                ->where('tipo', '!=', 'abierta')->count();

            $calificacion = null;
            if (!$tieneAbiertas && $totalPreguntas > 0) {
                $calificacion = round(($correctas / $totalPreguntas) * 10, 2);
            } elseif ($preguntasAutoCalificables > 0) {
                // Calificación parcial sin las abiertas
                $calificacion = round(($correctas / $totalPreguntas) * 10, 2);
            }

            // Marcar intento como completado
            DB::table('intento_examen')
                ->where('id_intento', $id_intento)
                ->update([
                    'completado'  => 1,
                    'fecha_fin'   => Carbon::now(),
                    'calificacion' => $calificacion,
                ]);

            DB::commit();

            return redirect()->route('examen.misResultados', $intento->id_cuestionario)
                ->with('success', '¡Examen enviado con éxito!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    // =============================================
    // ALUMNO: Ver sus propios resultados e intentos
    // =============================================
    public function misResultados($id_cuestionario)
    {
        $cuestionario = Cuestionario::with('configuracion')->findOrFail($id_cuestionario);

        $intentos = IntentoExamen::where('id_cuestionario', $id_cuestionario)
            ->where('correo_estudiante', Auth::user()->correo)
            ->orderBy('numero_intento')
            ->get();

        return view('ExamenesViews.misResultados', compact('cuestionario', 'intentos'));
    }

    // =============================================
    // ALUMNO: Ver detalle de sus respuestas
    // =============================================
    public function verMiIntento($id_intento)
    {
        $intento = IntentoExamen::with([
            'respuestas.pregunta.opciones',
            'respuestas.opcionesMultiples.opcion'
        ])->findOrFail($id_intento);

        if ($intento->correo_estudiante !== Auth::user()->correo) {
            abort(403);
        }

        $cuestionario = Cuestionario::findOrFail($intento->id_cuestionario);

        return view('ExamenesViews.verMiIntento', compact('intento', 'cuestionario'));
    }
}