<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CooperativeAnnouncement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type', // meeting, election, offering, general
        'scheduled_date',
        'scheduled_time',
        'location',
        'is_active',
        'priority', // low, normal, high, urgent
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where(function ($q) {
            $q->where('scheduled_date', '>=', now()->toDateString())
              ->orWhereNull('scheduled_date');
        });
    }

    public function scopeMeetings($query)
    {
        return $query->whereIn('type', ['meeting', 'election']);
    }

    public function scopeOfferings($query)
    {
        return $query->where('type', 'offering');
    }
}
