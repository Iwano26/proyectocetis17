<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class cambiarcontrasenniaMailable extends Mailable
{
    use Queueable, SerializesModels;
    
    public $nombreCompleto;
    public $token;

    public function __construct($nombre, $token)
    {
        // Asignamos las variables que vienen del controlador
        $this->nombreCompleto = $nombre;
        $this->token = $token;
    }

    /**
     * Definición del Sobre (Remitente y Asunto)
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address'), 
                config('mail.from.name')
            ),
            subject: 'Recuperación de Contraseña - Asesorías CETIS 17',
        );
    }

    /**
     * Definición del Contenido (Vista y Variables)
     */
    public function content(): Content
    {
        return new Content(
            view: 'ResetPasswordViews.mensajecambiarcontrasennia',
            with: [
                'nombreCompleto' => $this->nombreCompleto,
                'token' => $this->token,
            ]
        );
    }

    /**
     * Adjuntos (Vacío por ahora)
     */
    public function attachments(): array
    {
        return [];
    }
}