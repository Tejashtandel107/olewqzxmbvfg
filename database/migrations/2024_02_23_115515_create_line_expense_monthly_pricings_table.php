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
        Schema::create('line_expense_monthly_pricings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->date('pricing_date');
            $table->string('pricing_type')->comment('Costo Stipendio più bonus,Costo per Righe e Registrazioni');
            $table->decimal('price',10,2)->default(0.00)->comment('Costo Stipendio');
            $table->decimal('price_ordinaria',10,3)->default(0.00)->comment('Price For Ordinaria');
            $table->decimal('price_semplificata',10,3)->default(0.00)->comment('Price For Semplificata');
            $table->unsignedInteger('bonus_target')->default(0);
            $table->unsignedInteger('total_lines_ordinaria')->default(0);
            $table->unsignedInteger('total_lines_semplificata')->default(0);
            $table->unsignedInteger('total_lines')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'pricing_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('line_expense_monthly_pricings');
    }
};
