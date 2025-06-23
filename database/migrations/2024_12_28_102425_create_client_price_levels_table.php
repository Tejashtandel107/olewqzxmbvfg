<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_price_levels', function (Blueprint $table) {
            $table->increments('price_level_id');
            $table->text('title');
            $table->bigInteger('min');
            $table->bigInteger('max');
            $table->decimal('price_ordinaria',10,2)->comment('Price For Ordinaria');
            $table->decimal('price_semplificata',10,2)->comment('Price For Semplificata');
            $table->decimal('price_corrispettivi_semplificata',10,2)->comment('Price For Corrispettivi(Semplificata)');
            $table->decimal('price_paghe_semplificata',10,2)->comment('Price For Paghe(Semplificata)');
            $table->integer('order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_price_levels');
    }
};