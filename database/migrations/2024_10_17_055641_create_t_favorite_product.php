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
        Schema::create('t_favorite_product', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("id_product")->unsigned();
            $table->timestamps();
        });

        Schema::table('t_favorite_product', function ($table) {
            $table->foreign('id_product')->references('id')->on('t_products')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_favorite_product');
    }
};
