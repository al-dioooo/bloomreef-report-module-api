<?php

namespace App\Models;

use App\Traits\HasDynamicRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    use HasFactory, HasDynamicRelationship;

    protected $fillable = [
        'transaction_number',
        'model',

        'balance'
    ];

    public function detail()
    {
        return $this->morphTo('detail', 'model', 'transaction_number');
    }
}
