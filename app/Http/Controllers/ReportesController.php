<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Inscripcion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    /**
     * Muestra la vista previa del listado de estudiantes en la interfaz web.
     */
    public function index($id)
    {
        // ── CORRECCIÓN: JOIN igual que EventoController para obtener nombre_asesor ──
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

        $estudiantes = Inscripcion::where('id_curso', $id)
            ->with('estudiante')
            ->get();

        return view('CursosViews.reportes', compact('curso', 'estudiantes'));
    }

    /**
     * Genera el archivo PDF basado en los alumnos inscritos.
     */
    public function generarPDF($id)
    {
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

        // Estudiantes inscritos
        $estudiantes = Inscripcion::where('id_curso', $id)
            ->with('estudiante')
            ->get();

        // Total de asesorías del curso
        $totalAsesorias = DB::table('asesoria')
            ->join('evento', 'asesoria.id_evento', '=', 'evento.id_evento')
            ->where('evento.id_curso', $id)
            ->count();

        // Asesorías terminadas
        $asesoriasTerminadas = DB::table('asesoria')
            ->join('evento', 'asesoria.id_evento', '=', 'evento.id_evento')
            ->where('evento.id_curso', $id)
            ->where('asesoria.estado', 'TERMINADA')
            ->count();

        // Promedio de asistencia por asesoría
        // Promedio de asistencia
        // $promedioAsistencia
        $promedioAsistencia = DB::table('asistencia_asesoria as aa')
            ->join('asesoria as a', 'aa.id_asesoria', '=', 'a.id_asesoria')
            ->join('evento as e', 'a.id_evento', '=', 'e.id_evento')
            ->where('e.id_curso', $id)
            ->where('aa.asistio', 'ASISTIO')  // ← asistio no asisto
            ->count();

        // Lista de asesorías con su asistencia
        $asesorias = DB::table('asesoria')
            ->join('evento', 'asesoria.id_evento', '=', 'evento.id_evento')
            ->where('evento.id_curso', $id)
            ->select(
                'evento.nombre_evento',
                'asesoria.fecha_asesoria',
                'asesoria.hora_inicio',
                'asesoria.hora_fin',
                'asesoria.estado',
                'asesoria.lugar',
                'asesoria.id_asesoria'
            )
            ->orderBy('asesoria.fecha_asesoria', 'asc')
            ->get();

        // Para cada asesoría, contamos cuántos asistieron
        $asesorias = $asesorias->map(function($asesoria) {
            $asesoria->total_asistieron = DB::table('asistencia_asesoria')
                ->where('id_asesoria', $asesoria->id_asesoria)
                ->where('asistio', 'ASISTIO')  // ← asistio no asisto
                ->count();

            $asesoria->total_inscritos = DB::table('asistencia_asesoria')
                ->where('id_asesoria', $asesoria->id_asesoria)
                ->count();
            return $asesoria;
        });

        $pdf = Pdf::loadView('CursosViews.pdf_reporte', [
            'curso'               => $curso,
            'estudiantes'         => $estudiantes,
            'totalAsesorias'      => $totalAsesorias,
            'asesoriasTerminadas' => $asesoriasTerminadas,
            'promedioAsistencia'  => $promedioAsistencia,
            'asesorias'           => $asesorias,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Reporte_' . $curso->nombre_curso . '.pdf');
    }

    public function generarListaAsistencia($id)
    {
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

        // Traemos todas las asesorías del curso con sus asistentes
        $asesorias = DB::table('asesoria as a')
            ->join('evento as e', 'a.id_evento', '=', 'e.id_evento')
            ->where('e.id_curso', $id)
            ->select(
                'a.id_asesoria',
                'e.nombre_evento',
                'a.fecha_asesoria',
                'a.hora_inicio',
                'a.hora_fin',
                'a.lugar',
                'a.estado'
            )
            ->orderBy('a.fecha_asesoria', 'asc')
            ->get();

        // Para cada asesoría traemos los alumnos que ASISTIO con su nombre completo
        $asesorias = $asesorias->map(function($asesoria) {
            $asesoria->asistentes = DB::table('asistencia_asesoria as aa')
                ->join('persona as p', 'aa.correo_persona', '=', 'p.correo')
                ->where('aa.id_asesoria', $asesoria->id_asesoria)
                ->where('aa.asistio', 'ASISTIO')
                ->select(
                    DB::raw("CONCAT(p.nombre, ' ', p.apellidoPa, ' ', p.apellidoMa) as nombre_completo"),
                    'p.correo'
                )
                ->get();
            return $asesoria;
        });

        $pdf = Pdf::loadView('CursosViews.pdf_lista_asistencia', [
            'curso'    => $curso,
            'asesorias' => $asesorias,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Lista_Asistencia_' . $curso->nombre_curso . '.pdf');
    }
}