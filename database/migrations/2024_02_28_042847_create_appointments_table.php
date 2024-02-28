<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()->constrained('doctors', 'doctor_id')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('appointment_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['Planifié', 'Confirmé', 'Annulé','Fermé'])->default('Planifié');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
