<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Biblioteca;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class BibliotecaController extends Controller
{
    public function index(Request $request)
    {
        $query = Biblioteca::with('usuario');
        $user = Auth::user();

        if ($request->input('mis_archivos') == 1 && $user) {
            $query->where('correo_usuario', $user->correo);
        }

        if ($request->filled('buscar')) {
            $term = $request->input('buscar');
            $query->where('nombre_doc', 'LIKE', "%{$term}%");
        }

        if ($request->filled('materia')) {
            $query->where('materia', $request->input('materia'));
        }

        $direccionOrden = 'desc';
        if ($request->input('orden') === 'antiguo') {
            $direccionOrden = 'asc';
        }
        $archivos = $query->orderBy('created_at', $direccionOrden)->get();

        $materiasDisponibles = DB::connection('mysql')
            ->table('curso')
            ->whereNotNull('materia')
            ->where('materia', '!=', '')
            ->select(DB::raw('DISTINCT TRIM(materia) as materia'))
            ->orderBy('materia', 'asc')
            ->pluck('materia');

        return view('biblioteca', compact('archivos', 'materiasDisponibles'));
    }

    public function create()
    {
        $user = Auth::user();

        // Solo los cursos donde el usuario es Asesor
        $cursos = DB::connection('mysql')
            ->table('curso')
            ->where('correo_persona', $user->correo)
            ->orderBy('nombre_curso', 'asc')
            ->get(['id_curso', 'nombre_curso', 'materia']);

        return view('BibliotecaViews.SubirArchivo', compact('cursos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_doc'  => 'required|string|max:255',
            'id_curso'    => 'required|integer|exists:curso,id_curso',
            'materia'     => 'required|string|max:100',
            'archivo_pdf' => 'required|file|mimes:pdf|max:10240',
        ]);

        try {
            $user = Auth::user();
            $path = null;

            if ($request->hasFile('archivo_pdf')) {
                $file = $request->file('archivo_pdf');
                $nombreArchivo = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('documentos'), $nombreArchivo);
                $path = $nombreArchivo;
            }

            Biblioteca::create([
                'id_curso'       => $request->id_curso,
                'correo_usuario' => $user ? $user->correo : 'anonimo@cetis17.edu.mx',
                'nombre_doc'     => $request->nombre_doc,
                'materia'        => $request->materia,
                'ruta_archivo'   => $path,
            ]);

            return redirect()->route('biblioteca.index')
                ->with('success', 'Documento publicado con éxito.')
                ->with('sessionInsertado', 'true');

        } catch (\Exception $e) {
            Log::error('Error al guardar en biblioteca: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'No se pudo subir el archivo.');
        }
    }

    public function edit($id)
    {
        $archivo = Biblioteca::where('id_biblioteca', $id)->firstOrFail();
        $user = Auth::user();

        if ($user->rol !== 'Administrador' && $archivo->correo_usuario !== $user->correo) {
            abort(403, 'No tienes permisos para editar este archivo.');
        }

        // Cursos del asesor para el select de edición
        $cursos = DB::connection('mysql')
            ->table('curso')
            ->where('correo_persona', $user->correo)
            ->orderBy('nombre_curso', 'asc')
            ->get(['id_curso', 'nombre_curso', 'materia']);

        return view('BibliotecaViews.EditarArchivo', compact('archivo', 'cursos'));
    }

    public function update(Request $request, $id)
    {
        $archivo = Biblioteca::where('id_biblioteca', $id)->firstOrFail();
        $user = Auth::user();

        if ($user->rol !== 'Administrador' && $archivo->correo_usuario !== $user->correo) {
            abort(403, 'No tienes permisos para actualizar este archivo.');
        }

        // Obtenemos la materia del curso seleccionado
        $curso = DB::connection('mysql')
            ->table('curso')
            ->where('id_curso', $request->id_curso)
            ->first();

        $archivo->update([
            'nombre_doc' => $request->nombre_doc,
            'id_curso'   => $request->id_curso,
            'materia'    => $curso ? $curso->materia : $archivo->materia,
        ]);

        return redirect()->route('biblioteca.index')->with('success', 'Información actualizada correctamente.');
    }

    public function destroy($id)
    {
        $archivo = Biblioteca::where('id_biblioteca', $id)->firstOrFail();
        $user = Auth::user();

        if ($user->rol !== 'Administrador' && $archivo->correo_usuario !== $user->correo) {
            abort(403, 'No tienes permisos para eliminar este archivo.');
        }

        $ruta = public_path('documentos/' . $archivo->ruta_archivo);

        if (File::exists($ruta)) {
            File::delete($ruta);
        }

        $archivo->delete();
        return back()->with('success', 'Archivo eliminado.');
    }
}