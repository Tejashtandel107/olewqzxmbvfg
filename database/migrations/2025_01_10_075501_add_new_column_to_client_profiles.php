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
        Schema::table('client_profiles', function (Blueprint $table) {
            $table->date('activation_date')->after('milestone')->nullable();
            $table->integer('price_level_id')->after('activation_date')->nullable();
            $table->integer('base_price_level_id')->after('price_level_id')->nullable();
            $table->string('vat_number',50)->after('base_price_level_id')->nullable();
            $table->string('address')->nullable()->after('vat_number');
            $table->string('city',50)->nullable()->after('address');
            $table->string('province',50)->nullable()->after('city');
            $table->string('postal_code',50)->nullable()->after('province');
            $table->unsignedInteger('country_id')->nullable()->after('postal_code');
            $table->string('additional_emails')->nullable()->after('country_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_profiles', function (Blueprint $table) {
            $table->dropColumn('activation_date');
            $table->dropColumn('price_level_id');
            $table->dropColumn('base_price_level_id');
            $table->dropColumn('vat_number');
            $table->dropColumn('address');
            $table->dropColumn('city');
            $table->dropColumn('province');
            $table->dropColumn('postal_code');
            $table->dropColumn('country_id');
            $table->dropColumn('additional_emails');
        });
    }
};
