<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCashDetailUpdate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'petty_cash_update_id',

        'destination',

        'ppn',
        'ppn_percentage',
        'ppn_type',

        'pph',
        'pph_percentage',
        'pph_type',

        'amount',
        'subtotal',

        'balance',

        'transaction_date',

        'note'
    ];
}
