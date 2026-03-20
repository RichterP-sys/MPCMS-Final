<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CooperativeFund extends Model
{
    use HasFactory;

    protected $fillable = [
        'fund_type',
        'bank_name',
        'account_number',
        'account_name',
        'amount',
        'description',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Scope for active funds
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for cash on hand
     */
    public function scopeCash($query)
    {
        return $query->where('fund_type', 'cash');
    }

    /**
     * Scope for bank accounts
     */
    public function scopeBank($query)
    {
        return $query->where('fund_type', 'bank');
    }

    /**
     * Get total cash on hand
     */
    public static function getTotalCash()
    {
        return self::active()->cash()->sum('amount');
    }

    /**
     * Get total bank balance
     */
    public static function getTotalBankBalance()
    {
        return self::active()->bank()->sum('amount');
    }

    /**
     * Get bank accounts grouped by bank name
     */
    public static function getBankAccounts()
    {
        return self::active()->bank()->get()->groupBy('bank_name');
    }
}
