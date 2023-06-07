<?php

namespace App\Models;

use App\Traits\CallRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCash extends Model
{
    use HasFactory, CallRelationship;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'number';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

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
        })->when($filters['number'] ?? null, function ($query, $search) {
            $query->where('number', 'like', '%' . $search . '%');
        })->when($filters['currency'] ?? null, function ($query, $search) {
            $query->where('currency', 'like', '%' . $search . '%');
        })->when($filters['amount'] ?? null, function ($query, $search) {
            $query->where('amount', 'like', '%' . $search . '%');
        })->when(($filters['from'] ?? null) && ($filters['to'] ?? null), function ($query) {
            $query->whereDate('created_at', '>=', request('from'))
                ->whereDate('created_at', '<=', request('to'));
        })->when($filters['transaction_type'] ?? null, function ($query, $type) {
            if ($type === 'income') {
                $query->where('transaction_type', 0);
            } else if ($type === 'expense') {
                $query->where('transaction_type', 1);
            } else if ($type === 'internal') {
                $query->where('transaction_type', 2);
            }
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        })->when($filters['branch'] ?? null, function ($query, $branch) {
            $query->where('branch_id', $branch);
        })->when($filters['tax'] ?? null, function ($query, $tax) {
            if ($tax === 'ppn') {
                $query->where('ppn', '!=', 0)->whereNotNull('ppn');
            } else if ($tax === 'pph') {
                $query->where('pph', '!=', 0)->whereNotNull('pph');
            }
        })->when(($filters['tax_payment'] ?? null) && ($filters['tax'] ?? null), function ($query) {
            if (request('tax_payment') === 'only') {
                $query->whereHas('taxPayment');
            } else if (request('tax_payment') === 'without') {
                $query->where(function ($query) {
                    $query->whereDoesntHave('latestTaxPayment')->orWhereHas('latestTaxPayment', function ($q) {
                        $q->whereRelation('tax', function ($q) {
                            if (request('tax') === 'ppn') {
                                $q->where('status', 'drop')->where('tax_type', 'ppn');
                            } else if (request('tax') === 'pph') {
                                $q->where('status', 'drop')->where('tax_type', 'pph-23');
                            }
                        })->orWhereRelation('tax', function ($q) {
                            if (request('tax') === 'ppn') {
                                $q->where('status', '!=', 'drop')->where('tax_type', '!=', 'ppn');
                            } else if (request('tax') === 'pph') {
                                $q->where('status', '!=', 'drop')->where('tax_type', '!=', 'pph-23');
                            }
                        });
                    });
                });
            }
        });
    }

    public function taxPayment()
    {
        return $this->hasMany(TaxPaymentDetail::class, 'number', 'number');
    }

    public function latestTaxPayment()
    {
        return $this->hasOne(TaxPaymentDetail::class, 'number', 'number')->ofMany([
            'id' => 'max'
        ], function ($query) {
            if (request('tax')) {
                if (request('tax') === 'ppn') {
                    $query->whereRelation('tax', 'tax_type', 'ppn');
                } else if (request('tax') === 'pph') {
                    $query->whereRelation('tax', 'tax_type', 'pph-23');
                }
            }
        });
    }

    public function getBranchAttribute()
    {
        return $this->belongsToAnother(env('GENERIC_API_URL', "http://api-generic.test"), "branch", $this->branch_id, "branch-{$this->branch_id}");
    }

    public function getSourceCOAAttribute()
    {
        return $this->belongsToAnother(env('ACCOUNTING_API_URL', "https://accounting.guguskarangmekar.com"), "chart-of-account", $this->getAttribute('source'), "source-{$this->getAttribute('source')}");
    }

    public function getDestinationCOAAttribute()
    {
        return $this->belongsToAnother(env('ACCOUNTING_API_URL', "https://accounting.guguskarangmekar.com"), "chart-of-account", $this->getAttribute('destination'), "destination-{$this->getAttribute('destination')}");
    }

    public function details()
    {
        return $this->hasMany(PettyCashDetail::class, 'petty_cash_number', 'number');
    }

    public function updateBalance($amount)
    {
        if ((int) $this->transaction_type !== 2) {
            $this->balance += $amount;
        }
    }

    public function cashFlows()
    {
        return $this->morphOne(CashFlow::class, 'detail');
    }
}
