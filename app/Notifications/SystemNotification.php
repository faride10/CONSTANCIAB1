<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SystemNotification extends Notification
{
    use Queueable;

    public $titulo;
    public $mensaje;
    public $tipo; 
    public function __construct($titulo, $mensaje, $tipo)
    {
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
        $this->tipo = $tipo;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'titulo' => (string)$this->titulo,
            'mensaje' => (string)$this->mensaje,
            'tipo' => (string)$this->tipo,
        ];
    }
}