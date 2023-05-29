<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePettyCashUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petty_cash_updates', function (Blueprint $table) {
            $table->id();

            $table->string('number', 50)->index();
            $table->foreign('number')->references('number')->on('petty_cashes')->cascadeOnDelete();

            $table->unsignedBigInteger('branch_id')->nullable();

            $table->string('source', 100);

            $table->string('currency', 15)->nullable();
            $table->decimal('rate', 17, 2)->nullable();

            $table->decimal('grand_total', 17, 2)->nullable();

            $table->decimal('balance', 17, 2)->nullable();

            $table->text('note')->nullable();

            $table->string('status')->nullable();

            $table->boolean('transaction_type')->default(0)->comment('0 = Income, 1 = Expense')->nullable();

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();

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
        Schema::dropIfExists('petty_cash_updates');
    }
}
