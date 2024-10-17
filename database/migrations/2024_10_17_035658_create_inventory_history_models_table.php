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
        Schema::create('t_inventory_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("id_inventory")->unsigned();
            $table->double("stock")->default(0)->nullable();
            $table->integer("type")->comment("1 - Entrada, 2 - Salida, 3 - Ajuste");
            $table->timestamps();
        });

        Schema::table('t_inventory_histories', function ($table) {
            $table->foreign('id_inventory')->references('id')->on('t_inventories')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_history_models');
    }
};
