<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Inscripcion;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportesController extends Controller
{
    /**
     * Muestra la vista previa del listado de estudiantes en la interfaz web.
     */
    public function index($id)
    {
        $curso = Curso::findOrFail($id);

        // Consultamos la tabla inscripcion para obtener los estudiantes del curso
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
        $curso = Curso::findOrFail($id);
        
        // Obtenemos los inscritos para el PDF
        $registros = Inscripcion::where('id_curso', $id)
            ->with('estudiante')
            ->get();

        // Cargamos la vista del diseño profesional
        $pdf = Pdf::loadView('CursosViews.pdf_reporte', [
            'curso' => $curso,
            'registros' => $registros
        ]);

        // Retorna el archivo para descarga inmediata
        return $pdf->download('Reporte_Asesorias_' . $curso->nombre_curso . '.pdf');
    }
}