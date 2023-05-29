<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();

            $table->string('transaction_number', 50)->index();
            $table->foreign('transaction_number')->references('number')->on('transactions')->cascadeOnDelete();

            $table->string('invoice_number', 50)->index();
            $table->foreign('invoice_number')->references('number')->on('invoices')->cascadeOnDelete();

            $table->decimal('amount', 17, 2)->nullable();

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
        Schema::dropIfExists('transaction_details');
    }
}
