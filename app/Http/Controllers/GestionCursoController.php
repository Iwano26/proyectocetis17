<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GestionCursoController extends Controller
{
    // Nombre de la tabla de la base de datos
    protected $tableName = 'curso';
    // Columna de la llave primaria para buscar y editar/eliminar
    protected $primaryKey = 'id_curso';

    // Rutas de redirección (ajustadas a la nueva vista y ruta)
    protected $indexRoute = 'gestioncurso.index';
    protected $viewPath = 'GestionCursoViews/curso'; // La vista 'curso.blade.php'

    /**
     * Muestra la lista de todos los cursos y la vista de gestión (CRUD READ).
     * Ruta: GET /gestioncurso
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Obtener todos los cursos de la tabla 'curso'
            $cursos = DB::connection('mysql')->table($this->tableName)->get();
            
            // Devolver la vista, pasando la colección de cursos
            return view($this->viewPath, ['cursos' => $cursos]);

        } catch (\Exception $e) {
            Log::error('Error al cargar la gestión de cursos: ' . $e->getMessage());
            // En caso de error, pasa una colección vacía y un mensaje de error a la vista.
            return view($this->viewPath, ['cursos' => collect()])
                        ->with('mensaje', 'Error al conectar con la base de datos: ' . $e->getMessage())
                        ->with('sessionInsertado', 'false');
        }
    }

    /**
     * Almacena un nuevo curso (CRUD CREATE).
     * Ruta: POST /gestioncurso
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validar datos
        $request->validate([
            'nombre_curso' => 'required|string|max:60',
            'descripcion' => 'nullable|string|max:50',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'materia' => 'required|string|max:50',
            'horas_disponibles' => 'required|integer|min:1|max:24', // Asumiendo min 1 y max 24 por el valor que tenías
            'estado' => 'required|in:ACTIVO,INACTIVO,COMPLETADO', // Usando los valores ENUM de tu tabla
        ]);
        
        $Mensaje = "";
        try{
            // 2. Insertar en la tabla 'curso'
            DB::connection('mysql')
                ->table($this->tableName)
                ->insert([
                    'nombre_curso' => $request->nombre_curso,
                    'descripcion' => $request->descripcion,
                    'fecha_inicio' => $request->fecha_inicio,
                    'fecha_fin' => $request->fecha_fin,
                    'materia' => $request->materia,
                    'horas_disponibles' => $request->horas_disponibles,
                    'estado' => $request->estado,
                    // id_curso es AUTO_INCREMENT, no se inserta
                ]);

            $Mensaje = "Curso registrado exitosamente: " . $request->nombre_curso;

            return redirect()->route($this->indexRoute)
                ->with('sessionInsertado', 'true')
                ->with('mensaje', $Mensaje);

        } catch (\Exception $e){
            Log::error('Error al registrar curso: ' . $e->getMessage());
            $Mensaje = "Hubo un error al registrar el curso: " . $e->getMessage();
            
            return redirect()->back()
                ->withInput()
                ->with('sessionInsertado', 'false')
                ->with('mensaje', $Mensaje);
        }
    }
    
    /**
     * Actualiza los datos de un curso existente (CRUD UPDATE).
     * Ruta: PUT/PATCH /gestioncurso/{id_curso}
     * @param \Illuminate\Http\Request $request
     * @param int $id_curso ID del curso a actualizar.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id_curso)
    {
        // 1. Validar los datos de entrada
        $rules = [
            'nombre_curso' => 'required|string|max:60',
            'descripcion' => 'nullable|string|max:50',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'materia' => 'required|string|max:50',
            'horas_disponibles' => 'required|integer|min:1|max:24',
            'estado' => 'required|in:ACTIVO,INACTIVO,COMPLETADO',
        ];
        
        $request->validate($rules);

        $dataToUpdate = [
            'nombre_curso' => $request->nombre_curso,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'materia' => $request->materia,
            'horas_disponibles' => $request->horas_disponibles,
            'estado' => $request->estado,
        ];
        
        $Mensaje = "";

        try {
            // 2. Realizar la actualización
            $updated = DB::connection('mysql')
                ->table($this->tableName)
                ->where($this->primaryKey, $id_curso)
                ->update($dataToUpdate);

            $Mensaje = "Curso con ID {$id_curso} actualizado exitosamente.";
            
            return redirect()->route($this->indexRoute)
                ->with('sessionInsertado', 'true')
                ->with('mensaje', $Mensaje);

        } catch (\Exception $e) {
            Log::error('Error al actualizar curso: ' . $e->getMessage());
            $Mensaje = "Hubo un error al actualizar el curso: " . $e->getMessage();
            
            return redirect()->back()
                ->withInput()
                ->with('sessionInsertado', 'false')
                ->with('mensaje', $Mensaje);
        }
    }
    
    /**
     * Elimina un curso (CRUD DELETE).
     * Ruta: DELETE /gestioncurso/{id_curso}
     * @param int $id_curso ID del curso a eliminar.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id_curso)
    {
        try {
            // Eliminar el registro donde el id_curso coincide
            $deleted = DB::connection('mysql')
                ->table($this->tableName)
                ->where($this->primaryKey, $id_curso)
                ->delete();

            if ($deleted) {
                return redirect()->route($this->indexRoute)
                    ->with('mensaje', 'Curso con ID ' . $id_curso . ' eliminado exitosamente.')
                    ->with('sessionEliminado', 'true');
            } else {
                return redirect()->route($this->indexRoute)
                    ->with('mensaje', 'El curso con ID ' . $id_curso . ' no fue encontrado para eliminar.')
                    ->with('sessionEliminado', 'false');
            }

        } catch (\Exception $e) {
            Log::error('Error al eliminar curso: ' . $e->getMessage());
            return redirect()->route($this->indexRoute)
                ->with('mensaje', 'Error al eliminar el curso: ' . $e->getMessage())
                ->with('sessionEliminado', 'false');
        }
    }
}