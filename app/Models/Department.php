<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'chef_id'];

    public function chefDepartment()
    {
        return $this->belongsTo(Doctor::class, 'chef_id');
    }

}
