<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    public function index($id)
    {
        // 1. Buscamos el curso con el nombre del asesor
        $curso = DB::table('curso')
            ->join('persona', 'curso.correo_persona', '=', 'persona.correo')
            ->select(
                'curso.*',
                DB::raw("CONCAT(persona.nombre, ' ', persona.apellidoPa, ' ', persona.apellidoMa) as nombre_asesor")
            )
            ->where('curso.id_curso', $id)
            ->first();

        if (!$curso) {
            return redirect()->route('cursos.index')->with('mensaje', 'Curso no encontrado');
        }

        // 2. Verificar inscripción del estudiante
        $yaInscrito = false;
        if (Auth::check() && Auth::user()->rol === 'Estudiante') {
            $yaInscrito = DB::table('inscripcion')
                ->where('id_curso', $id)
                ->where('correo_estudiante', Auth::user()->correo)
                ->exists();
        }

        // =============================================
        // 3. Actualizar estados de ASESORÍAS
        // =============================================

        // EN_CURSO → TERMINADA
        DB::table('asesoria')
            ->where('estado', 'EN_CURSO')
            ->whereRaw('fecha_asesoria <= CURDATE()')
            ->whereRaw('hora_fin <= CURTIME()')
            ->update(['estado' => 'TERMINADA']);

        // DISPONIBLE → TERMINADA
        DB::table('asesoria')
            ->where('estado', 'DISPONIBLE')
            ->whereRaw('fecha_asesoria <= CURDATE()')
            ->whereRaw('hora_fin <= CURTIME()')
            ->update(['estado' => 'TERMINADA']);

        // DISPONIBLE → EN_CURSO
        DB::table('asesoria')
            ->where('estado', 'DISPONIBLE')
            ->whereRaw('fecha_asesoria = CURDATE()')
            ->whereRaw('hora_inicio <= CURTIME()')
            ->whereRaw('hora_fin > CURTIME()')
            ->update(['estado' => 'EN_CURSO']);

        // =============================================
        // 4. Actualizar estados de EXÁMENES
        // =============================================

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

        // =============================================
        // 5. Query de eventos con JOIN a asesoria
        // =============================================
        $correoUsuario = Auth::check() ? Auth::user()->correo : '';

        $eventos = DB::table('evento')
        ->leftJoin('asesoria', 'evento.id_evento', '=', 'asesoria.id_evento')
        ->leftJoin('cuestionario', 'evento.id_evento', '=', 'cuestionario.id_evento')
        ->leftJoin('configuracion_examen', 'cuestionario.id_cuestionario', '=', 'configuracion_examen.id_cuestionario')
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
            'asesoria.requiere_evidencia as ases_requiere_evidencia',
            'cuestionario.id_cuestionario',
            'cuestionario.nombre_cuestionario',
            'configuracion_examen.id_config',
            'configuracion_examen.fecha_examen as ex_fecha',
            'configuracion_examen.fecha_cierre as ex_fecha_cierre',
            'configuracion_examen.hora_inicio as ex_inicio',
            'configuracion_examen.hora_fin as ex_fin',
            'configuracion_examen.oportunidades as ex_oportunidades',
            'configuracion_examen.estado as ex_estado',
            DB::raw('(SELECT COUNT(*) FROM asistencia_asesoria aa WHERE aa.id_asesoria = asesoria.id_asesoria) as total_asistentes'),
            DB::raw('(SELECT COUNT(*) FROM asistencia_asesoria aa WHERE aa.id_asesoria = asesoria.id_asesoria AND aa.correo_persona = "' . $correoUsuario . '") as ya_inscrito')
        )
        ->where('evento.id_curso', $id)
        ->orderBy('evento.id_evento', 'desc')
        ->get();

        // =============================================
        // 6. Marcar bloqueo de inscripción en asesorías
        // =============================================
        $eventos = $eventos->map(function($evento) {
            $evento->bloquear_inscripcion = false;

            if ($evento->tipo === 'asesoria' && $evento->ases_estado === 'DISPONIBLE') {
                if ($evento->ases_fecha && $evento->ases_inicio) {
                    $inicioAsesoria = \Carbon\Carbon::parse($evento->ases_fecha . ' ' . $evento->ases_inicio);
                    $ahora = \Carbon\Carbon::now();
                    $minutosRestantes = $ahora->diffInMinutes($inicioAsesoria, false);

                    if ($minutosRestantes <= 60 && $evento->total_asistentes == 0) {
                        $evento->bloquear_inscripcion = true;
                    }
                }
            }

            return $evento;
        });

        // 7. Retornamos la vista
        return view('CursosViews.actividadesCurso', compact('curso', 'eventos', 'yaInscrito'));
    }
}