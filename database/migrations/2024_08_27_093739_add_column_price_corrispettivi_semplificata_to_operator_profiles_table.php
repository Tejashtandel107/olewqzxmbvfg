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
        Schema::table('operator_profiles', function (Blueprint $table) {
            $table->decimal('price_corrispettivi_semplificata',10,3)
                                                ->default(0.00)
                                                ->after('price_semplificata') 
                                                ->comment('Costo Bonus For Corrispettivi(Semplificata)');
            $table->decimal('price_paghe_semplificata',10,3)
                                                ->default(0.00)
                                                ->after('price_corrispettivi_semplificata') 
                                                ->comment('Costo Bonus For Paghe(Semplificata)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operator_profiles', function (Blueprint $table) {
            $table->dropColumn('price_corrispettivi_semplificata');
            $table->dropColumn('price_paghe_semplificata');

            
        });
    }
};
