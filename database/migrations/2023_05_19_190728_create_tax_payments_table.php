<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_payments', function (Blueprint $table) {
            $table->string('number', 50)->primary();

            $table->string('tax_type', 10)->nullable()->index();

            $table->decimal('in', 17, 2);
            $table->decimal('out', 17, 2);
            $table->decimal('prepopulated', 17, 2)->nullable();

            $table->decimal('total', 17, 2)->nullable();

            $table->decimal('balance', 17, 2)->nullable();

            $table->dateTime('from');
            $table->dateTime('to');

            $table->string('status')->default('created');

            $table->boolean('is_remain')->default(0);

            $table->dateTime('transaction_date')->useCurrent();

            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();

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
        Schema::dropIfExists('tax_payments');
    }
}
