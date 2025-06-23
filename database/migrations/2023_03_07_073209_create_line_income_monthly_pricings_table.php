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
        Schema::create('line_income_monthly_pricings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->date('pricing_date');
            $table->string('pricing_type')->comment('Prezzo Fisso,Prezzo Fisso con Pietra Miliare,Per Righe,Per Registrazioni');
            $table->decimal('price',10,2)->default(0.00)->comment('Prezzo Fisso');
            $table->decimal('price_ordinaria',10,2)->default(0.00)->comment('Price For Ordinaria');
            $table->decimal('price_semplificata',10,2)->default(0.00)->comment('Price For Semplificata');
            $table->decimal('price_corrispettivi',10,2)->default(0.00)->comment('Price For Corrispettivi(Semplificata)');
            $table->unsignedInteger('milestone')->default(0)->comment("Pietra Miliare");
            $table->unsignedInteger('total_corrispettivi_lines_semplificata')->default(0);
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
        Schema::dropIfExists('line_income_monthly_pricings');
    }
};
