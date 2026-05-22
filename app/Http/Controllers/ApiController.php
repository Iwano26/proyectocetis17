<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\User; // Tu modelo de la tabla 'persona'
use App\Models\Evento;
use App\Models\Biblioteca;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    // API 1: Obtener los cursos activos
    public function obtenerCursos()
    {
        try {
            // Selecciona campos de tu tabla 'curso'
            $cursos = Curso::select('id_curso', 'nombre_curso', 'descripcion', 'materia', 'estado')
                ->where('estado', '=', 'Activo') // Por si manejas estados
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $cursos
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener cursos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // API 2: Obtener usuarios (personas) filtrando datos sensibles
    public function obtenerUsuarios()
    {
        try {
            // Usamos el modelo User (tabla persona) omitiendo la clave 'pass' por seguridad
            $usuarios = User::select('correo', 'nombre', 'apellidoPa', 'apellidoMa', 'rol', 'telefono', 'activo')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $usuarios
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener usuarios',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // API 3: Obtener eventos programados (asesorías, solicitudes, etc.)
    public function obtenerEventos()
    {
        try {
            // Campos de tu tabla 'evento'
            $eventos = Evento::select('id_evento', 'id_curso', 'nombre_evento', 'fecha', 'hora', 'tipo')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $eventos
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener eventos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // API 4: Registrar un documento en la biblioteca (Petición POST)
    public function subirDocumento(Request $request)
    {
        // Validamos según las columnas de tu tabla 'biblioteca'
        $validator = Validator::make($request->all(), [
            'id_curso'       => 'required|integer',
            'correo_usuario' => 'required|email|max:150',
            'nombre_doc'     => 'required|string|max:255',
            'materia'        => 'required|string|max:100',
            'ruta_archivo'   => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            // Creamos el registro usando el modelo Biblioteca
            $documento = Biblioteca::create([
                'id_curso'       => $request->id_curso,
                'correo_usuario' => $request->correo_usuario,
                'nombre_doc'     => $request->nombre_doc,
                'materia'        => $request->materia,
                'ruta_archivo'   => $request->ruta_archivo
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Documento registrado en la biblioteca con éxito',
                'data' => $documento
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al registrar en la biblioteca',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}