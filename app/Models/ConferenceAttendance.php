<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConferenceAttendance extends Model
{
    use HasFactory;

    protected $table = 'conference_attendances';

    protected $fillable = [
        'conference_id',
        'student_control_number',
        'verification_token',
        'token_expires_at',
        'status'
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_control_number', 'control_number');
    }
}