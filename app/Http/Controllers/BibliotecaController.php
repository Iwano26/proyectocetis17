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
        // 1. Cargamos el query con la relación de usuario para el Nombre Completo + Apellidos
        $query = Biblioteca::with('usuario');
        $user = Auth::user();

        // 2. FILTRO: "Mis Archivos" (Muestra solo los subidos por el usuario actual)
        if ($request->input('mis_archivos') == 1 && $user) {
            $query->where('correo_usuario', $user->correo);
        }

        // 3. BUSCADOR: Buscar por nombre de documento
        if ($request->filled('buscar')) {
            $term = $request->input('buscar');
            $query->where('nombre_doc', 'LIKE', "%{$term}%");
        }

        // 4. FILTRO: Filtrar por Materia seleccionada (Usando el campo 'materia' de tu tabla biblioteca)
        if ($request->filled('materia')) {
            $query->where('materia', $request->input('materia'));
        }

        // 5. ORDEN: Más reciente o Más antiguo
        $direccionOrden = 'desc'; // Por defecto el más reciente
        if ($request->input('orden') === 'antiguo') {
            $direccionOrden = 'asc';
        }
        $archivos = $query->orderBy('created_at', $direccionOrden)->get();

        // 6. TRUCO DE ORO: Traemos las materias ÚNICAS y limpias de la tabla 'curso'
        // Esto junta los duplicados y quita espacios basura para que NO salgan 7 "Programación" XD
        $materiasDisponibles = DB::connection('mysql')
            ->table('curso')
            ->whereNotNull('materia')
            ->where('materia', '!=', '')
            ->select(DB::raw('DISTINCT TRIM(materia) as materia'))
            ->orderBy('materia', 'asc')
            ->pluck('materia');

        // Retornamos la vista inyectando los archivos filtrados y la lista limpia de materias
        return view('biblioteca', compact('archivos', 'materiasDisponibles'));
    }
    
    public function create()
    {
        // Traemos las materias ÚNICAS y limpias para el formulario de subida
        $materias = DB::connection('mysql')
            ->table('curso')
            ->whereNotNull('materia')
            ->where('materia', '!=', '')
            ->select(DB::raw('DISTINCT TRIM(materia) as materia'))
            ->orderBy('materia', 'asc')
            ->pluck('materia'); // Nos da una lista limpia de textos

        return view('BibliotecaViews.SubirArchivo', compact('materias'));
    }

    /**
     * NUEVO: Procesa y guarda un nuevo documento real en la base de datos
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_doc'  => 'required|string|max:255',
            'materia'     => 'required|string|max:100',
            'archivo_pdf' => 'required|file|mimes:pdf|max:10240',
        ]);

        try {
            $user = Auth::user();
            $path = null;

            // Procesamos y subimos el archivo a la carpeta publica documentos/
            if ($request->hasFile('archivo_pdf')) {
                $file = $request->file('archivo_pdf');
                $nombreArchivo = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('documentos'), $nombreArchivo);
                $path = $nombreArchivo;
            }

            Biblioteca::create([
                'id_curso'       => null, // Nulo para desligarlo de duplicados y usar texto limpio
                'correo_usuario' => $user ? $user->correo : 'anonimo@cetis17.edu.mx',
                'nombre_doc'     => $request->nombre_doc,
                'materia'        => $request->materia, // Guardamos la materia directa
                'ruta_archivo'   => $path,
                'autor'          => $user ? ($user->nombre . ' ' . $user->apellido) : 'Invitado'
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

        // BLINDAJE: Si no es Admin Y tampoco es el Asesor dueño del archivo, lo sacamos patitas pa' la calle
        if ($user->rol !== 'Administrador' && $archivo->correo_usuario !== $user->correo) {
            abort(403, 'No tienes permisos para editar este archivo.');
        }

        // ACTUALIZADO: Traemos las materias de forma única también para editar sin ver duplicados
        $materias = DB::connection('mysql')
            ->table('curso')
            ->whereNotNull('materia')
            ->where('materia', '!=', '')
            ->select(DB::raw('DISTINCT TRIM(materia) as materia'))
            ->orderBy('materia', 'asc')
            ->pluck('materia');

        return view('BibliotecaViews.EditarArchivo', compact('archivo', 'materias'));
    }

    public function update(Request $request, $id)
    {
        $archivo = Biblioteca::where('id_biblioteca', $id)->firstOrFail();
        $user = Auth::user();

        // BLINDAJE: Seguridad en el procesamiento del formulario
        if ($user->rol !== 'Administrador' && $archivo->correo_usuario !== $user->correo) {
            abort(403, 'No tienes permisos para actualizar este archivo.');
        }

        // ACTUALIZADO: Actualizamos directamente la materia con el string limpio del select
        $archivo->update([
            'nombre_doc' => $request->nombre_doc,
            'id_curso'   => null, // Lo desligamos de IDs individuales 
            'materia'    => $request->materia 
        ]);

        return redirect()->route('biblioteca.index')->with('success', 'Información actualizada correctamente.');
    }

    public function destroy($id)
    {
        $archivo = Biblioteca::where('id_biblioteca', $id)->firstOrFail();
        $user = Auth::user();

        // BLINDAJE: Seguridad al borrar
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