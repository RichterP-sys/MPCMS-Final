<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'activity_type',
        'description',
        'ip_address',
        'user_agent'
    ];

    /**
     * Get the member that owns the activity log.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
