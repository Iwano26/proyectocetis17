<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importante para las consultas

class CursoController extends Controller
{
    // Vista principal de búsqueda conectada a la BD
    public function index(Request $request) {
        // Obtenemos el término de búsqueda si existe
        $buscar = $request->input('buscar');

        // Consultamos la tabla 'curso'
        $cursos = DB::table('curso')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre_curso', 'LIKE', "%{$buscar}%")
                             ->orWhere('materia', 'LIKE', "%{$buscar}%");
            })
            ->get();

        // Enviamos los datos a la vista
        return view('BuscarCurso', compact('cursos'));
    }

    // El resto de tus métodos (show, edit, etc.) se mantienen...
    // Pero en show($id) y edit($id) deberías también usar DB::table('curso')->where('id_curso', $id)->first();
}