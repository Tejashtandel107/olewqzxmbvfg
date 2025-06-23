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
            $table->decimal('total_bonus_ordinaria',10,2)->default(0.00)->after('total_paghe_lines_semplificata');
            $table->decimal('total_bonus_semplificata',10,2)->default(0.00)->after('total_bonus_ordinaria');
            $table->decimal('total_bonus_corrispettivi_semplificata',10,2)->default(0.00)->after('total_bonus_semplificata');
            $table->decimal('total_bonus_paghe_semplificata',10,2)->default(0.00)->after('total_bonus_corrispettivi_semplificata');
            $table->decimal('total_bonus',10,2)->default(0.00)->after('total_bonus_paghe_semplificata')->comment('Total Bonus');
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
            $table->dropColumn('total_bonus_ordinaria');
            $table->dropColumn('total_bonus_semplificata');
            $table->dropColumn('total_bonus_corrispettivi_semplificata');
            $table->dropColumn('total_bonus_paghe_semplificata');
            $table->dropColumn('total_bonus');
        });
    }
};
