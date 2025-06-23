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
            $table->renameColumn('price_corrispettivi', 'price_corrispettivi_semplificata');
        });
        Schema::table('client_profiles', function (Blueprint $table) {
            $table->renameColumn('price_corrispettivi', 'price_corrispettivi_semplificata');
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
            $table->renameColumn('price_corrispettivi_semplificata', 'price_corrispettivi');
        });
        Schema::table('client_profiles', function (Blueprint $table) {
            $table->renameColumn('price_corrispettivi_semplificata', 'price_corrispettivi');
        });
    }
};
