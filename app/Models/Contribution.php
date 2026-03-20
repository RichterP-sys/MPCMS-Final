<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    use HasFactory;

    protected $table = 'financial_records';

    protected static function booted()
    {
        static::addGlobalScope('contributions', fn ($q) => $q->where('record_type', 'contribution'));
        static::creating(function ($m) {
            $m->record_type = 'contribution';
            if (empty($m->application_date) && !empty($m->contribution_date)) {
                $m->application_date = $m->contribution_date;
            }
        });
    }

    protected $fillable = [
        'member_id',
        'amount',
        'contribution_type',
        'contribution_date',
        'application_date',
        'status'
    ];

    protected $casts = [
        'contribution_date' => 'date',
        'application_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
