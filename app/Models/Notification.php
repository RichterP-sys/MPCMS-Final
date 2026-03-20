<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Notification extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the notifiable entity
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => now()])->save();
        }
    }

    /**
     * Mark as unread
     */
    public function markAsUnread()
    {
        if (!is_null($this->read_at)) {
            $this->forceFill(['read_at' => null])->save();
        }
    }

    /**
     * Determine if notification has been read
     */
    public function read()
    {
        return $this->read_at !== null;
    }

    /**
     * Determine if notification has not been read
     */
    public function unread()
    {
        return $this->read_at === null;
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Create a notification for a member
     */
    public static function createForMember(Member $member, string $type, array $data)
    {
        return self::create([
            'type' => $type,
            'notifiable_type' => Member::class,
            'notifiable_id' => $member->id,
            'data' => $data,
        ]);
    }

    /**
     * Create notification for all active members
     */
    public static function createForAllMembers(string $type, array $data)
    {
        $members = Member::where('status', 'active')->get();
        
        foreach ($members as $member) {
            self::createForMember($member, $type, $data);
        }
    }
}
