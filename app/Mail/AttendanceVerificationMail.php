<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AttendanceVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $token;

    public function __construct($student, $token)
    {
        $this->student = $student;
        $this->token = $token;
    }

    public function build()
    {
        
        $ip_de_tu_compu = '192.168.0.38'; 

        $link = "http://" . $ip_de_tu_compu . "/api/attendance/confirm/" . $this->token;

        return $this->subject('Confirma tu asistencia - ConstanciaB')
                    ->view('emails.attendance_confirmation')
                    ->with([
                        'link' => $link,
                     
                        'name' => $this->student->NOMBRE 
                    ]);
    }
}