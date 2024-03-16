<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'title',
        'description',
        'service',
        'appointment_date',
        'start_time',
        'end_time',
        'status',
    ];


    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'doctor_id');
    }

    public function medicalReports()
    {
        return $this->hasMany(MedicalReport::class, 'appointment_id');
    }
}
