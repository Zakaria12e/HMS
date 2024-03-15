<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'diagnosis',
        'medications',
        'recommendations',
        'symptoms',
        'appointment_id',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

}
