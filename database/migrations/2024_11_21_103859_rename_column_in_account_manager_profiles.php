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
        Schema::table('account_manager_profiles', function (Blueprint $table) {
            $table->renameColumn('price_ordinaria', 'price_righe_ordinaria');
            $table->renameColumn('price_semplificata', 'price_righe_semplificata');
            $table->renameColumn('price_corrispettivi_semplificata', 'price_righe_corrispettivi_semplificata');
            $table->renameColumn('price_paghe_semplificata', 'price_righe_paghe_semplificata');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_manager_profiles', function (Blueprint $table) {
            $table->renameColumn('price_righe_ordinaria', 'price_ordinaria');
            $table->renameColumn('price_righe_semplificata', 'price_semplificata');
            $table->renameColumn('price_righe_corrispettivi_semplificata', 'price_corrispettivi_semplificata');
            $table->renameColumn('price_righe_paghe_semplificata', 'price_paghe_semplificata');
        });
    }
};
