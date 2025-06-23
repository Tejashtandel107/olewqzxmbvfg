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
        Schema::table('line_income_monthly_pricings', function (Blueprint $table) {
            $table->decimal('price_paghe_semplificata',10,2)
                                            ->default(0.00)
                                            ->after('price_corrispettivi') 
                                            ->comment('Price For Paghe(Semplificata)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('line_income_monthly_pricings', function (Blueprint $table) {
            $table->dropColumn('price_paghe_semplificata');
        });
    }
};
