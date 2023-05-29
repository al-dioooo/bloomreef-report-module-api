<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCashUpdate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',

        'branch_id',

        'currency',
        'rate',

        'amount',

        'ppn',
        'ppn_percentage',
        'ppn_type',

        'pph',
        'pph_percentage',
        'pph_type',

        'grand_total',

        'balance',

        'source',

        'note',

        'status',

        'transaction_type',

        'created_by',
        'updated_by'
    ];
}
