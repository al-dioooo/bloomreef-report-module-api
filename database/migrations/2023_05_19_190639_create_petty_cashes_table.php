<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePettyCashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petty_cashes', function (Blueprint $table) {
            $table->string('number', 50)->primary();

            $table->unsignedBigInteger('branch_id')->nullable()->index();

            $table->string('source', 100);

            $table->string('currency', 15)->default('idr');
            $table->decimal('rate', 17, 2);

            $table->decimal('grand_total', 17, 2);

            $table->decimal('balance', 17, 2)->nullable();

            $table->text('note')->nullable();

            $table->string('status')->default('settled');

            $table->boolean('transaction_type')->default(0)->comment('0 = Income, 1 = Expense');

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
        Schema::dropIfExists('petty_cashes');
    }
}
