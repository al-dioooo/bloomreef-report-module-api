<?php

namespace App\Models;

use App\Traits\CallRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
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
        'transaction_date',
        'due_date'
    ];

    protected $casts = [
        'reference_number' => 'json'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $appends = [
        'branch',
        'customer',
        'supplier',
        'type_detail',
        'order'
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
            $query->whereDate('transaction_date', '>=', request('from'))
                ->whereDate('transaction_date', '<=', request('to'));
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
        })->when($filters['branch'] ?? null, function ($query, $branch) {
            $query->where('branch_id', $branch);
        });
    }

    public function invoice()
    {
        return $this->hasOne(InvoiceDetail::class, 'bill_number');
    }

    public function taxPayment()
    {
        return $this->hasMany(TaxPaymentDetail::class, 'bill_number', 'number');
    }

    public function latestTaxPayment()
    {
        return $this->hasOne(TaxPaymentDetail::class, 'bill_number', 'number')->latestOfMany();
    }

    public function getCustomerAttribute()
    {
        return $this->belongsToAnother(env('CASHIER_API_URL', "http://api-kasir.test"), "customer", $this->payor_or_payee_code, "customer-{$this->payor_or_payee_code}");
    }

    public function getSupplierAttribute()
    {
        return $this->belongsToAnother(env('CASHIER_API_URL', "http://api-kasir.test"), "supplier", $this->payor_or_payee_code, "supplier-{$this->payor_or_payee_code}");
    }

    public function getTypeDetailAttribute()
    {
        return $this->belongsToAnother(env('CASHIER_API_URL', "http://api-kasir.test"), "type", $this->type, "type-{$this->type}");
    }

    public function getBranchAttribute()
    {
        return $this->belongsToAnother(env('GENERIC_API_URL', "http://api-generic.test"), "branch", $this->branch_id, "branch-{$this->branch_id}");
    }

    public function getOrderAttribute()
    {
        return $this->belongsToAnother(env('KACAFILM_API_URL', "https://guguskarangmekar.com/apiKacaFilmDev"), "filterOrder", "?order_number=CBGBGR_2023062100308&model_mobil=&team=&id_cabang=&no_polisi=&status=&tanggal_dari=&tanggal_sampai=&page=1&sistem_pembayaran=", "order-{$this->branch_id}");
    }

    public function cashFlows()
    {
        return $this->morphOne(CashFlow::class, 'detail');
    }

    public function histories()
    {
        return $this->hasMany(BillUpdate::class, 'number', 'number');
    }
}
