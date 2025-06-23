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
        Schema::table('line_expenses_monthly', function (Blueprint $table) {
            $table->boolean('sync_total')->after('total_lines')->comment('1 or 0')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('line_expenses_monthly', function (Blueprint $table) {
            $table->dropColumn('sync_total');
        });
    }
};
