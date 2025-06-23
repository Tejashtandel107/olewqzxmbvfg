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
        Schema::table('line_incomes_monthly', function (Blueprint $table) {
            $table->string('invoice_number')->after('total_cost')->nullable();
            $table->decimal('total_paid',10,2)->default(0.00)->after('invoice_number')->nullable();
            $table->decimal('bank_fees',10,2)->default(0.00)->after('total_paid')->nullable();
            $table->datetime('invoice_due_date')->after('bank_fees')->nullable();
            $table->string('invoice_status')->after('invoice_due_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('line_incomes_monthly', function (Blueprint $table) {
            $table->dropColumn('invoice_number');
            $table->dropColumn('total_paid');
            $table->dropColumn('bank_fees');
            $table->dropColumn('invoice_due_date');
            $table->dropColumn('invoice_status');
        });
    }
};
