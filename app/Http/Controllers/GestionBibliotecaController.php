<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Biblioteca;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GestionBibliotecaController extends Controller
{
    protected $indexRoute = 'gestionbiblioteca.index';
    protected $viewPath = 'GestionBibliotecaViews/biblioteca';

    public function index()
    {
        try {
            $archivos = Biblioteca::with('usuario')->get();

            $materiasDisponibles = DB::connection('mysql')
                ->table('curso')
                ->whereNotNull('materia')
                ->where('materia', '!=', '')
                ->select(DB::raw('DISTINCT TRIM(materia) as materia'))
                ->orderBy('materia', 'asc')
                ->pluck('materia');

            // Todos los cursos para el select del admin
            $cursosDisponibles = DB::connection('mysql')
                ->table('curso')
                ->orderBy('nombre_curso', 'asc')
                ->get(['id_curso', 'nombre_curso', 'materia']);

            return view($this->viewPath, compact('archivos', 'materiasDisponibles', 'cursosDisponibles'));
        } catch (\Exception $e) {
            Log::error('Error al cargar biblioteca: ' . $e->getMessage());
            return view($this->viewPath, [
                'archivos' => collect(),
                'materiasDisponibles' => collect(),
                'cursosDisponibles' => collect()
            ])->with('mensaje', 'Error al conectar con el servidor.')
            ->with('sessionInsertado', 'false');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_doc'   => 'required|string|max:255',
            'materia'      => 'required|string|max:100',
            'id_curso'     => 'required|integer|exists:curso,id_curso',
            'ruta_archivo' => 'required|file|mimes:pdf|max:10240',
        ]);

        try {
            $user = Auth::user();
            $file = $request->file('ruta_archivo');
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('documentos'), $nombreArchivo);

            Biblioteca::create([
                'nombre_doc'     => $request->nombre_doc,
                'materia'        => $request->materia,
                'ruta_archivo'   => $nombreArchivo,
                'correo_usuario' => $user ? $user->correo : 'admin@cetis17.edu.mx',
                'id_curso'       => $request->id_curso, // Ya viene del select
            ]);

            return redirect()->route($this->indexRoute)
                ->with('sessionInsertado', 'true')
                ->with('mensaje', 'Documento publicado con éxito.');
        } catch (\Exception $e) {
            Log::error('Error en store de biblioteca: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('sessionInsertado', 'false')
                ->with('mensaje', 'Error al subir el archivo: ' . $e->getMessage());
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
                'materia'    => $request->materia,
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

            // Borramos el archivo físico también
            $ruta = public_path('documentos/' . $documento->ruta_archivo);
            if (file_exists($ruta)) {
                unlink($ruta);
            }

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