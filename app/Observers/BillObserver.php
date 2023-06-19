<?php

namespace App\Observers;

use App\Models\Bill;
use App\Models\BillUpdate;
use App\Models\CashFlow;
use ReflectionClass;

class BillObserver
{
    /**
     * Handle the Bill "created" event.
     *
     * @param  \App\Models\Bill  $bill
     * @return void
     */
    public function created(Bill $bill)
    {
        BillUpdate::create($bill->attributesToArray());

        CashFlow::updateOrCreate([
            'transaction_number' => $bill->getAttribute('number')
        ], [
            'model' => (new ReflectionClass(Bill::class))->getShortName(),
            'balance' => $bill->getAttribute('balance'),
            'status' => $bill->getAttribute('status'),
        ]);
    }

    /**
     * Handle the Bill "updated" event.
     *
     * @param  \App\Models\Bill  $bill
     * @return void
     */
    public function updated(Bill $bill)
    {
        $changes = array_merge([
            'number' => $bill->getAttribute('number')
        ], $bill->getChanges());

        BillUpdate::create($changes);

        CashFlow::updateOrCreate([
            'transaction_number' => $bill->getAttribute('number')
        ], [
            'model' => (new ReflectionClass(Bill::class))->getShortName(),
            'balance' => $bill->getAttribute('balance'),
            'status' => $bill->getAttribute('status'),
        ]);
    }

    /**
     * Handle the Bill "deleted" event.
     *
     * @param  \App\Models\Bill  $bill
     * @return void
     */
    public function deleted(Bill $bill)
    {
        //
    }

    /**
     * Handle the Bill "restored" event.
     *
     * @param  \App\Models\Bill  $bill
     * @return void
     */
    public function restored(Bill $bill)
    {
        //
    }

    /**
     * Handle the Bill "force deleted" event.
     *
     * @param  \App\Models\Bill  $bill
     * @return void
     */
    public function forceDeleted(Bill $bill)
    {
        //
    }
}
