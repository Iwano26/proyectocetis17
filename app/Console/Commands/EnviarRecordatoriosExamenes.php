<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\RecordatorioExamenMailable;

class EnviarRecordatoriosExamenes extends Command
{
    protected $signature   = 'examenes:recordatorios';
    protected $description = 'Envía recordatorios de exámenes programados para mañana';

    public function handle()
    {
        $ahora = Carbon::now();
        $desde = $ahora->copy()->addHours(24)->subMinutes(1);
        $hasta = $ahora->copy()->addHours(24)->addMinutes(1);

        $examenes = DB::table('configuracion_examen')
            ->join('cuestionario', 'configuracion_examen.id_cuestionario', '=', 'cuestionario.id_cuestionario')
            ->join('evento', 'cuestionario.id_evento', '=', 'evento.id_evento')
            ->join('curso', 'evento.id_curso', '=', 'curso.id_curso')
            ->where('configuracion_examen.estado', 'PENDIENTE')
            ->whereRaw("CONCAT(configuracion_examen.fecha_examen, ' ', configuracion_examen.hora_inicio) BETWEEN ? AND ?", [
                $desde->toDateTimeString(),
                $hasta->toDateTimeString()
            ])
            ->select(
                'configuracion_examen.id_config',
                'configuracion_examen.fecha_examen',
                'configuracion_examen.hora_inicio',
                'configuracion_examen.hora_fin',
                'configuracion_examen.oportunidades',
                'cuestionario.id_cuestionario',
                'cuestionario.nombre_cuestionario',
                'evento.id_evento',
                'evento.id_curso',
                'curso.nombre_curso'
            )
            ->get();

        if ($examenes->isEmpty()) {
            return 0;
        }

        $totalEnviados = 0;

        foreach ($examenes as $examen) {

            // Verificar que no se haya mandado ya el recordatorio
            $yaEnviado = DB::table('notificacion')
                ->where('id_evento', $examen->id_evento)
                ->where('mensaje', 'LIKE', '%recordatorio examen%')
                ->exists();

            if ($yaEnviado) continue;

            // Alumnos inscritos al curso
            $alumnos = DB::table('inscripcion')
                ->join('persona', 'inscripcion.correo_estudiante', '=', 'persona.correo')
                ->where('inscripcion.id_curso', $examen->id_curso)
                ->select('persona.correo', 'persona.nombre', 'persona.apellidoPa')
                ->get();

            foreach ($alumnos as $alumno) {
                try {
                    Mail::to($alumno->correo)->send(new RecordatorioExamenMailable(
                        $alumno->nombre . ' ' . $alumno->apellidoPa,
                        $examen->nombre_cuestionario,
                        $examen->nombre_curso,
                        $examen->fecha_examen,
                        $examen->hora_inicio,
                        $examen->hora_fin,
                        $examen->oportunidades,
                        $examen->id_curso
                    ));

                    DB::table('notificacion')->insert([
                        'id_evento'      => $examen->id_evento,
                        'correo_persona' => $alumno->correo,
                        'mensaje'        => 'recordatorio examen: "' . $examen->nombre_cuestionario . '" es mañana.',
                        'leido'          => 0,
                        'fecha_envio'    => now(),
                    ]);

                    $totalEnviados++;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Error recordatorio examen: ' . $e->getMessage());
                }
            }
        }

        $this->info("Recordatorios de examen enviados: {$totalEnviados}");
        return 0;
    }
}