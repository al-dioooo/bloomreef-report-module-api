<?php

namespace App\Observers;

use App\Models\Balance;
use App\Models\CashFlow;
use App\Models\PettyCash;
use App\Models\PettyCashUpdate;
use Illuminate\Support\Facades\DB;
use ReflectionClass;

class PettyCashObserver
{
    /**
     * Handle the PettyCash "created" event.
     *
     * @param  \App\Models\PettyCash  $pettyCash
     * @return void
     */
    public function created(PettyCash $pettyCash)
    {
        PettyCashUpdate::create($pettyCash->attributesToArray());

        CashFlow::updateOrCreate([
            'transaction_number' => $pettyCash->getAttribute('number')
        ], [
            'model' => (new ReflectionClass(PettyCash::class))->getShortName(),
            'balance' => $pettyCash->getAttribute('balance'),
            'status' => $pettyCash->getAttribute('status')
        ]);
    }

    /**
     * Handle the PettyCash "updated" event.
     *
     * @param  \App\Models\PettyCash  $pettyCash
     * @return void
     */
    public function updated(PettyCash $pettyCash)
    {
        $changes = array_merge([
            'number' => $pettyCash->getAttribute('number')
        ], $pettyCash->getChanges());

        PettyCashUpdate::create($changes);

        CashFlow::updateOrCreate([
            'transaction_number' => $pettyCash->getAttribute('number')
        ], [
            'model' => (new ReflectionClass(PettyCash::class))->getShortName(),
            'balance' => $pettyCash->getAttribute('balance'),
            'status' => $pettyCash->getAttribute('status')
        ]);
    }

    /**
     * Handle the PettyCash "deleting" event.
     *
     * @param  \App\Models\PettyCash  $pettyCash
     * @return void
     */
    public function deleting(PettyCash $pettyCash)
    {
        PettyCashUpdate::create([
            'number' => $pettyCash->getAttribute('number')
        ]);

        $total = 0;

        if ($pettyCash->transaction_type === 1) {
            $total -= $pettyCash->grand_total;
        } else if ($pettyCash->transaction_type === 0) {
            $total += $pettyCash->grand_total;
        }

        $petty_cashes = PettyCash::query()
            ->where('status', 'settled')
            ->whereDate('updated_at', '>=', $pettyCash->updated_at)
            ->whereTime('updated_at', '>=', $pettyCash->updated_at)
            ->update([
                'balance' => DB::raw("`balance` - ({$total})"),
                'updated_at' => DB::raw("`updated_at`")
            ]);

        $latest = PettyCash::where('status', 'settled')
            ->where('transaction_type', '<=', 1)
            ->orderBy('updated_at', 'ASC')
            ->first();

        Balance::create([
            'from' => $latest->created_at,
            'to' => $latest->created_at,
            'amount' => $latest->balance
        ]);

        $cash_flow = CashFlow::where('transaction_number', $pettyCash->getAttribute('number'))->first();

        if ($cash_flow) {
            $cash_flow->delete();

            $cash_flows = CashFlow::query()
                ->where('status', 'settled')
                ->whereDate('updated_at', '>=', $cash_flow->updated_at)
                ->whereTime('updated_at', '>=', $cash_flow->updated_at)
                ->update([
                    'balance' => DB::raw("`balance` - ({$total})"),
                    'updated_at' => DB::raw("`updated_at`")
                ]);
        }
    }

    /**
     * Handle the PettyCash "restored" event.
     *
     * @param  \App\Models\PettyCash  $pettyCash
     * @return void
     */
    public function restored(PettyCash $pettyCash)
    {
        //
    }

    /**
     * Handle the PettyCash "force deleted" event.
     *
     * @param  \App\Models\PettyCash  $pettyCash
     * @return void
     */
    public function forceDeleted(PettyCash $pettyCash)
    {
        //
    }
}
