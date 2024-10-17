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
        Schema::create('t_inventories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("id_product")->unsigned();
            $table->double("stock")->default(0)->nullable();
            $table->double("stockMax")->default(0)->nullable();
            $table->double("stockMin")->default(0)->nullable();
            $table->timestamps();
        });

        Schema::table('t_inventories', function ($table) {
            $table->foreign('id_product')->references('id')->on('t_products')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_inventories');
    }
};
