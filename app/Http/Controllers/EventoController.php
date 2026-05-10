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
        // 1. Buscamos el curso con el nombre del asesor (usando Query Builder para el JOIN)
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

        // 2. Traemos todos los eventos que pertenezcan a este curso
        $eventos = Evento::where('id_curso', $id)
                         ->orderBy('fecha', 'asc')
                         ->get();

        // 3. Lógica para verificar si el estudiante ya está inscrito (Para evitar el error 500)
        $yaInscrito = false;
        if (Auth::check() && Auth::user()->rol === 'Estudiante') {
            $yaInscrito = DB::table('inscripcion')
                ->where('id_curso', $id)
                ->where('correo_estudiante', Auth::user()->correo)
                ->exists();
        }

        // 4. Retornamos la vista enviando los datos necesarios
        return view('CursosViews.actividadesCurso', compact('curso', 'eventos', 'yaInscrito'));
    }
}