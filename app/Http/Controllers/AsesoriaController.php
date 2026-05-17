<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
}