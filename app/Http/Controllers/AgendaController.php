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

        // Traemos todos los cursos en los que está inscrito el alumno
        $cursosInscritos = DB::table('inscripcion')
            ->where('correo_estudiante', $correo)
            ->pluck('id_curso');

        // ── ASESORÍAS ──────────────────────────────────────────────────────────
        // Todas las asesorías de los cursos inscritos, con flag de si ya se unió
        $asesorias = DB::table('evento')
            ->join('asesoria', 'evento.id_evento', '=', 'asesoria.id_evento')
            ->join('curso', 'evento.id_curso', '=', 'curso.id_curso')
            ->whereIn('evento.id_curso', $cursosInscritos)
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
                          AND aa.correo_persona = '{$correo}') as ya_inscrito")
            )
            ->orderBy('asesoria.fecha_asesoria')
            ->get();

        // ── CUESTIONARIOS ──────────────────────────────────────────────────────
        // Todos los exámenes de los cursos inscritos
        $examenes = DB::table('evento')
            ->join('cuestionario', 'evento.id_evento', '=', 'cuestionario.id_evento')
            ->join('configuracion_examen', 'cuestionario.id_cuestionario', '=', 'configuracion_examen.id_cuestionario')
            ->join('curso', 'evento.id_curso', '=', 'curso.id_curso')
            ->whereIn('evento.id_curso', $cursosInscritos)
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
                // Cuántos intentos ya hizo el alumno
                DB::raw("(SELECT COUNT(*) FROM intento_examen ie 
                          WHERE ie.id_cuestionario = cuestionario.id_cuestionario 
                          AND ie.correo_estudiante = '{$correo}') as intentos_hechos")
            )
            ->orderBy('configuracion_examen.fecha_examen')
            ->get();

        // ── EVENTOS PARA EL CALENDARIO ─────────────────────────────────────────
        $eventosCalendario = collect();

        foreach ($asesorias as $a) {
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
                'extendedProps' => ['tipo' => 'asesoria', 'lugar' => $a->lugar],
            ]);
        }

        foreach ($examenes as $e) {
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
                'extendedProps' => ['tipo' => 'examen'],
            ]);
        }

        return view('agenda', compact('asesorias', 'examenes', 'eventosCalendario'));
    }
}