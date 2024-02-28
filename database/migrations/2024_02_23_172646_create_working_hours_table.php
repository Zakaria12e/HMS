<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
    {
        Schema::create('working_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors', 'doctor_id')->onDelete('cascade');
            $table->integer('monday')->default(1);
            $table->integer('tuesday')->default(1);
            $table->integer('wednesday')->default(1);
            $table->integer('thursday')->default(1);
            $table->integer('friday')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('working_hours');
    }
};
