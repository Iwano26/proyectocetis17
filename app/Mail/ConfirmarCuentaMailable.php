<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmarCuentaMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $token;

    public function __construct($nombre, $token)
    {
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Bienvenido - Confirma tu cuenta de Asesorías CETIS 17',
        );
    }

    public function content(): Content
{
    return new Content(
        // Cambiamos la ruta para que coincida con donde guardaste el archivo
        view: 'RegisterViews.mensajeconfirmarcorreo', 
        with: [
            'nombre' => $this->nombre,
            'token' => $this->token,
        ]
    );
}
}