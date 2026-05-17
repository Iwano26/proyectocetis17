<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importante para la consulta de inscripción
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
    
    // Actualizar estados automáticamente al cargar la página
    $ahora = \Carbon\Carbon::now();

    // DISPONIBLE → EN_CURSO
    // Actualizar estados automáticamente al cargar la página
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


    // 3. Query correcta con JOIN a asesoria
    $correoUsuario = Auth::check() ? Auth::user()->correo : '';

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

        // Marcamos los eventos que están en la hora límite sin asistentes
        $eventos = $eventos->map(function($evento) {
        $evento->bloquear_inscripcion = false;

        if ($evento->tipo === 'asesoria' && $evento->ases_estado === 'DISPONIBLE') {
            if ($evento->ases_fecha && $evento->ases_inicio) {
                $inicioAsesoria = \Carbon\Carbon::parse($evento->ases_fecha . ' ' . $evento->ases_inicio);
                $ahora = \Carbon\Carbon::now();

                // Minutos que faltan para el inicio (negativo si ya pasó)
                $minutosRestantes = $ahora->diffInMinutes($inicioAsesoria, false);

                // Bloquear si faltan 60 minutos o menos (incluyendo 0) Y sin asistentes
                if ($minutosRestantes <= 60 && $evento->total_asistentes == 0) {
                    $evento->bloquear_inscripcion = true;
                }
            }
        }

        return $evento;
    });

    // 4. Retornamos la vista
    return view('CursosViews.actividadesCurso', compact('curso', 'eventos', 'yaInscrito'));
}
}