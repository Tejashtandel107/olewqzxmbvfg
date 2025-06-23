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
            $table->dropColumn('price_registrazioni_ordinaria');
            $table->dropColumn('price_registrazioni_semplificata');
            $table->dropColumn('price_registrazioni_corrispettivi_semplificata');
            $table->dropColumn('price_registrazioni_paghe_semplificata');
        });
        Schema::table('account_manager_profiles', function (Blueprint $table) {
            $table->dropColumn('price_registrazioni_ordinaria');
            $table->dropColumn('price_registrazioni_semplificata');
            $table->dropColumn('price_registrazioni_corrispettivi_semplificata');
            $table->dropColumn('price_registrazioni_paghe_semplificata');
        });
        Schema::table('line_expenses_monthly', function (Blueprint $table) {
            $table->dropColumn('price_registrazioni_ordinaria');
            $table->dropColumn('price_registrazioni_semplificata');
            $table->dropColumn('price_registrazioni_corrispettivi_semplificata');
            $table->dropColumn('price_registrazioni_paghe_semplificata');
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
            $table->decimal('price_registrazioni_ordinaria',10,3)->default(0.00)->after('price_righe_paghe_semplificata')->comment('Price For ordinaria');
            $table->decimal('price_registrazioni_semplificata',10,3)->default(0.00)->after('price_registrazioni_ordinaria')->comment('Price For semplificata');
            $table->decimal('price_registrazioni_corrispettivi_semplificata',10,3)->default(0.00)->after('price_registrazioni_semplificata')->comment('Price For Corrispettivi(Semplificata)');
            $table->decimal('price_registrazioni_paghe_semplificata',10,3)->default(0.00)->after('price_registrazioni_corrispettivi_semplificata') ->comment('Price For Paghe(Semplificata)');
        });
        Schema::table('account_manager_profiles', function (Blueprint $table) {
            $table->decimal('price_registrazioni_ordinaria',10,3)->default(0.00)->after('price_righe_paghe_semplificata')->comment('Price For ordinaria');
            $table->decimal('price_registrazioni_semplificata',10,3)->default(0.00)->after('price_registrazioni_ordinaria')->comment('Price For semplificata');
            $table->decimal('price_registrazioni_corrispettivi_semplificata',10,3)->default(0.00)->after('price_registrazioni_semplificata')->comment('Price For Corrispettivi(Semplificata)');
            $table->decimal('price_registrazioni_paghe_semplificata',10,3)->default(0.00)->after('price_registrazioni_corrispettivi_semplificata')->comment('Price For Paghe(Semplificata)');
        });
        Schema::table('line_expenses_monthly', function (Blueprint $table) {
            $table->decimal('price_registrazioni_ordinaria',10,3)->default(0.00)->after('price_righe_paghe_semplificata')->comment('Price For ordinaria');
            $table->decimal('price_registrazioni_semplificata',10,3)->default(0.00)->after('price_registrazioni_ordinaria')->comment('Price For semplificata');
            $table->decimal('price_registrazioni_corrispettivi_semplificata',10,3)->default(0.00)->after('price_registrazioni_semplificata')->comment('Price For Corrispettivi(Semplificata)');
            $table->decimal('price_registrazioni_paghe_semplificata',10,3)->default(0.00)->after('price_registrazioni_corrispettivi_semplificata')->comment('Price For Paghe(Semplificata)');
        });
    }
};
