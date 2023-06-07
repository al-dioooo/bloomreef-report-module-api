<?php

namespace App\Models;

use App\Traits\CallRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillUpdate extends Model
{
    use HasFactory, CallRelationship;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',

        'branch_id',

        'payor_or_payee_code',

        'currency',
        'rate',
        'discount',
        'dpp',
        'ppn',
        'ppn_percentage',
        'advance_payment',
        'grand_total',

        'balance',

        'note',

        'type',

        'status',
        'transaction_type',

        'reference_number',

        'created_by',
        'updated_by',
        'bill_date',
        'due_date'
    ];

    protected $casts = [
        'reference_number' => 'array'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $appends = [
        'branch'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('number', 'like', '%' . $search . '%');
        })->when($filters['number'] ?? null, function ($query, $number) {
            $query->where('number', 'like', '%' . $number . '%');
        })->when($filters['grand_total'] ?? null, function ($query, $grand_total) {
            $query->where('grand_total', 'like', '%' . $grand_total . '%');
        })->when(($filters['from'] ?? null) && ($filters['to'] ?? null), function ($query) {
            $query->whereDate('bill_date', '>=', request('from'))
                ->whereDate('bill_date', '<=', request('to'));
        })->when($filters['transaction_type'] ?? null, function ($query, $type) {
            if ($type === 'income') {
                $query->where('transaction_type', 0);
            } else if ($type === 'expense') {
                $query->where('transaction_type', 1);
            }
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        })->when($filters['type'] ?? null, function ($query, $type) {
            $query->where('type', $type);
        })->when(($filters['payor_or_payee'] ?? null) && ($filters['type'] ?? null), function ($query) {
            if ((request('type') === 'po' || request('type') === 'rt-po')) {
                $query->whereHas('supplier', function ($q) {
                    $q->where('supplier_code', request('payor_or_payee'));
                });
            } else {
                $query->where('type', request('type'))->whereHas('customer', function ($q) {
                    $q->where('customer_code', request('payor_or_payee'));
                });
            }
        })->when($filters['branch'] ?? null, function ($query, $branch) {
            $query->where('branch_id', $branch);
        })->when($filters['tax'] ?? null, function ($query, $tax) {
            if ($tax === 'ppn') {
                $query->where('ppn', '!=', 0)->whereNotNull('ppn');
            }
        })->when($filters['tax_payment'] ?? null, function ($query, $tax_payment) {
            if ($tax_payment === 'only') {
                $query->whereHas('taxPayment');
            } else if ($tax_payment === 'without') {
                $query->where(function ($query) {
                    $query->whereDoesntHave('latestTaxPayment')->orWhereHas('latestTaxPayment', function ($q) {
                        $q->whereRelation('tax', 'status', 'drop');
                    });
                });
            }
        });
    }

    public function getBranchAttribute()
    {
        return $this->belongsToAnother(env('GENERIC_API_URL', "http://api-generic.test"), "branch", $this->branch_id, "branch-{$this->branch_id}");
    }
}
