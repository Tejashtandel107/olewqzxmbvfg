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
            $table->decimal('total_cost',10,2)->default(0.00)->after('total_lines');
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
            $table->dropColumn('total_cost');
        });
    }
};
