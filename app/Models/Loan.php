<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $table = 'financial_records';

    protected static function booted()
    {
        static::addGlobalScope('loans', fn ($q) => $q->where('record_type', 'loan'));
        static::creating(fn ($m) => $m->record_type = $m->record_type ?? 'loan');
    }

    protected $fillable = [
        'record_type',
        'member_id',
        'amount',
        'interest_rate',
        'interest_amount',
        'total_amount',
        'monthly_repayment',
        'term_months',
        'remaining_balance',
        'status',
        'application_date',
        'approval_date',

        // Loan application fields (kept explicit to avoid mass-assignment issues)
        'last_name',
        'first_name',
        'name_extension',
        'middle_name',
        'maiden_middle_name',
        'no_middle_name',
        'date_of_birth',
        'place_of_birth',
        'mothers_maiden_name',
        'nationality',
        'sex',
        'marital_status',
        'citizenship',
        'tin_number',
        'sss_gsis_no',
        'email',
        'cell_phone',
        'home_telephone',
        'business_telephone',
        'unit_room_no',
        'floor',
        'building_name',
        'lot_no',
        'block_no',
        'phase_no',
        'house_no',
        'street_name',
        'subdivision',
        'barangay',
        'municipality_city',
        'province_state_country',
        'zip_code',
        'nature_of_work',
        'employer_business_name',
        'employee_id',
        'date_of_employment',
        'source_of_fund',
        'repayment_method',
        'repayment_details',
        'emp_unit_room_no',
        'emp_floor',
        'emp_building_name',
        'emp_lot_no',
        'emp_block_no',
        'emp_phase_no',
        'emp_house_no',
        'emp_street_name',
        'emp_subdivision',
        'emp_barangay',
        'emp_municipality_city',
        'emp_province_state_country',
        'emp_zip_code',
        'loan_purpose',
        'other_purpose_specify',
        'desired_loan_amount',
        'other_amount_specify',
        'loan_term',
    ];

    protected $dates = [
        'application_date',
        'approval_date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'monthly_repayment' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'application_date' => 'date',
        'approval_date' => 'date',
        'repayment_details' => 'array',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function repayments()
    {
        return $this->hasMany(LoanRepayment::class);
    }

    public function collateral()
    {
        return $this->hasOne(LoanCollateral::class);
    }
}
