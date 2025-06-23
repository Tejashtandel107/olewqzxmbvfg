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
        Schema::table('line_expense_monthly_pricings', function (Blueprint $table) {
            $table->unsignedInteger('total_corrispettivi_lines_semplificata')->default(0)->after('total_lines_semplificata');
            $table->unsignedInteger('total_paghe_lines_semplificata')->default(0)->after('total_corrispettivi_lines_semplificata');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('line_expense_monthly_pricings', function (Blueprint $table) {
            $table->dropColumn('total_corrispettivi_lines_semplificata');
            $table->dropColumn('total_paghe_lines_semplificata');
        });
    }
};
