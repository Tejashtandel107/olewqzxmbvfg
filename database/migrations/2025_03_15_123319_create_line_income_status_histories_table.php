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
        Schema::create('line_income_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('line_income_monthly_id');
            $table->integer('user_id')->unsigned();

            $table->foreign('line_income_monthly_id')
                ->references('id')
                ->on('line_incomes_monthly');
      
            $table->foreign('user_id')
                    ->references('user_id')
                    ->on('users');
      
            $table->string('status');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('line_income_monthly_id','line_income_monthly_id');
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
        Schema::dropIfExists('line_income_status_histories');
    }
};
