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
        Schema::create('account_manager_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('pricing_type')->nullable()->comment('Costo Stipendio più bonus,Costo per Righe e Registrazioni');
            $table->decimal('price',10,2)->default(0.00)->comment('Costo Stipendio');
            $table->decimal('price_ordinaria',10,3)->default(0.00)->comment('Price For Ordinaria');
            $table->decimal('price_semplificata',10,3)->default(0.00)->comment('Price For Semplificata');
            $table->unsignedInteger('bonus_target')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_manager_profiles');
    }
};
