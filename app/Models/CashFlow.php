<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'model',

        'balance',

        'status'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        })->when(($filters['from'] ?? null) && ($filters['to'] ?? null), function ($query) {
            $query->whereDate('updated_at', '>=', request('from'))
                ->whereDate('updated_at', '<=', request('to'));
        });
    }

    public function detail()
    {
        return $this->morphTo('detail', 'model', 'transaction_number');
    }
}
