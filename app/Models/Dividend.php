<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dividend extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'year',
        'total_contributions',
        'dividend_rate',
        'dividend_amount',
        'status', // pending, released
        'released_at',
        'notes',
    ];

    protected $casts = [
        'total_contributions' => 'decimal:2',
        'dividend_rate' => 'decimal:4',
        'dividend_amount' => 'decimal:2',
        'released_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReleased($query)
    {
        return $query->where('status', 'released');
    }
}
