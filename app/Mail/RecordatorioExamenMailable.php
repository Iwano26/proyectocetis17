<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecordatorioExamenMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $nombreAlumno;
    public $nombreExamen;
    public $nombreCurso;
    public $fechaExamen;
    public $horaInicio;
    public $horaFin;
    public $oportunidades;
    public $idCurso;

    public function __construct($nombreAlumno, $nombreExamen, $nombreCurso, $fechaExamen, $horaInicio, $horaFin, $oportunidades, $idCurso)
    {
        $this->nombreAlumno   = $nombreAlumno;
        $this->nombreExamen   = $nombreExamen;
        $this->nombreCurso    = $nombreCurso;
        $this->fechaExamen    = $fechaExamen;
        $this->horaInicio     = $horaInicio;
        $this->horaFin        = $horaFin;
        $this->oportunidades  = $oportunidades;
        $this->idCurso        = $idCurso;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                config('mail.from.name')
            ),
            subject: '⏰ Recordatorio de Examen Mañana - ' . $this->nombreCurso,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.recordatorio-examen',
            with: [
                'nombreAlumno'  => $this->nombreAlumno,
                'nombreExamen'  => $this->nombreExamen,
                'nombreCurso'   => $this->nombreCurso,
                'fechaExamen'   => $this->fechaExamen,
                'horaInicio'    => $this->horaInicio,
                'horaFin'       => $this->horaFin,
                'oportunidades' => $this->oportunidades,
                'idCurso'       => $this->idCurso,
            ]
        );
    }

    public function attachments(): array { return []; }
}