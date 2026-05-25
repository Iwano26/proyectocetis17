<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AsesoriaController extends Controller
{
    // Muestra la pantalla para crear la asesoría
    public function create($id_curso)
    {
        // Traemos el curso para mostrar sus fechas en la vista
        $curso = DB::table('curso')->where('id_curso', $id_curso)->first();

        return view('AsesoriasViews.crearAsesoria', compact('id_curso', 'curso'));
    }

    public function store(Request $request, $id_curso)
    {
        // Validación básica
        $request->validate([
            'nombre_evento'  => 'required|string|max:255',
            'fecha_asesoria' => 'required|date',
            'hora_inicio'    => 'required',
            'hora_fin'       => 'required',
            'estado'         => 'required|in:DISPONIBLE,EN_CURSO,TERMINADA,CANCELADA'
        ]);

        $curso = DB::table('curso')->where('id_curso', $id_curso)->first();

        // 1. Regla de Rango de Curso
        if ($request->fecha_asesoria < $curso->fecha_inicio || $request->fecha_asesoria > $curso->fecha_fin) {
            return back()->withInput()->with('error', "La fecha debe estar entre " . date('d/m/Y', strtotime($curso->fecha_inicio)) . " y " . date('d/m/Y', strtotime($curso->fecha_fin)));
        }

        // 2. Regla de Horas: Solo validar si es el mismo día
        // (Si fueran días distintos no validamos conflicto de horario porque es lógico)
        if (strtotime($request->hora_fin) <= strtotime($request->hora_inicio)) {
            return back()->withInput()->with('error', "La hora de fin debe ser posterior a la hora de inicio.");
        }

        $requiereEvidencia = $request->has('requiere_evidencia') ? 1 : 0;

        if (!$curso) {
            return back()->withInput()->with('error', 'El curso especificado no existe.');
        }

        // 2. VALIDACIÓN DEL MAESTRO: Comprobar rango del curso
        $fechaAsesoria = $request->fecha_asesoria;

        if ($fechaAsesoria < $curso->fecha_inicio || $fechaAsesoria > $curso->fecha_fin) {
            $inicioFormateado = date('d/m/Y', strtotime($curso->fecha_inicio));
            $finFormateado = date('d/m/Y', strtotime($curso->fecha_fin));

            return back()
                ->withInput()
                ->with('error', "No puedes agendar fuera del periodo del curso. Este comprende del {$inicioFormateado} al {$finFormateado}.");
        }

        // 3. LOGICA REQUERIDA: Si la fecha ya pasó (es menor a hoy), forzar estado TERMINADA
        $estadoFinal = $request->estado;
        $hoy = Carbon::now()->toDateString();

        if ($fechaAsesoria < $hoy) {
            $estadoFinal = 'TERMINADA';
        }

        // Continuamos con los inserts usando el $estadoFinal controlado
        DB::beginTransaction();

        try {
            // PRIMER INSERT: Tabla 'evento'
            $id_evento = DB::table('evento')->insertGetId([
                'id_curso'      => $id_curso,
                'nombre_evento' => $request->nombre_evento,
                'fecha'         => Carbon::now()->toDateString(),
                'hora'          => Carbon::now()->toTimeString(),
                'tipo'          => 'asesoria'
            ]);

            // SEGUNDO INSERT: Tabla 'asesoria'
            DB::table('asesoria')->insert([
                'id_evento'      => $id_evento,
                'fecha_asesoria' => $request->fecha_asesoria,
                'hora_inicio'    => $request->hora_inicio,
                'hora_fin'       => $request->hora_fin,
                'lugar'          => $request->lugar,
                'estado'         => $estadoFinal, // <--- Aquí entra el estado automático
                'requiere_evidencia' => $requiereEvidencia
            ]);

            DB::commit();
            return redirect()->route('curso.eventos', $id_curso)->with('success', 'Asesoría agendada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al agendar: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id_evento)
    {
        $request->validate([
            'nombre_evento'  => 'required|string|max:255',
            'lugar'          => 'required|string|max:255',
            'fecha_asesoria' => 'required|date',
            'hora_inicio'    => 'required',
            'hora_fin'       => 'required',
            'estado'         => 'required|in:DISPONIBLE,EN_CURSO,TERMINADA,CANCELADA',
            'id_curso'       => 'required'
        ]);

        $id_curso = $request->id_curso;
        $curso = DB::table('curso')->where('id_curso', $id_curso)->first();

        if (!$curso) {
            return back()->withInput()->with('error', 'El curso especificado no existe.');
        }

        // 1. VALIDACIÓN: Rango de fechas del curso
        $fechaAsesoria = $request->fecha_asesoria;
        if ($fechaAsesoria < $curso->fecha_inicio || $fechaAsesoria > $curso->fecha_fin) {
            $inicioFormateado = date('d/m/Y', strtotime($curso->fecha_inicio));
            $finFormateado = date('d/m/Y', strtotime($curso->fecha_fin));

            return back()
                ->withInput()
                ->with('error', "No puedes mover la asesoría fuera del periodo del curso. Este comprende del {$inicioFormateado} al {$finFormateado}.");
        }

        // 2. NUEVA VALIDACIÓN: Asegurar que la hora de fin sea mayor a la de inicio
        // Convertimos las horas a formato tiempo para compararlas
        if (strtotime($request->hora_fin) <= strtotime($request->hora_inicio)) {
            return back()
                ->withInput()
                ->with('error', "La hora de fin debe ser posterior a la hora de inicio.");
        }

        // 3. Lógica de estado automático
        $estadoFinal = $request->estado;
        if ($request->fecha_asesoria < Carbon::now()->toDateString()) {
            $estadoFinal = 'TERMINADA';
        }

        $requiereEvidencia = $request->has('requiere_evidencia') ? 1 : 0;

        DB::beginTransaction();
        try {
            DB::table('evento')
                ->where('id_evento', $id_evento)
                ->update([
                    'nombre_evento' => $request->nombre_evento,
                ]);

            DB::table('asesoria')
                ->where('id_evento', $id_evento)
                ->update([
                    'fecha_asesoria'     => $request->fecha_asesoria,
                    'hora_inicio'        => $request->hora_inicio,
                    'hora_fin'           => $request->hora_fin,
                    'lugar'              => $request->lugar,
                    'estado'             => $estadoFinal,
                    'requiere_evidencia' => $requiereEvidencia
                ]);

            DB::commit();
            return redirect()->route('curso.eventos', $id_curso)->with('success', 'Asesoría actualizada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroy($id_evento)
    {
        try {
            DB::table('evento')->where('id_evento', $id_evento)->delete();
            return back()->with('success', '¡Actividad eliminada correctamente!');
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo eliminar: ' . $e->getMessage());
        }
    }

    public function unirse($id_evento)
    {
        $asesoria = DB::table('asesoria')->where('id_evento', $id_evento)->first();

        if (!$asesoria) {
            return back()->with('error', 'No se encontró la asesoría.');
        }

        $yaRegistrado = DB::table('asistencia_asesoria')
            ->where('id_asesoria', $asesoria->id_asesoria)
            ->where('correo_persona', Auth::user()->correo)
            ->exists();

        if ($yaRegistrado) {
            return back()->with('error', 'Ya estás registrado en esta asesoría.');
        }

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
        $asesoria = DB::table('asesoria')->where('id_evento', $id_evento)->first();

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
                'asesoria.estado',
                'asesoria.requiere_evidencia'
            )
            ->first();

        if (!$asesoria) {
            return redirect()->back()->with('error', 'Asesoría no encontrada.');
        }

        // Traemos el curso para mostrar su rango de fechas en la vista
        $curso = DB::table('curso')->where('id_curso', $asesoria->id_curso)->first();

        return view('AsesoriasViews.editarAsesoria', compact('asesoria', 'curso'));
    }


    public function agregarManualmente(Request $request, $id_asesoria)
    {
        $request->validate(['correo_persona' => 'required|email']);

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

    public function quitarAsistente($id_asistencia)
    {
        $asistencia = DB::table('asistencia_asesoria')
            ->where('id_asistencia', $id_asistencia)
            ->first();

        if (!$asistencia) {
            return back()->with('error', 'Registro no encontrado.');
        }

        DB::table('asistencia_asesoria')
            ->where('id_asistencia', $id_asistencia)
            ->delete();

        return back()->with('success', 'Alumno quitado de la lista correctamente.');
    }
}