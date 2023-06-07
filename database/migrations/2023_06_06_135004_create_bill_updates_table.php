<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_updates', function (Blueprint $table) {
            $table->id();

            $table->string('number', 50)->index();
            $table->foreign('number')->references('number')->on('bills')->cascadeOnDelete();

            $table->unsignedBigInteger('branch_id')->nullable()->index();

            $table->string('payor_or_payee_code', 25)->nullable()->index();

            $table->string('currency', 15)->nullable()->default('idr');
            $table->decimal('rate', 17, 2)->nullable();

            $table->decimal('discount', 17, 2)->nullable();
            $table->decimal('dpp', 17, 2)->nullable();

            $table->decimal('ppn', 17, 2)->nullable();
            $table->unsignedInteger('ppn_percentage')->nullable();

            $table->decimal('advance_payment', 17, 2)->nullable();

            $table->decimal('grand_total', 17, 2)->nullable();

            $table->decimal('balance', 17, 2)->nullable();

            $table->json('reference_number')->nullable();

            $table->text('note')->nullable();

            $table->string('type', 25)->nullable()->index();

            $table->string('status')->nullable()->default('outstanding');

            $table->boolean('transaction_type')->nullable()->default(0)->comment('0 = Income, 1 = Expense');

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();

            $table->dateTime('transaction_date')->nullable()->useCurrent();
            $table->dateTime('due_date')->nullable();

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
        Schema::dropIfExists('bill_updates');
    }
}
