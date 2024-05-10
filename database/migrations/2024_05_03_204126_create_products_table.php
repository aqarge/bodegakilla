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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('pro_types_id')->constrained('pro_types')->cascadeOnDelete();
            $table->string('name_pro');
            $table->text('descrip_pro')->nullable();
            $table->decimal('price_pro', 10,2);
            $table->integer('stock_pro');
            $table->date('expiration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
