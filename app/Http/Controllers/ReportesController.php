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
        // ── CORRECCIÓN: mismo JOIN para que el PDF también muestre el nombre ──
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

        $registros = Inscripcion::where('id_curso', $id)
            ->with('estudiante')
            ->get();

        $pdf = Pdf::loadView('CursosViews.pdf_reporte', [
            'curso'    => $curso,
            'registros' => $registros
        ]);

        return $pdf->download('Reporte_Asesorias_' . $curso->nombre_curso . '.pdf');
    }
}