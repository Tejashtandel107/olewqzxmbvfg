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
            $table->decimal('price_righe_am_ordinaria', 10, 3)->default(0.00)->after('price_righe_paghe_semplificata');
            $table->decimal('price_righe_am_semplificata', 10, 3)->default(0.00)->after('price_righe_am_ordinaria');
            $table->decimal('price_righe_am_corrispettivi_semplificata', 10, 3)->default(0.00)->after('price_righe_am_semplificata');
            $table->decimal('price_righe_am_paghe_semplificata', 10, 3)->default(0.00)->after('price_righe_am_corrispettivi_semplificata');
        });

        Schema::table('line_expenses_monthly', function (Blueprint $table) {
            $table->decimal('price_righe_am_ordinaria', 10, 3)->default(0.00)->after('price_righe_paghe_semplificata');
            $table->decimal('price_righe_am_semplificata', 10, 3)->default(0.00)->after('price_righe_am_ordinaria');
            $table->decimal('price_righe_am_corrispettivi_semplificata', 10, 3)->default(0.00)->after('price_righe_am_semplificata');
            $table->decimal('price_righe_am_paghe_semplificata', 10, 3)->default(0.00)->after('price_righe_am_corrispettivi_semplificata');
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
            $table->dropColumn([
                'price_righe_am_ordinaria',
                'price_righe_am_semplificata',
                'price_righe_am_corrispettivi_semplificata',
                'price_righe_am_paghe_semplificata'
            ]);
        });

        Schema::table('line_expenses_monthly', function (Blueprint $table) {
            $table->dropColumn([
                'price_righe_am_ordinaria',
                'price_righe_am_semplificata',
                'price_righe_am_corrispettivi_semplificata',
                'price_righe_am_paghe_semplificata'
            ]);
        });
    }
};
