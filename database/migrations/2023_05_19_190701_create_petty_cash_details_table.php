<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePettyCashDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petty_cash_details', function (Blueprint $table) {
            $table->id();

            $table->string('petty_cash_number', 50)->index();
            $table->foreign('petty_cash_number')->references('number')->on('petty_cashes')->cascadeOnDelete();

            $table->string('destination', 100)->index();

            $table->decimal('ppn', 17, 2)->nullable();
            $table->unsignedInteger('ppn_percentage')->nullable();
            $table->boolean('ppn_type')->nullable()->comment('0 = In, 1 = Out');

            $table->decimal('pph', 17, 2)->nullable();
            $table->unsignedInteger('pph_percentage')->nullable();
            $table->boolean('pph_type')->nullable()->comment('0 = In, 1 = Out');

            $table->decimal('amount', 17, 2);
            $table->decimal('subtotal', 17, 2);

            $table->decimal('balance', 17, 2)->nullable();

            $table->text('note')->nullable();

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
        Schema::dropIfExists('petty_cash_details');
    }
}
