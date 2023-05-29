<?php

namespace App\Models;

use App\Traits\CallRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCashDetail extends Model
{
    use HasFactory, CallRelationship;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',

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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $appends = [
        'destination_coa',
        'currency'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('transaction_number', 'like', '%' . $search . '%');
        })->when($filters['transaction_number'] ?? null, function ($query, $search) {
            $query->where('transaction_number', 'like', '%' . $search . '%');
        })->when(($filters['from'] ?? null) && ($filters['to'] ?? null), function ($query) {
            $query->whereDate('transaction_date', '>=', request('from'))
                ->whereDate('transaction_date', '<=', request('to'));
        })->when($filters['transaction_type'] ?? null, function ($query, $type) {
            if ($type === 'income') {
                $query->where('transaction_type', 0);
            } else if ($type === 'expense') {
                $query->where('transaction_type', 1);
            } else if ($type === 'internal') {
                $query->where('transaction_type', 2);
            }
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->whereRelation('pettyCash', function ($q) use ($status) {
                if ($status === 'settled') {
                    $q->where('status', 'approved');
                } else {
                    $q->where('status', $status);
                }
            });
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

    public function pettyCash()
    {
        return $this->belongsTo(PettyCash::class, 'petty_cash_number', 'number');
    }

    public function taxPayment()
    {
        return $this->hasMany(TaxPaymentDetail::class);
    }

    public function latestTaxPayment()
    {
        return $this->hasOne(TaxPaymentDetail::class)->ofMany([
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

    public function getDestinationCOAAttribute()
    {
        return $this->belongsToAnother(env('ACCOUNTING_API_URL', "https://accounting.guguskarangmekar.com"), "chart-of-account", $this->destination, "destination-{$this->destination}");
    }

    public function getCurrencyAttribute()
    {
        return $this->pettyCash->currency;
    }
}
