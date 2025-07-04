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
        Schema::table('client_profiles', function (Blueprint $table) {
            $table->decimal('price_paghe_semplificata',10,2)
                                            ->default(0.00)
                                            ->after('price_corrispettivi') 
                                            ->comment('Prezzo For Paghe(Semplificata)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_profiles', function (Blueprint $table) {
            $table->dropColumn('price_paghe_semplificata');
        });
    }
};
