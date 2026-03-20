<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\MemberAccountUpdated;

class Member extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'member_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'address',
        'join_date',
        'status',
        'theme',
        'offset_contribution_amount',
        // Profile completion fields
        'nature_of_work',
        'employer_business_name',
        'date_of_employment',
        'tin_number',
        'sss_gsis_no',
        'profile_completed',
        'profile_photo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'join_date' => 'date',
        'date_of_employment' => 'date',
        'email_verified_at' => 'datetime',
        'offset_contribution_amount' => 'decimal:2',
        'profile_completed' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Send the account updated notification.
     *
     * @param string $adminName
     * @param array $updatedFields
     * @return void
     */
    public function sendAccountUpdatedNotification($adminName, $updatedFields = [])
    {
        //$this->notify(new MemberAccountUpdated($adminName, $updatedFields));
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function dividends()
    {
        return $this->hasMany(Dividend::class);
    }

    /**
     * Relationship to User account
     */
    public function memberAccount()
    {
        return $this->hasOne(User::class, 'member_id', 'id');
    }
}
