<?php

namespace App\Observers;

use App\Models\Balance;
use App\Models\PettyCash;
use App\Models\PettyCashUpdate;

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
        PettyCashUpdate::create($pettyCash->getChanges());
    }

    /**
     * Handle the PettyCash "deleted" event.
     *
     * @param  \App\Models\PettyCash  $pettyCash
     * @return void
     */
    public function deleted(PettyCash $pettyCash)
    {
        //
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
