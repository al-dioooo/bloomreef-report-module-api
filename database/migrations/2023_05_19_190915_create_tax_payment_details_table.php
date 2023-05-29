<?php

use App\Models\PettyCashDetail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxPaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_payment_details', function (Blueprint $table) {
            $table->id();

            $table->string('tax_payment_number', 50)->nullable()->index();

            $table->foreignIdFor(PettyCashDetail::class)->nullable()->constrained()->nullOnDelete();

            $table->string('bill_number', 50)->nullable()->index();
            $table->foreign('bill_number')->references('number')->on('bills')->nullOnDelete();

            $table->string('invoice_number', 50)->nullable()->index();
            $table->foreign('invoice_number')->references('number')->on('invoices')->nullOnDelete();

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
        Schema::dropIfExists('tax_payment_details');
    }
}
