<?php

namespace App\Observers;

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

        $petty_cashes = PettyCash::whereDate('created_at', '>=', $pettyCash->getAttribute('created_at'))->update([
            'balance' => DB::raw("`balance` + ({$pettyCash->getAttribute('balance')})")
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
