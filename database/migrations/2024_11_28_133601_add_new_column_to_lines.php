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
        Schema::table('lines', function (Blueprint $table) {
            $table->unsignedInteger('total_passive_lines')->after('petty_cash_book_registrations')->default(0);
            $table->unsignedInteger('total_active_lines')->after('total_passive_lines')->default(0);
            $table->unsignedInteger('total_corrispetivvi_lines')->after('total_active_lines')->default(0);
            $table->unsignedInteger('total_paghe_lines')->after('total_corrispetivvi_lines')->default(0);
            $table->unsignedInteger('total_prima_nota_lines')->after('total_paghe_lines')->default(0);
            $table->string('line_type',50)->nullable()->after('account_manager_id');
            $table->string('line_pricing_type',50)->nullable()->after('line_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lines', function (Blueprint $table) {
            $table->dropColumn('total_passive_lines');
            $table->dropColumn('total_active_lines');
            $table->dropColumn('total_corrispetivvi_lines');
            $table->dropColumn('total_paghe_lines');
            $table->dropColumn('total_prima_nota_lines');
            $table->dropColumn('line_type');
            $table->dropColumn('line_pricing_type');
        });
    }
};
