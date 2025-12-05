<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusquedaCursoController extends Controller
{
    public function index(Request $request)
    {
        // Obtener los cursos de la base de datos
        // Puedes agregar ->where('estado', 'ACTIVO') si solo quieres mostrar los activos
        $cursos = DB::connection('mysql')->table('curso')->get();

        // Retornar la vista 'BusquedaCurso' con los datos
        return view('BusquedaCurso', ['cursos' => $cursos]);
    }
}