<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GestionCursoController extends Controller
{
    protected $tableName = 'curso';
    protected $primaryKey = 'id_curso';
    protected $indexRoute = 'gestioncurso.index';
    protected $viewPath = 'GestionCursoViews/curso';

    public function index()
    {
        try {
            $cursos = DB::connection('mysql')->table($this->tableName)->get();
            return view($this->viewPath, ['cursos' => $cursos]);
        } catch (\Exception $e) {
            Log::error('Error al cargar la gestión de cursos: ' . $e->getMessage());
            return view($this->viewPath, ['cursos' => collect()])
                        ->with('mensaje', 'Error al conectar con la base de datos: ' . $e->getMessage())
                        ->with('sessionInsertado', 'false');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'correo_persona'    => 'required|email|max:150',
            'nombre_curso'      => 'required|string|max:255',
            'fecha_inicio'      => 'required|date',
            'materia'           => 'required|string|max:100',
            'fecha_fin'         => 'required|date|after_or_equal:fecha_inicio',
            'horas_disponibles' => 'required|integer|min:1',
            'estado'            => 'required|string|max:50',
        ]);
        
        try {
            DB::connection('mysql')
                ->table($this->tableName)
                ->insert([
                    'correo_persona'    => $request->correo_persona,
                    'nombre_curso'      => $request->nombre_curso,
                    'fecha_inicio'      => $request->fecha_inicio,
                    'materia'           => $request->materia,
                    'fecha_fin'         => $request->fecha_fin,
                    'horas_disponibles' => $request->horas_disponibles,
                    'estado'            => $request->estado,
                ]);

            return redirect()->route($this->indexRoute)
                ->with('sessionInsertado', 'true')
                ->with('mensaje', "Curso '{$request->nombre_curso}' registrado exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error al registrar curso: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('sessionInsertado', 'false')
                ->with('mensaje', "Error al registrar: " . $e->getMessage());
        }
    }
    
    public function update(Request $request, int $id_curso)
    {
        $request->validate([
            'correo_persona'    => 'required|email|max:150',
            'nombre_curso'      => 'required|string|max:255',
            'fecha_inicio'      => 'required|date',
            'materia'           => 'required|string|max:100',
            'fecha_fin'         => 'required|date|after_or_equal:fecha_inicio',
            'horas_disponibles' => 'required|integer|min:1',
            'estado'            => 'required|string|max:50',
        ]);

        try {
            DB::connection('mysql')
                ->table($this->tableName)
                ->where($this->primaryKey, $id_curso)
                ->update([
                    'correo_persona'    => $request->correo_persona,
                    'nombre_curso'      => $request->nombre_curso,
                    'fecha_inicio'      => $request->fecha_inicio,
                    'materia'           => $request->materia,
                    'fecha_fin'         => $request->fecha_fin,
                    'horas_disponibles' => $request->horas_disponibles,
                    'estado'            => $request->estado,
                ]);

            return redirect()->route($this->indexRoute)
                ->with('sessionInsertado', 'true')
                ->with('mensaje', "Curso ID {$id_curso} actualizado correctamente.");

        } catch (\Exception $e) {
            Log::error('Error al actualizar curso: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('sessionInsertado', 'false')
                ->with('mensaje', "Error al actualizar: " . $e->getMessage());
        }
    }
    
    public function destroy(int $id_curso)
    {
        try {
            $deleted = DB::connection('mysql')->table($this->tableName)->where($this->primaryKey, $id_curso)->delete();
            return redirect()->route($this->indexRoute)
                ->with('mensaje', $deleted ? 'Curso eliminado.' : 'No se encontró el curso.')
                ->with('sessionEliminado', $deleted ? 'true' : 'false');
        } catch (\Exception $e) {
            Log::error('Error al eliminar curso: ' . $e->getMessage());
            return redirect()->route($this->indexRoute)
                ->with('mensaje', 'Error al eliminar: ' . $e->getMessage())
                ->with('sessionEliminado', 'false');
        }
    }
}