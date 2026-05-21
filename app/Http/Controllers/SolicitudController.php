<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SolicitudController extends Controller
{
    // =============================================
    // ALUMNO: Muestra formulario de solicitud
    // =============================================
    public function create()
    {
        $correo = Auth::user()->correo;

        // Cursos donde está inscrito el alumno
        $cursosInscritos = DB::table('inscripcion')
            ->join('curso', 'inscripcion.id_curso', '=', 'curso.id_curso')
            ->where('inscripcion.correo_estudiante', $correo)
            ->where('curso.estado', 'ACTIVO')
            ->select(
                'curso.id_curso',
                'curso.nombre_curso',
                'curso.materia'
            )
            ->get();

        return view('SolicitudesViews.crearSolicitud', compact('cursosInscritos'));
    }

    // =============================================
    // ALUMNO: Obtener horarios de un curso (AJAX)
    // =============================================
    public function horariosCurso($id_curso)
    {
        $horarios = DB::table('curso_horarios')
            ->where('id_curso', $id_curso)
            ->select('id_horario', 'dia_semana', 'hora_inicio', 'hora_fin')
            ->get();

        return response()->json($horarios);
    }

    // =============================================
    // ALUMNO: Guardar solicitud
    // =============================================
    public function store(Request $request)
    {
        $request->validate([
            'id_curso'    => 'required|integer',
            'motivo'      => 'required|string|max:500',
            'id_horario'  => 'required|integer',
        ], [
            'id_curso.required'   => 'Selecciona un curso.',
            'motivo.required'     => 'Describe brevemente tu solicitud.',
            'motivo.max'          => 'Máximo 500 caracteres.',
            'id_horario.required' => 'Selecciona un horario sugerido.',
        ]);

        // Traemos el horario seleccionado para guardar día y hora
        $horario = DB::table('curso_horarios')
            ->where('id_horario', $request->id_horario)
            ->first();

        if (!$horario) {
            return back()->withInput()->with('error', 'Horario no válido.');
        }

        DB::table('solicitud')->insert([
            'id_curso'        => $request->id_curso,
            'id_evento'       => null,
            'correo_alumno'   => Auth::user()->correo,
            'motivo'          => $request->motivo,
            'dia_sugerido'    => $horario->dia_semana,
            'hora_sugerida'   => $horario->hora_inicio,
            'estado'          => 'PENDIENTE',
            'fecha_solicitud' => now(),
        ]);

        return redirect()->route('agenda')->with('success', '¡Solicitud enviada! El asesor la revisará pronto.');
    }

    // =============================================
    // ASESOR: Bandeja de entrada
    // =============================================
    public function bandeja()
    {
        $correo = Auth::user()->correo;

        // Cursos que imparte este asesor
        $misCursos = DB::table('curso')
            ->where('correo_persona', $correo)
            ->where('estado', 'ACTIVO')
            ->select('id_curso', 'nombre_curso', 'materia')
            ->get();

        $idsMisCursos = $misCursos->pluck('id_curso')->toArray();

        // Solicitudes de sus cursos agrupadas
        $solicitudes = DB::table('solicitud')
            ->join('persona', 'solicitud.correo_alumno', '=', 'persona.correo')
            ->join('curso', 'solicitud.id_curso', '=', 'curso.id_curso')
            ->whereIn('solicitud.id_curso', $idsMisCursos)
            ->select(
                'solicitud.id_solicitud',
                'solicitud.id_curso',
                'solicitud.correo_alumno',
                'solicitud.motivo',
                'solicitud.dia_sugerido',
                'solicitud.hora_sugerida',
                'solicitud.estado',
                'solicitud.fecha_solicitud',
                'persona.nombre',
                'persona.apellidoPa',
                'persona.apellidoMa',
                'curso.nombre_curso'
            )
            ->orderBy('solicitud.fecha_solicitud', 'desc')
            ->get();

        // Contadores
        $totalPendientes = $solicitudes->where('estado', 'PENDIENTE')->count();

        return view('SolicitudesViews.bandeja', compact('misCursos', 'solicitudes', 'totalPendientes'));
    }

    // =============================================
    // ASESOR: Aceptar solicitud
    // =============================================
    public function aceptar($id_solicitud)
    {
        DB::table('solicitud')
            ->where('id_solicitud', $id_solicitud)
            ->update([
                'estado' => 'ACEPTADA',
            ]);

        return back()->with('success', 'Solicitud aceptada.');
    }

    // =============================================
    // ASESOR: Rechazar solicitud
    // =============================================
    public function rechazar($id_solicitud)
    {
        DB::table('solicitud')
            ->where('id_solicitud', $id_solicitud)
            ->update([
                'estado' => 'RECHAZADA',
            ]);

        return back()->with('success', 'Solicitud rechazada.');
    }

    // =============================================
    // ALUMNO: Ver mis solicitudes enviadas
    // =============================================
    public function misSolicitudes()
    {
        $solicitudes = DB::table('solicitud')
            ->join('curso', 'solicitud.id_curso', '=', 'curso.id_curso')
            ->where('solicitud.correo_alumno', Auth::user()->correo)
            ->select(
                'solicitud.*',
                'curso.nombre_curso',
                'curso.materia'
            )
            ->orderBy('solicitud.fecha_solicitud', 'desc')
            ->get();

        return view('SolicitudesViews.misSolicitudes', compact('solicitudes'));
    }
}