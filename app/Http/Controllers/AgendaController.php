<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgendaController extends Controller
{
    public function index()
    {
        $correo = Auth::user()->correo;
        $rol    = Auth::user()->rol;

        // ── OBTENER CURSOS SEGÚN ROL ──────────────────────────────────────────
        if ($rol === 'Estudiante') {
            $idCursos = DB::table('inscripcion')
                ->where('correo_estudiante', $correo)
                ->pluck('id_curso');
        } else {
            // Asesor y Administrador ven los cursos que imparten
            $idCursos = DB::table('curso')
                ->where('correo_persona', $correo)
                ->pluck('id_curso');
        }

        // ── ACTUALIZAR ESTADOS (igual que EventoController) ──────────────────
        DB::table('asesoria')
            ->where('estado', 'EN_CURSO')
            ->whereRaw('fecha_asesoria <= CURDATE()')
            ->whereRaw('hora_fin <= CURTIME()')
            ->update(['estado' => 'TERMINADA']);

        DB::table('asesoria')
            ->where('estado', 'DISPONIBLE')
            ->whereRaw('fecha_asesoria <= CURDATE()')
            ->whereRaw('hora_fin <= CURTIME()')
            ->update(['estado' => 'TERMINADA']);

        DB::table('asesoria')
            ->where('estado', 'DISPONIBLE')
            ->whereRaw('fecha_asesoria = CURDATE()')
            ->whereRaw('hora_inicio <= CURTIME()')
            ->whereRaw('hora_fin > CURTIME()')
            ->update(['estado' => 'EN_CURSO']);

        DB::table('configuracion_examen')
            ->where('estado', 'PENDIENTE')
            ->whereRaw('fecha_examen = CURDATE()')
            ->whereRaw('hora_inicio <= CURTIME()')
            ->whereRaw('hora_fin > CURTIME()')
            ->update(['estado' => 'ACTIVO']);

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

        DB::table('configuracion_examen')
            ->where('estado', 'PENDIENTE')
            ->whereRaw('fecha_examen < CURDATE()')
            ->update(['estado' => 'CERRADO']);

        // ── ASESORÍAS ─────────────────────────────────────────────────────────
        $asesorias = DB::table('evento')
            ->join('asesoria', 'evento.id_evento', '=', 'asesoria.id_evento')
            ->join('curso', 'evento.id_curso', '=', 'curso.id_curso')
            ->whereIn('evento.id_curso', $idCursos)
            ->whereNotIn('asesoria.estado', ['CANCELADA'])
            ->select(
                'evento.id_evento',
                'evento.id_curso',
                'evento.nombre_evento',
                'evento.fecha',
                'asesoria.id_asesoria',
                'asesoria.lugar',
                'asesoria.fecha_asesoria',
                'asesoria.hora_inicio',
                'asesoria.hora_fin',
                'asesoria.estado',
                'curso.nombre_curso',
                DB::raw("'asesoria' as tipo"),
                DB::raw("(SELECT COUNT(*) FROM asistencia_asesoria aa 
                          WHERE aa.id_asesoria = asesoria.id_asesoria 
                          AND aa.correo_persona = '{$correo}') as ya_inscrito"),
                DB::raw("(SELECT COUNT(*) FROM asistencia_asesoria aa 
                          WHERE aa.id_asesoria = asesoria.id_asesoria) as total_asistentes")
            )
            ->orderBy('asesoria.fecha_asesoria')
            ->get();

        // ── EXÁMENES ──────────────────────────────────────────────────────────
        $examenes = DB::table('evento')
            ->join('cuestionario', 'evento.id_evento', '=', 'cuestionario.id_evento')
            ->join('configuracion_examen', 'cuestionario.id_cuestionario', '=', 'configuracion_examen.id_cuestionario')
            ->join('curso', 'evento.id_curso', '=', 'curso.id_curso')
            ->whereIn('evento.id_curso', $idCursos)
            ->select(
                'evento.id_evento',
                'evento.id_curso',
                'evento.nombre_evento',
                'evento.fecha',
                'cuestionario.id_cuestionario',
                'cuestionario.nombre_cuestionario',
                'configuracion_examen.fecha_examen',
                'configuracion_examen.hora_inicio',
                'configuracion_examen.hora_fin',
                'configuracion_examen.estado',
                'configuracion_examen.oportunidades',
                'curso.nombre_curso',
                DB::raw("'cuestionario' as tipo"),
                DB::raw("(SELECT COUNT(*) FROM intento_examen ie 
                          WHERE ie.id_cuestionario = cuestionario.id_cuestionario 
                          AND ie.correo_estudiante = '{$correo}') as intentos_hechos"),
                DB::raw("(SELECT COUNT(DISTINCT ie.correo_estudiante) FROM intento_examen ie 
                          WHERE ie.id_cuestionario = cuestionario.id_cuestionario 
                          AND ie.completado = 1) as alumnos_completaron")
            )
            ->orderBy('configuracion_examen.fecha_examen')
            ->get();

        // ── EVENTOS PARA EL CALENDARIO ────────────────────────────────────────
        $eventosCalendario = collect();

        foreach ($asesorias as $a) {
            if (!$a->fecha_asesoria) continue;
            $eventosCalendario->push([
                'title' => $a->nombre_evento,
                'start' => $a->fecha_asesoria . 'T' . ($a->hora_inicio ?? '00:00:00'),
                'end'   => $a->fecha_asesoria . 'T' . ($a->hora_fin ?? '23:59:00'),
                'color' => match($a->estado) {
                    'DISPONIBLE' => '#dc3545',
                    'EN_CURSO'   => '#ffc107',
                    'TERMINADA'  => '#6c757d',
                    default      => '#dc3545',
                },
                'extendedProps' => [
                    'tipo'              => 'asesoria',
                    'lugar'             => $a->lugar ?? 'No especificado',
                    'fecha'             => $a->fecha_asesoria,
                    'hora_inicio'       => $a->hora_inicio,
                    'hora_fin'          => $a->hora_fin,
                    'estado'            => $a->estado,
                    'curso'             => $a->nombre_curso,
                    'total_asistentes'  => $a->total_asistentes,
                    'ya_inscrito'       => $a->ya_inscrito,
                ],
            ]);
        }

        foreach ($examenes as $e) {
            if (!$e->fecha_examen) continue;
            $eventosCalendario->push([
                'title' => $e->nombre_evento,
                'start' => $e->fecha_examen . 'T' . ($e->hora_inicio ?? '00:00:00'),
                'end'   => $e->fecha_examen . 'T' . ($e->hora_fin ?? '23:59:00'),
                'color' => match($e->estado) {
                    'ACTIVO'    => '#198754',
                    'PENDIENTE' => '#0d6efd',
                    'CERRADO'   => '#6c757d',
                    default     => '#0d6efd',
                },
                'extendedProps' => [
                    'tipo'               => 'examen',
                    'fecha'              => $e->fecha_examen,
                    'hora_inicio'        => $e->hora_inicio,
                    'hora_fin'           => $e->hora_fin,
                    'estado'             => $e->estado,
                    'curso'              => $e->nombre_curso,
                    'oportunidades'      => $e->oportunidades,
                    'intentos_hechos'    => $e->intentos_hechos,
                    'alumnos_completaron'=> $e->alumnos_completaron,
                ],
            ]);
        }

        return view('agenda', compact('asesorias', 'examenes', 'eventosCalendario', 'rol'));
    }
}