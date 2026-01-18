<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Biblioteca; // El modelo que creaste
use Illuminate\Support\Facades\Auth; // Para obtener el nombre del usuario
use Illuminate\Support\Facades\File; // Por si necesitas manejar archivos físicos

class BibliotecaController extends Controller
{
    /**
     * Muestra la lista de archivos en la biblioteca
     */
    public function index()
    {
        // Jalamos todos los registros de la tabla biblioteca
        $archivos = Biblioteca::all();
        
        // Retornamos la vista principal de la biblioteca enviando los datos
        return view('biblioteca', compact('archivos'));
    }

    /**
     * Muestra el formulario para subir un nuevo archivo
     */
    public function create()
    {
        // Retorna la vista con el formulario que creamos antes
        return view('BibliotecaViews.SubirArchivo');
    }

    /**
     * Recibe los datos del formulario y guarda el archivo PDF
     */
    public function store(Request $request)
    {
        // 1. Validar que los datos sean correctos
        $request->validate([
            'nombre_doc' => 'required|string|max:255',
            'materia'    => 'required|string',
            'archivo_pdf' => 'required|mimes:pdf|max:10240', // Solo PDF y máx 10MB
        ], [
            'archivo_pdf.mimes' => 'El archivo debe ser un formato PDF válido.',
            'archivo_pdf.max'   => 'El archivo no debe pesar más de 10MB.'
        ]);

        // 2. Verificar si se envió el archivo
        if ($request->hasFile('archivo_pdf')) {
            
            $file = $request->file('archivo_pdf');

            // 3. Crear un nombre único para el archivo (evita que se sobrescriban)
            // Ejemplo: 170554212_manual_algoritmos.pdf
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();

            // 4. Mover el archivo a la carpeta public/documentos
            // public_path apunta a la carpeta 'public' de tu proyecto
            $file->move(public_path('documentos'), $nombreArchivo);

            // 5. Guardar el registro en la base de datos usando el Modelo
            Biblioteca::create([
                'nombre_doc'   => $request->nombre_doc,
                'materia'      => $request->materia,
                'ruta_archivo' => $nombreArchivo,
                'autor'        => Auth::user()->nombre // Aquí usamos la variable 'nombre' de tu tabla persona
            ]);

            // 6. Redirigir con un mensaje de éxito
            return redirect()->route('biblioteca.index')->with('success', '¡El documento se ha subido correctamente!');
        }

        return back()->with('error', 'No se pudo procesar el archivo.');
    }

    public function destroy($id) {
        $archivo = Biblioteca::findOrFail($id);

        // 1. Borrar el archivo físico de la carpeta public/documentos
        $rutaFisica = public_path('documentos/' . $archivo->ruta_archivo);
        if (file_exists($rutaFisica)) {
            unlink($rutaFisica);
        }

        // 2. Borrar el registro de la base de datos
        $archivo->delete();

        return back()->with('success', 'Archivo eliminado correctamente.');
    }

    public function edit($id) {
        $archivo = Biblioteca::findOrFail($id);
        return view('BibliotecaViews.EditarArchivo', compact('archivo'));
    }

    public function update(Request $request, $id) {
        $archivo = Biblioteca::findOrFail($id);
        $archivo->nombre_doc = $request->nombre_doc;
        $archivo->materia = $request->materia;
        $archivo->save();

        return redirect()->route('biblioteca.index')->with('success', 'Archivo actualizado.');
    }
}