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
        Schema::create('debtproducts', function (Blueprint $table) {
            $table->id();
           $table->unsignedBigInteger('debt_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->nullable()->default(1);
            $table->decimal('amount_debt', 10, 2)->default(0);




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debtproducts');
    }
};
