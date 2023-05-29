<?php

use App\Models\PettyCashUpdate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePettyCashDetailUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petty_cash_detail_updates', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(PettyCashUpdate::class)->constrained()->cascadeOnDelete();

            $table->string('destination', 100)->nullable();

            $table->decimal('ppn', 17, 2)->nullable();
            $table->unsignedInteger('ppn_percentage')->nullable();
            $table->boolean('ppn_type')->nullable()->comment('0 = In, 1 = Out');

            $table->decimal('pph', 17, 2)->nullable();
            $table->unsignedInteger('pph_percentage')->nullable();
            $table->boolean('pph_type')->nullable()->comment('0 = In, 1 = Out');

            $table->decimal('amount', 17, 2)->nullable();
            $table->decimal('subtotal', 17, 2)->nullable();

            $table->decimal('balance', 17, 2)->nullable();

            $table->text('note')->nullable();

            $table->dateTime('transaction_date')->nullable();

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
        Schema::dropIfExists('petty_cash_detail_updates');
    }
}
