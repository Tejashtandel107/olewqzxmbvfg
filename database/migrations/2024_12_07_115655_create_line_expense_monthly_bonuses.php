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
        Schema::create('line_expense_monthly_bonuses', function (Blueprint $table) {
            $table->increments('line_bonus_id');
            $table->unsignedInteger('source_user_id')->default(0)->comment('The user who is the source of this bonus, if applicable.');
            $table->unsignedInteger('user_id')->default(0)->comment('The user receiving the bonus');
            $table->date('pricing_date');
            $table->unsignedInteger('total_lines_ordinaria')->default(0);
            $table->unsignedInteger('total_lines_semplificata')->default(0);
            $table->unsignedInteger('total_corrispettivi_lines_semplificata')->default(0);
            $table->unsignedInteger('total_paghe_lines_semplificata')->default(0);
            $table->decimal('total_bonus_ordinaria',10,2)->default(0.00);
            $table->decimal('total_bonus_semplificata',10,2)->default(0.00);
            $table->decimal('total_bonus_corrispettivi_semplificata',10,2)->default(0.00);
            $table->decimal('total_bonus_paghe_semplificata',10,2)->default(0.00);
            $table->decimal('total_bonus',10,2)->default(0.00); 
            $table->unsignedInteger('total_lines')->default(0);
            $table->timestamps();

            $table->index('source_user_id','source_user_id');
            $table->index('user_id','user_id');
            $table->index('pricing_date','pricing_date');
            $table->unique(['source_user_id', 'user_id','pricing_date'],'source_user_id_user_id_pricing_date_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('line_monthly_bonuses');
    }
};
