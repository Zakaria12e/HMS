<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->date('Date');
            $table->date('DueDate');
            $table->foreignId('appointment_id')->constrained('appointments', 'id')->onDelete('cascade');
            $table->text('DescriptionOfServices');
            $table->decimal('TotalAmount', 10, 2);
            $table->enum('status', ['Payé', 'En attente'])->default('En attente');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
