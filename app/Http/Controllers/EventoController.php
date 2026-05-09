<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Curso;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index($id)
    {
        // 1. Buscamos el curso por su ID
        $curso = Curso::findOrFail($id);

        // 2. Traemos todos los eventos que pertenezcan a este curso
        $eventos = Evento::where('id_curso', $id)
                         ->orderBy('fecha', 'asc')
                         ->get();

        // 3. Retornamos la vista enviando los datos
        return view('CursosViews.actividadesCurso', compact('curso', 'eventos'));
    }
}