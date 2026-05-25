<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecordatorioAsesoriaMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $nombreAlumno;
    public $nombreAsesoria;
    public $nombreCurso;
    public $lugar;
    public $fechaAsesoria;
    public $horaInicio;
    public $horaFin;
    public $idCurso;

    public function __construct($nombreAlumno, $nombreAsesoria, $nombreCurso, $lugar, $fechaAsesoria, $horaInicio, $horaFin, $idCurso)
    {
        $this->nombreAlumno   = $nombreAlumno;
        $this->nombreAsesoria = $nombreAsesoria;
        $this->nombreCurso    = $nombreCurso;
        $this->lugar          = $lugar;
        $this->fechaAsesoria  = $fechaAsesoria;
        $this->horaInicio     = $horaInicio;
        $this->horaFin        = $horaFin;
        $this->idCurso        = $idCurso;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                config('mail.from.name')
            ),
            subject: '⏰ Recordatorio de Asesoría Mañana - ' . $this->nombreCurso,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.recordatorio-asesoria',
            with: [
                'nombreAlumno'   => $this->nombreAlumno,
                'nombreAsesoria' => $this->nombreAsesoria,
                'nombreCurso'    => $this->nombreCurso,
                'lugar'          => $this->lugar,
                'fechaAsesoria'  => $this->fechaAsesoria,
                'horaInicio'     => $this->horaInicio,
                'horaFin'        => $this->horaFin,
                'idCurso'        => $this->idCurso,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}