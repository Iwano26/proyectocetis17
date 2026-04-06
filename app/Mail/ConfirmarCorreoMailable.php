<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmarCorreoMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $nombreCompleto;
    public $correo;

    public function __construct($nombreCompleto, $correo)
    {
        $this->nombreCompleto = $nombreCompleto;
        $this->correo = $correo;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            // Usamos el remitente de tu .env
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Confirma tu cuenta - Academia de Karate',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'RegisterViews.mensajeconfirmarcorreo',
            with: [
                'nombreCompleto' => $this->nombreCompleto,
                'correo' => $this->correo,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}