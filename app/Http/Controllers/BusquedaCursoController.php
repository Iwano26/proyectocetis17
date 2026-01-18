<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusquedaCursoController extends Controller
{
    public function index(Request $request)
{
    // Obtenemos los cursos. 
    // Nota: Asegúrate de que los nombres de las columnas coincidan con tu DB (id, nombre, maestro, estado, etc.)
    $cursos = DB::connection('mysql')->table('curso')->get();

    return view('BuscarCurso', compact('cursos'));
}
}