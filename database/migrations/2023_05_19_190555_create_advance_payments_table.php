<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvancePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advance_payments', function (Blueprint $table) {
            $table->string('number', 50)->primary();

            $table->unsignedBigInteger('branch_id')->nullable()->index();

            $table->string('payor_or_payee_code', 25)->index();

            $table->string('currency', 15)->default('idr');
            $table->decimal('rate', 17, 2);

            $table->string('sales_payment_account', 100)->nullable();
            $table->string('tax_invoice_number', 100)->nullable();

            $table->decimal('amount', 17, 2);

            $table->decimal('ppn', 17, 2)->nullable();
            $table->unsignedInteger('ppn_percentage')->nullable();

            $table->decimal('grand_total', 17, 2);

            $table->json('reference_number')->nullable();

            $table->text('note')->nullable();

            $table->string('status')->default('settled');

            $table->boolean('transaction_type')->default(0)->comment('0 = Income, 1 = Expense');

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();

            $table->dateTime('transaction_date')->useCurrent();

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
        Schema::dropIfExists('advance_payments');
    }
}
