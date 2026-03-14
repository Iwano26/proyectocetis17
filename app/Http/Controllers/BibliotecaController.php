<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Biblioteca;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BibliotecaController extends Controller
{
    public function index(Request $request)
    {
        $query = Biblioteca::query();

        if ($request->filled('buscar')) {
            $term = $request->input('buscar');
            $query->where('nombre_doc', 'LIKE', "%{$term}%");
        }

        if ($request->filled('materia')) {
            $query->where('materia', $request->input('materia'));
        }

        $archivos = $query->orderBy('created_at', 'desc')->get();
        return view('biblioteca', compact('archivos'));
    }

    public function create()
    {
        $materias = DB::table('curso')->select('id_curso', 'materia')->get();
        return view('BibliotecaViews.SubirArchivo', compact('materias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_doc'  => 'required|string|max:255',
            'id_curso'    => 'required|integer',
            'archivo_pdf' => 'required|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('archivo_pdf')) {
            $file = $request->file('archivo_pdf');
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('documentos'), $nombreArchivo);

            $curso = DB::table('curso')->where('id_curso', $request->id_curso)->first();
            $user = Auth::user();

            // ELIMINADO EL CAMPO 'autor' PORQUE NO EXISTE EN TU TABLA
            Biblioteca::create([
                'id_curso'       => $request->id_curso,
                'correo_usuario' => $user->correo,
                'nombre_doc'     => $request->nombre_doc,
                'materia'        => $curso->materia,
                'ruta_archivo'   => $nombreArchivo,
            ]);

            return redirect()->route('biblioteca.index')->with('success', 'Archivo subido correctamente.');
        }

        return back()->with('error', 'Error al procesar el archivo.');
    }

    public function edit($id)
    {
        // Buscamos por la llave primaria de tu tabla
        $archivo = Biblioteca::where('id_biblioteca', $id)->firstOrFail();
        $materias = DB::table('curso')->select('id_curso', 'materia')->get();
        return view('BibliotecaViews.EditarArchivo', compact('archivo', 'materias'));
    }

    public function update(Request $request, $id)
    {
        $archivo = Biblioteca::where('id_biblioteca', $id)->firstOrFail();
        $curso = DB::table('curso')->where('id_curso', $request->id_curso)->first();

        $archivo->update([
            'nombre_doc' => $request->nombre_doc,
            'id_curso'   => $request->id_curso,
            'materia'    => $curso->materia
        ]);

        return redirect()->route('biblioteca.index')->with('success', 'Información actualizada.');
    }

    public function destroy($id)
    {
        $archivo = Biblioteca::where('id_biblioteca', $id)->firstOrFail();
        $ruta = public_path('documentos/' . $archivo->ruta_archivo);
        
        if (File::exists($ruta)) {
            File::delete($ruta);
        }

        $archivo->delete();
        return back()->with('success', 'Archivo eliminado.');
    }
}