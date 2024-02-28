<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'Date',
        'DueDate',
        'DescriptionOfServices',
        'TotalAmount',
        'appointment_id',
        'status',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public function doctor()
    {
        return $this->appointment->doctor;
    }

    public function patient()
    {
        return $this->appointment->patient;
    }
}
