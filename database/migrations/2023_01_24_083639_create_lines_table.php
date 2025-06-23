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
        Schema::create('lines', function (Blueprint $table) {
            $table->increments('line_id');
            $table->date('register_date');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('operator_id');
            $table->unsignedInteger('account_manager_id')->nullable();
            $table->unsignedInteger('purchase_invoice_from')->nullable()->comment('Purchase Invoice From Protocol Number');
            $table->unsignedInteger('purchase_invoice_to')->nullable()->comment('Purchase Invoice To Protocol Number');
            $table->unsignedInteger('purchase_invoice_lines')->nullable()->comment('Number of passive accounting lines the operator has posted in the Company ledger related to purchase invoices');
            $table->unsignedInteger('purchase_invoice_registrations')->nullable();
            $table->unsignedInteger('sales_invoice_from')->nullable()->comment('Sales Invoice From Protocol Number');
            $table->unsignedInteger('sales_invoice_to')->nullable()->comment('Sales Invoice To Protocol Number');
            $table->unsignedInteger('sales_invoice_lines')->nullable()->comment('Number of active accounting lines the operator has posted in the Company ledger.');
            $table->unsignedInteger('sales_invoice_registrations')->nullable();
            $table->string('payment_register_month_id')->nullable();
            $table->unsignedInteger('payment_register_day')->nullable();
            $table->unsignedInteger('payment_register_daily_records')->nullable();
            $table->unsignedInteger('payment_register_lines')->nullable()->comment('Number of accounting lines the operator has posted in the Payments Register');
            $table->string('petty_cash_book_type')->nullable();
            $table->unsignedTinyInteger('petty_cash_bank_id')->nullable();
            $table->string('petty_cash_other_bank')->nullable();
            $table->string('petty_cash_book_month_id')->nullable();
            $table->unsignedInteger('petty_cash_book_lines')->nullable()->comment('Number of lines posted by the operator in the Petty Cash Book');
            $table->unsignedInteger('petty_cash_book_registrations')->nullable();
            $table->dateTime('time_spent_start_time');
            $table->dateTime('time_spent_end_time');
            $table->unsignedInteger('overtime_extra')->nullable();
            $table->string('overtime_note')->nullable();
            $table->timestamps();

            $table->index('register_date','register_date');
            $table->index('client_id','client_id');
            $table->index('company_id','company_id');
            $table->index('operator_id','operator_id');
            $table->index('account_manager_id','account_manager_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lines');
    }
};
