<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CursoController extends Controller
{
    protected $tableName = 'curso';
    protected $primaryKey = 'id_curso';
     protected $indexRoute = 'cursos.index';

    /**
     * Muestra la lista de cursos con filtros de búsqueda.
     */
    public function index(Request $request) 
    {
        try {
            $buscar = $request->input('buscar');
            $estado = $request->input('estado');
            $materia = $request->input('materia');
            $dia = $request->input('dia');

            $query = DB::table($this->tableName);

            // Filtro por nombre o materia
            if ($buscar) {
                $query->where(function($q) use ($buscar) {
                    $q->where('nombre_curso', 'LIKE', "%{$buscar}%")
                      ->orWhere('materia', 'LIKE', "%{$buscar}%");
                });
            }

            // Filtro por estado (Abierto/Cerrado)
            if ($estado) {
                $query->where('estado', $estado);
            }

            // Filtro por materia (Select)
            if ($materia) {
                $query->where('materia', $materia);
            }

            // Nota: El filtro por 'dia' requeriría un JOIN con la tabla horarios
            $cursos = $query->get();

            return view('BuscarCurso', compact('cursos'));

        } catch (\Exception $e) {
            Log::error('Error en index de cursos: ' . $e->getMessage());
            return view('BuscarCurso', ['cursos' => collect()])->with('mensaje', 'Error al cargar cursos.');
        }
    }

    public function create() {
        return view('CursosViews/crear');
    }

    /**
     * Guarda un nuevo curso y sus múltiples horarios.
     */
    public function store(Request $request)
    {
        // 1. Quita el dd($request->all()); para que pueda seguir el proceso

        try {
            DB::beginTransaction();

            $id_nuevo_curso = DB::table($this->tableName)->insertGetId([
                'correo_persona'    => Auth::user()->correo, // <--- FALTABA ESTE
                'nombre_curso'      => $request->nombre_curso,
                // 'descripcion'       => $request->descripcion,
                'fecha_inicio'      => $request->fecha_inicio,
                'fecha_fin'         => $request->fecha_fin,
                'materia'           => $request->materia,
                'horas_disponibles' => $request->horas_disponibles,
                'estado'            => $request->estado,
                // 'acceso'            => $request->acceso ?? '', // Si es null, manda cadena vacía
            ]);

            // 2. Insertar horarios (asegúrate que el nombre de la tabla sea curso_horarios)
            if ($request->has('dia')) {
                foreach ($request->dia as $key => $valorDia) {
                    DB::table('curso_horarios')->insert([
                        'id_curso'    => $id_nuevo_curso,
                        'dia_semana'  => $valorDia,
                        'hora_inicio' => $request->hora_inicio[$key],
                        'hora_fin'    => $request->hora_fin[$key],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route($this->indexRoute)->with('mensaje', 'Curso guardado con éxito');

        } catch (\Exception $e) {
            DB::rollBack();
            // Esto te dirá el error exacto en pantalla si algo falla
            return "Error al guardar: " . $e->getMessage(); 
        }
    }

    /**
     * Mostrar formulario de edición con horarios reales.
     */
    public function edit($id) {
        $curso = DB::table($this->tableName)->where($this->primaryKey, $id)->first();

        if (!$curso) {
            return redirect()->route('cursos.index')->with('mensaje', 'Curso no encontrado');
        }

        // Importante: usar el nombre correcto de tu tabla 'curso_horarios'
        $curso->horarios = DB::table('curso_horarios')->where('id_curso', $id)->get();

        return view('CursosViews/editar', compact('curso'));
    }

    /**
     * Actualiza curso y reemplaza horarios.
     */
    public function update(Request $request, $id) { 
        try {
            DB::beginTransaction();

            // Actualizar datos básicos
            DB::table($this->tableName)
                ->where($this->primaryKey, $id)
                ->update([
                    'nombre_curso' => $request->nombre_curso,
                    'materia'      => $request->materia,
                    'estado'       => $request->estado,
                    'password_curso' => ($request->estado == 'Cerrado') ? $request->password_curso : null,
                ]);

            // Actualizar horarios: Lo más limpio es borrar los anteriores y crear los nuevos
            DB::table('horarios')->where('id_curso', $id)->delete();
            
            if ($request->has('dia')) {
                foreach ($request->dia as $key => $val) {
                    DB::table('horarios')->insert([
                        'id_curso'    => $id,
                        'dia'         => $request->dia[$key],
                        'hora_inicio' => $request->hora_inicio[$key],
                        'hora_fin'    => $request->hora_fin[$key],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('cursos.index')->with('success', 'Curso creado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en update: ' . $e->getMessage());
            return back()->with('mensaje', 'Error al actualizar.');
        }
    }

    public function show($id) {
        $curso = DB::table($this->tableName)->where($this->primaryKey, $id)->first();

        if (!$curso) return redirect()->route('cursos.index');

        // Adaptar nombres para la vista vercurso.blade.php
        $curso->nombre = $curso->nombre_curso;
        $curso->instructor = "Lic. Andrea García Pérez"; // O traerlo de tabla docentes
        
        // Obtener horarios reales para mostrarlos en la vista
        $curso->sesiones = DB::table('horarios')->where('id_curso', $id)->get();

        return view('CursosViews/vercurso', compact('curso'));
    }

    public function destroy($id) {
        DB::table($this->tableName)->where($this->primaryKey, $id)->delete();
        return redirect()->route('cursos.index')->with('mensaje', 'Curso eliminado.');
    }
}