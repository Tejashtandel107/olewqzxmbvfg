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
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('company_id');
            $table->unsignedInteger('user_id'); 
            $table->string('company_name');
            $table->string('company_type',50)->comment('ordinaria,semplificata,professionista,forfettario');
            $table->string('vat_tax')->nullable();
            $table->string('business_type',50)->comment('commercio,servizio,ristorante,professionista,edile,produzione')->nullable();
            $table->unsignedTinyInteger('created_by')->default(0);
            $table->softDeletes();
            $table->timestamps();    
            
            $table->index('user_id','user_id');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
};
