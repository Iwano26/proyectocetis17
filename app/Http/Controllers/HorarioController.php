<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HorarioController extends Controller
{
    protected $tableName = 'curso_horarios';

    /**
     * Guarda los horarios de un curso.
     * Se espera que 'dia', 'hora_inicio' y 'hora_fin' sean arreglos.
     */
    public function store(Request $request)
    {
        // 1. Validar que vengan los datos necesarios
        $request->validate([
            'id_curso'    => 'required|integer',
            'dia'         => 'required|array',
            'dia.*'       => 'string',
            'hora_inicio' => 'required|array',
            'hora_fin'    => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            // 2. Ciclo para insertar cada día seleccionado
            foreach ($request->dia as $key => $valorDia) {
                DB::table($this->tableName)->insert([
                    'id_curso'    => $request->id_curso,
                    'dia_semana'  => $valorDia,
                    'hora_inicio' => $request->hora_inicio[$key],
                    'hora_fin'    => $request->hora_fin[$key],
                ]);
            }

            DB::commit();
            return back()->with('mensaje', 'Horarios asignados correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar horarios: ' . $e->getMessage());
            return back()->with('mensaje', 'Error al guardar los horarios.');
        }
    }

    /**
     * Elimina un horario específico.
     */
    public function destroy($id)
    {
        try {
            DB::table($this->tableName)->where('id_horario', $id)->delete();
            return back()->with('mensaje', 'Horario eliminado.');
        } catch (\Exception $e) {
            return back()->with('mensaje', 'No se pudo eliminar el horario.');
        }
    }
}