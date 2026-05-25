<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\RecordatorioAsesoriaMailable;

class EnviarRecordatoriosAsesorias extends Command
{
    protected $signature   = 'asesorias:recordatorios';
    protected $description = 'Envía recordatorios de asesorías programadas para mañana';

    public function handle()
    {
        $ahora = Carbon::now();
        $desde = $ahora->copy()->addHours(24)->subMinutes(1);
        $hasta = $ahora->copy()->addHours(24)->addMinutes(1);

        $asesorias = DB::table('asesoria')
            ->join('evento', 'asesoria.id_evento', '=', 'evento.id_evento')
            ->join('curso', 'evento.id_curso', '=', 'curso.id_curso')
            ->where('asesoria.estado', 'DISPONIBLE')
            ->whereRaw("CONCAT(asesoria.fecha_asesoria, ' ', asesoria.hora_inicio) BETWEEN ? AND ?", [
                $desde->toDateTimeString(),
                $hasta->toDateTimeString()
            ])
            ->select(
                'asesoria.id_asesoria',
                'asesoria.lugar',
                'asesoria.fecha_asesoria',
                'asesoria.hora_inicio',
                'asesoria.hora_fin',
                'evento.nombre_evento',
                'evento.id_evento',
                'evento.id_curso',
                'curso.nombre_curso'
            )
            ->get();

        if ($asesorias->isEmpty()) {
            return 0;
        }

        $totalEnviados = 0;

        foreach ($asesorias as $asesoria) {

            // Verificar que no se haya mandado ya el recordatorio
            $yaEnviado = DB::table('notificacion')
                ->where('id_evento', $asesoria->id_evento)
                ->where('mensaje', 'LIKE', '%recordatorio%')
                ->exists();

            if ($yaEnviado) continue;

            $alumnos = DB::table('asistencia_asesoria')
                ->join('persona', 'asistencia_asesoria.correo_persona', '=', 'persona.correo')
                ->where('asistencia_asesoria.id_asesoria', $asesoria->id_asesoria)
                ->select('persona.correo', 'persona.nombre', 'persona.apellidoPa')
                ->get();

            foreach ($alumnos as $alumno) {
                try {
                    Mail::to($alumno->correo)->send(new RecordatorioAsesoriaMailable(
                        $alumno->nombre . ' ' . $alumno->apellidoPa,
                        $asesoria->nombre_evento,
                        $asesoria->nombre_curso,
                        $asesoria->lugar,
                        $asesoria->fecha_asesoria,
                        $asesoria->hora_inicio,
                        $asesoria->hora_fin,
                        $asesoria->id_curso
                    ));

                    DB::table('notificacion')->insert([
                        'id_evento'      => $asesoria->id_evento,
                        'correo_persona' => $alumno->correo,
                        'mensaje'        => 'recordatorio: Tu asesoría "' . $asesoria->nombre_evento . '" es mañana.',
                        'leido'          => 0,
                        'fecha_envio'    => now(),
                    ]);

                    $totalEnviados++;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Error recordatorio: ' . $e->getMessage());
                }
            }
        }

        $this->info("Recordatorios enviados: {$totalEnviados}");
        return 0;
    }
}