<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();

            $table->string('invoice_number', 100)->index();
            $table->foreign('invoice_number')->references('number')->on('invoices')->cascadeOnDelete();

            $table->string('bill_number', 50)->index();
            $table->foreign('bill_number')->references('number')->on('bills')->cascadeOnDelete();

            $table->decimal('price', 17, 2)->nullable();
            $table->decimal('discount', 17, 2)->nullable();
            $table->decimal('ppn', 17, 2)->nullable();

            $table->decimal('subtotal', 17, 2)->nullable();

            $table->text('description')->nullable();

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
        Schema::dropIfExists('invoice_details');
    }
}
