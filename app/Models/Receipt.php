<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'record_id',
        'record_type',
        'member_id',
        'amount',
        'receipt_number',
        'receipt_status',
        'receipt_issued_at',
    ];

    protected $casts = [
        'receipt_issued_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'record_id');
    }

    public function contribution()
    {
        return $this->belongsTo(Contribution::class, 'record_id');
    }

    public function repayment()
    {
        return $this->belongsTo(LoanRepayment::class, 'record_id');
    }
}
