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
        Schema::create('client_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('pricing_type')->nullable()->comment('Prezzo Fisso,Prezzo Fisso con Pietra Miliare,Per Righe,Per Registrazioni');
            $table->decimal('price',10,2)->default(0.00)->comment('Prezzo Fisso');
            $table->decimal('price_ordinaria',10,2)->default(0.00)->comment('Price For Ordinaria');
            $table->decimal('price_semplificata',10,2)->default(0.00)->comment('Price For Semplificata');
            $table->decimal('price_corrispettivi',10,2)->default(0.00)->comment('Price For Corrispettivi(Semplificata)');
            $table->unsignedInteger('milestone')->default(0)->comment("Pietra Miliare");
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
        Schema::dropIfExists('client_profiles');
    }
};
