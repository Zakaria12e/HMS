<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'chef_id'];



    public function chef()
    {

        return $this->belongsTo(User::class, 'chef_id');
    }


    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'department_id');
    }


}
