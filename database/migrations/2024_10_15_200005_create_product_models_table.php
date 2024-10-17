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
        Schema::create('t_products', function (Blueprint $table) {
            $table->id();
            $table->string("code");
            $table->string("name");
            $table->double("price")->default(0);
            $table->double("priceSale")->default(0);
            $table->double("priceSaleMin")->default(0);
            $table->integer("depto");
            $table->integer("inventory")->default(0);
            $table->integer("unit")->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_products');
    }
};
