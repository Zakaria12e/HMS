<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medical_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('diagnosis');
            $table->text('medications');
            $table->text('recommendations');
            $table->text('symptoms');
            $table->foreignId('appointment_id')->constrained('appointments', 'id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_reports');
    }
};
