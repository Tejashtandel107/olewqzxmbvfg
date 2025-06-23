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
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->unsignedTinyInteger('role_id')->default(4)->comment("1=Super Admin,2=Account Manager,3=Client,4=Operator");
            $table->string('email')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('photo',100)->nullable();
            $table->string('status',20)->default('active')->comment('active,inactive');
            $table->unsignedTinyInteger('created_by')->default(0);
            $table->string('profile_type')->nullable();
            $table->unsignedInteger('profile_id')->nullable();
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->index('role_id','role_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
