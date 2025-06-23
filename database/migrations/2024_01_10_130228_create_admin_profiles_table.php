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
        Schema::create('admin_profiles', function (Blueprint $table) {
            $table->id();
            $table->boolean('notify_on_client_create')->default(1);
            $table->boolean('notify_on_client_update')->default(1);
            $table->boolean('notify_on_client_delete')->default(1);
            $table->boolean('notify_on_company_create')->default(1);
            $table->boolean('notify_on_company_update')->default(1);
            $table->boolean('notify_on_company_delete')->default(1);
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
        Schema::dropIfExists('admin_profiles');
    }
};
