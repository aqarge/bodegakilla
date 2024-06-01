<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('boxes', function (Blueprint $table) {
            $table->id();
            $table->date('opening')->unique();
            $table->decimal('income', 10,2)->default(0); //ingresos = sum(ingresos de transacciones)
            $table->decimal('expenses', 10,2)->default(0); //egresos = sum(egresos de transacciones)
            $table->decimal('revenue', 10,2)->default(0); // ganancia = sum(ingresos de transacciones) - sum(egresos de transacciones)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boxes');
    }
};
