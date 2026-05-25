<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EvidenciaController extends Controller
{
    // =============================================
    // ALUMNO: Subir evidencia
    // =============================================
    public function store(Request $request, $id_asesoria)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:pdf|max:5120', // máximo 5MB
        ], [
            'archivo.required' => 'Debes seleccionar un archivo PDF.',
            'archivo.mimes'    => 'Solo se permiten archivos PDF.',
            'archivo.max'      => 'El archivo no puede pesar más de 5MB.',
        ]);

        // Verificar que la asesoría requiere evidencia
        $asesoria = DB::table('asesoria')->where('id_asesoria', $id_asesoria)->first();

        if (!$asesoria || !$asesoria->requiere_evidencia) {
            return back()->with('error', 'Esta asesoría no requiere evidencia.');
        }

        // Verificar que el alumno estuvo inscrito
        $inscrito = DB::table('asistencia_asesoria')
            ->where('id_asesoria', $id_asesoria)
            ->where('correo_persona', Auth::user()->correo)
            ->exists();

        if (!$inscrito) {
            return back()->with('error', 'No estás registrado en esta asesoría.');
        }

        // Verificar que no haya subido ya una evidencia
        $yaSubio = DB::table('evidencia_asesoria')
            ->where('id_asesoria', $id_asesoria)
            ->where('correo_alumno', Auth::user()->correo)
            ->exists();

        if ($yaSubio) {
            return back()->with('error', 'Ya subiste una evidencia para esta asesoría.');
        }

        // Guardar el archivo
        $archivo = $request->file('archivo');
        $nombreArchivo = Auth::user()->correo . '_' . $id_asesoria . '_' . time() . '.pdf';
        $ruta = $archivo->storeAs('evidencias', $nombreArchivo, 'public');

        // Guardar en BD
        DB::table('evidencia_asesoria')->insert([
            'id_asesoria'  => $id_asesoria,
            'correo_alumno' => Auth::user()->correo,
            'archivo'      => $ruta,
            'fecha_subida' => now(),
        ]);

        return back()->with('success', '¡Evidencia subida correctamente!');
    }

    // =============================================
    // ASESOR: Ver evidencias de una asesoría
    // =============================================
    public function verEvidencias($id_asesoria)
    {
        $asesoria = DB::table('asesoria')
            ->join('evento', 'asesoria.id_evento', '=', 'evento.id_evento')
            ->where('asesoria.id_asesoria', $id_asesoria)
            ->select('asesoria.*', 'evento.nombre_evento', 'evento.id_curso')
            ->first();

        if (!$asesoria) {
            return back()->with('error', 'Asesoría no encontrada.');
        }

        $evidencias = DB::table('evidencia_asesoria')
            ->join('persona', 'evidencia_asesoria.correo_alumno', '=', 'persona.correo')
            ->where('evidencia_asesoria.id_asesoria', $id_asesoria)
            ->select(
                'evidencia_asesoria.id_evidencia',
                'evidencia_asesoria.archivo',
                'evidencia_asesoria.fecha_subida',
                'persona.nombre',
                'persona.apellidoPa',
                'persona.apellidoMa',
                'persona.correo'
            )
            ->orderBy('evidencia_asesoria.fecha_subida', 'desc')
            ->get();

        return view('EvidenciasViews.verEvidencias', compact('asesoria', 'evidencias'));
    }

    // =============================================
    // ALUMNO: Eliminar su evidencia
    // =============================================
    public function destroy($id_evidencia)
    {
        $evidencia = DB::table('evidencia_asesoria')
            ->where('id_evidencia', $id_evidencia)
            ->where('correo_alumno', Auth::user()->correo)
            ->first();

        if (!$evidencia) {
            return back()->with('error', 'Evidencia no encontrada.');
        }

        // Eliminar archivo del storage
        Storage::disk('public')->delete($evidencia->archivo);

        // Eliminar de BD
        DB::table('evidencia_asesoria')
            ->where('id_evidencia', $id_evidencia)
            ->delete();

        return back()->with('success', 'Evidencia eliminada correctamente.');
    }
}