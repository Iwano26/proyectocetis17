<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AsesoriaController extends Controller
{
    // Muestra la pantalla para crear la asesoría
    public function create($id_curso)
    {
        return view('AsesoriasViews.crearAsesoria', compact('id_curso'));
    }

    // EL MÉTODO QUE TE FALTA: Guarda los datos en la base de datos (Doble Insert)
    public function store(Request $request, $id_curso)
    {
        // 1. Validar que los campos vengan completos
        $request->validate([
            'nombre_evento'  => 'required|string|max:255',
            'lugar'           => 'required|string|max:255',
            'fecha_asesoria'  => 'required|date',
            'hora_inicio'     => 'required',
            'hora_fin'        => 'required',
            'estado'          => 'required|in:DISPONIBLE,EN_CURSO,TERMINADA,CANCELADA'
        ]);

        // Usamos una Transacción por seguridad
        DB::beginTransaction();

        try {
            // 2. PRIMER INSERT: Tabla 'evento'
            $id_evento = DB::table('evento')->insertGetId([
                'id_curso'      => $id_curso,
                'nombre_evento' => $request->nombre_evento,
                'fecha'         => Carbon::now()->toDateString(), 
                'hora'          => Carbon::now()->toTimeString(), 
                'tipo'          => 'asesoria'                     
            ]);

            // 3. SEGUNDO INSERT: Tabla 'asesoria'
            DB::table('asesoria')->insert([
                'id_evento'      => $id_evento, 
                'lugar'          => $request->lugar,
                'fecha_asesoria' => $request->fecha_asesoria, 
                'hora_inicio'    => $request->hora_inicio,    
                'hora_fin'       => $request->hora_fin,       
                'estado'         => $request->estado              
            ]);

            DB::commit();

            // Redireccionamos a la vista del curso
            return redirect('/curso/' . $id_curso . '/eventos')->with('success', '¡Asesoría agendada con éxito, papu!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Hubo un error al guardar: ' . $e->getMessage());
        }
    }

    public function destroy($id_evento)
    {
        try {
            // Buscamos el evento y lo eliminamos
            DB::table('evento')->where('id_evento', $id_evento)->delete();
            
            return back()->with('success', '¡Actividad eliminada correctamente, papu!');
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo eliminar: ' . $e->getMessage());
        }
    }

    public function unirse($id_evento)
    {
        // 1. Buscamos la asesoría ligada a ese evento
        $asesoria = DB::table('asesoria')
            ->where('id_evento', $id_evento)
            ->first();

        if (!$asesoria) {
            return back()->with('error', 'No se encontró la asesoría.');
        }

        // 2. Verificamos que no esté ya registrado
        $yaRegistrado = DB::table('asistencia_asesoria')
            ->where('id_asesoria', $asesoria->id_asesoria)
            ->where('correo_persona', Auth::user()->correo)
            ->exists();

        if ($yaRegistrado) {
            return back()->with('error', 'Ya estás registrado en esta asesoría.');
        }

        // 3. Insertamos el registro
        DB::table('asistencia_asesoria')->insert([
            'id_asesoria'    => $asesoria->id_asesoria,
            'correo_persona' => Auth::user()->correo,
            'asistio'        => 'PENDIENTE'
        ]);

        return back()->with('success', '¡Te has registrado en la asesoría!');
    }

    public function listaAsistencia($id_evento)
    {
        $asesoria = DB::table('asesoria')
            ->join('evento', 'asesoria.id_evento', '=', 'evento.id_evento')
            ->where('asesoria.id_evento', $id_evento)
            ->select('asesoria.*', 'evento.nombre_evento')
            ->first();

        if (!$asesoria) {
            return back()->with('error', 'Asesoría no encontrada.');
        }

        $asistentes = DB::table('asistencia_asesoria')
            ->join('persona', 'asistencia_asesoria.correo_persona', '=', 'persona.correo')
            ->where('asistencia_asesoria.id_asesoria', $asesoria->id_asesoria)
            ->select(
                'persona.nombre',
                'persona.apellidoPa',
                'persona.apellidoMa',
                'persona.correo',
                'asistencia_asesoria.asistio',
                'asistencia_asesoria.id_asistencia'
            )
            ->get();

        return response()->json($asistentes);
    }

    public function cancelarAsistencia($id_evento)
    {
        $asesoria = DB::table('asesoria')
            ->where('id_evento', $id_evento)
            ->first();

        if (!$asesoria) {
            return back()->with('error', 'No se encontró la asesoría.');
        }

        DB::table('asistencia_asesoria')
            ->where('id_asesoria', $asesoria->id_asesoria)
            ->where('correo_persona', Auth::user()->correo)
            ->delete();

        return back()->with('success', 'Has cancelado tu asistencia.');
    }

    public function edit($id_evento)
    {
        // Buscamos el evento y la asesoría juntos
        $asesoria = DB::table('asesoria')
            ->join('evento', 'asesoria.id_evento', '=', 'evento.id_evento')
            ->where('asesoria.id_evento', $id_evento)
            ->select(
                'evento.id_evento',
                'evento.id_curso',
                'evento.nombre_evento',
                'asesoria.id_asesoria',
                'asesoria.lugar',
                'asesoria.fecha_asesoria',
                'asesoria.hora_inicio',
                'asesoria.hora_fin',
                'asesoria.estado'
            )
            ->first();

        if (!$asesoria) {
            return redirect()->back()->with('error', 'Asesoría no encontrada.');
        }

        return view('AsesoriasViews.editarAsesoria', compact('asesoria'));
    }

    public function update(Request $request, $id_evento)
    {
        $request->validate([
            'nombre_evento'  => 'required|string|max:255',
            'lugar'          => 'required|string|max:255',
            'fecha_asesoria' => 'required|date',
            'hora_inicio'    => 'required',
            'hora_fin'       => 'required',
            'estado'         => 'required|in:DISPONIBLE,EN_CURSO,TERMINADA,CANCELADA'
        ]);

        DB::beginTransaction();

        try {
            // Actualizamos la tabla evento
            DB::table('evento')
                ->where('id_evento', $id_evento)
                ->update([
                    'nombre_evento' => $request->nombre_evento,
                ]);

            // Actualizamos la tabla asesoria
            DB::table('asesoria')
                ->where('id_evento', $id_evento)
                ->update([
                    'lugar'          => $request->lugar,
                    'fecha_asesoria' => $request->fecha_asesoria,
                    'hora_inicio'    => $request->hora_inicio,
                    'hora_fin'       => $request->hora_fin,
                    'estado'         => $request->estado,
                ]);

            DB::commit();

            // Regresamos a los eventos del curso
            return redirect('/curso/' . $request->id_curso . '/eventos')
                ->with('success', '¡Asesoría actualizada con éxito!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function agregarManualmente(Request $request, $id_asesoria)
    {
        $request->validate([
            'correo_persona' => 'required|email'
        ]);

        // Verificar que no esté ya registrado
        $yaExiste = DB::table('asistencia_asesoria')
            ->where('id_asesoria', $id_asesoria)
            ->where('correo_persona', $request->correo_persona)
            ->exists();

        if ($yaExiste) {
            return back()->with('error', 'Este alumno ya está en la lista.');
        }

        DB::table('asistencia_asesoria')->insert([
            'id_asesoria'    => $id_asesoria,
            'correo_persona' => $request->correo_persona,
            'asistio'        => 'ASISTIO'
        ]);

        return back()->with('success', 'Alumno agregado a la lista correctamente.');
    }
}