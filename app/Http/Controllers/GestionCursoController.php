<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GestionCursoController extends Controller
{
    protected $indexRoute = 'gestioncurso.index';
    protected $viewPath = 'GestionCursoViews/curso';

    /**
     * Muestra el panel principal de control de cursos.
     */
    public function index()
    {
        try {
            // Usamos Eloquent nativo a través de tu modelo Curso
            $cursos = Curso::all();
            return view($this->viewPath, compact('cursos'));
        } catch (\Exception $e) {
            Log::error('Error al cargar la gestión de cursos: ' . $e->getMessage());
            return view($this->viewPath, ['cursos' => collect()])
                        ->with('mensaje', 'Error al conectar con la base de datos institucional: ' . $e->getMessage())
                        ->with('sessionInsertado', 'false');
        }
    }

    /**
     * Registra una nueva entidad curso en el esquema.
     */
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
            // Inserción directa mapeada por el modelo Eloquent
            Curso::create($request->all());

            return redirect()->route($this->indexRoute)
                ->with('sessionInsertado', 'true')
                ->with('mensaje', "El curso '{$request->nombre_curso}' ha sido dado de alta exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error al registrar curso: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('sessionInsertado', 'false')
                ->with('mensaje', "Fallo en el registro técnico: " . $e->getMessage());
        }
    }
    
    /**
     * Actualiza los datos de un curso específico mediante PUT.
     */
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
            $curso = Curso::findOrFail($id_curso);
            $curso->update($request->all());

            return redirect()->route($this->indexRoute)
                ->with('sessionInsertado', 'true')
                ->with('mensaje', "Información del curso ID {$id_curso} actualizada correctamente.");

        } catch (\Exception $e) {
            Log::error('Error al actualizar curso: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('sessionInsertado', 'false')
                ->with('mensaje', "Error de guardado en base de datos: " . $e->getMessage());
        }
    }
    
    /**
     * Remueve la entidad curso de forma permanente.
     */
    public function destroy(int $id_curso)
    {
        try {
            $curso = Curso::find($id_curso);
            
            if ($curso) {
                $curso->delete();
                return redirect()->route($this->indexRoute)
                    ->with('mensaje', 'El curso seleccionado fue eliminado del sistema.')
                    ->with('sessionEliminado', 'true');
            }

            return redirect()->route($this->indexRoute)
                ->with('mensaje', 'No se localizó la clave del curso solicitado.')
                ->with('sessionEliminado', 'false');

        } catch (\Exception $e) {
            Log::error('Error al eliminar curso: ' . $e->getMessage());
            return redirect()->route($this->indexRoute)
                ->with('mensaje', 'Imposible eliminar registro: ' . $e->getMessage())
                ->with('sessionEliminado', 'false');
        }
    }
}