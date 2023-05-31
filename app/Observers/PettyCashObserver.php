<?php

namespace App\Observers;

use App\Models\Balance;
use App\Models\PettyCash;
use App\Models\PettyCashUpdate;
use Illuminate\Support\Facades\DB;

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
            ->where('status', 'approved')
            ->whereDate('updated_at', '>=', $pettyCash->updated_at)
            ->whereTime('updated_at', '>=', $pettyCash->updated_at)
            ->update([
                'balance' => DB::raw("`balance` - ({$total})"),
                'updated_at' => DB::raw("`updated_at`")
            ]);

        $latest = PettyCash::where('status', 'approved')
            ->where('transaction_type', '<=', 1)
            ->orderBy('updated_at', 'ASC')
            ->first();

        Balance::create([
            'from' => $latest->created_at,
            'to' => $latest->created_at,
            'amount' => $latest->balance
        ]);
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
