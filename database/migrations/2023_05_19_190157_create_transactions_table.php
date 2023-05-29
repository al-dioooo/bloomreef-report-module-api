<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('number', 50)->primary();

            $table->unsignedBigInteger('branch_id')->nullable()->index();

            $table->string('payor_or_payee_code', 25)->index();

            $table->string('currency', 15)->default('idr');
            $table->decimal('rate', 17, 2);

            $table->decimal('advance_payment', 17, 2)->nullable();

            $table->decimal('grand_total', 17, 2);

            $table->text('note')->nullable();

            $table->string('type', 25)->nullable()->index();

            $table->string('status')->default('outstanding');

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
        Schema::dropIfExists('transactions');
    }
}
