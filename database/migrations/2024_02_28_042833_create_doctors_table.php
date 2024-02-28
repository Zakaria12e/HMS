<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('users', 'id')->onDelete('cascade');
            $table->string('specialization');
            $table->string('contact_number');
            $table->decimal('salary', 10, 2)->nullable();
            $table->foreignId('department_id')->constrained('departments', 'id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctors');
    }
};
