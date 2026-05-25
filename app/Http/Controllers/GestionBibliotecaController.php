<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Biblioteca;
use Illuminate\Support\Facades\DB; // Añadido para consultar la tabla curso
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class GestionBibliotecaController extends Controller
{
    protected $indexRoute = 'gestionbiblioteca.index';
    protected $viewPath = 'GestionBibliotecaViews/biblioteca';

    public function index()
    {
        try {
            // Eager Loading de la relación de usuario para el nombre completo
            $archivos = Biblioteca::with('usuario')->get();

            // Obtenemos las materias únicas registradas en la tabla de cursos
            $materiasDisponibles = DB::connection('mysql')
                ->table('curso')
                ->whereNotNull('materia')
                ->where('materia', '!=', '')
                ->distinct()
                ->pluck('materia');

            return view($this->viewPath, compact('archivos', 'materiasDisponibles'));
        } catch (\Exception $e) {
            Log::error('Error al cargar biblioteca: ' . $e->getMessage());
            return view($this->viewPath, [
                    'archivos' => collect(), 
                    'materiasDisponibles' => collect()
                ])
                ->with('mensaje', 'Error al conectar con el servidor.')
                ->with('sessionInsertado', 'false');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_doc'   => 'required|string|max:255',
            'materia'      => 'required|string|max:100', // Sigue validando la materia seleccionada
            'ruta_archivo' => 'required|file|mimes:pdf|max:10240',
        ]);

        try {
            $path = $request->file('ruta_archivo')->store('biblioteca', 'public');
            $usuarioLogueado = Auth::user(); 

            Biblioteca::create([
                'nombre_doc'     => $request->nombre_doc,
                'materia'        => $request->materia,
                'ruta_archivo'   => $path,
                'correo_usuario' => $usuarioLogueado ? $usuarioLogueado->correo : 'admin@cetis17.edu.mx',
                'id_curso'       => null,
                'autor'          => $usuarioLogueado ? $usuarioLogueado->nombre : 'Administrador'
            ]);

            return redirect()->route($this->indexRoute)
                ->with('sessionInsertado', 'true')
                ->with('mensaje', 'Documento publicado con éxito.');
        } catch (\Exception $e) {
            Log::error('Error en store de biblioteca: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('sessionInsertado', 'false')
                ->with('mensaje', 'Error al subir el archivo.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_doc' => 'required|string|max:255',
            'materia'    => 'required|string|max:100',
        ]);

        try {
            $documento = Biblioteca::findOrFail($id);
            
            $documento->update([
                'nombre_doc' => $request->nombre_doc,
                'materia'    => $request->materia
            ]);

            return redirect()->route($this->indexRoute)
                ->with('sessionInsertado', 'true')
                ->with('mensaje', 'Metadatos modificados correctamente.');
        } catch (\Exception $e) {
            Log::error('Error en update de biblioteca: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('sessionInsertado', 'false')
                ->with('mensaje', 'Error al actualizar el registro.');
        }
    }

    public function destroy($id)
    {
        try {
            $documento = Biblioteca::findOrFail($id);
            $documento->delete();

            return redirect()->route($this->indexRoute)
                ->with('mensaje', 'Documento eliminado de la biblioteca.')
                ->with('sessionEliminado', 'true');
        } catch (\Exception $e) {
            Log::error('Error en destroy de biblioteca: ' . $e->getMessage());
            return redirect()->route($this->indexRoute)
                ->with('mensaje', 'No se pudo eliminar el recurso.');
        }
    }
}