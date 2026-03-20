<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Loan Eligibility Rules
     |--------------------------------------------------------------------------
     |
     | Keep these values configurable so business rules can evolve without
     | rewriting controller logic.
     |
     */
    'eligibility' => [
        // Member must be active to apply/receive a loan.
        'require_active_member' => true,

        // When false, contributions do NOT affect eligibility. Only the admin decides.
        'contributions_affect_eligibility' => false,

        // Minimum approved contributions required before a member can borrow (only when contributions_affect_eligibility is true).
        'min_approved_contributions_count' => 3,

        // Minimum total approved contribution amount required before borrowing (only when contributions_affect_eligibility is true).
        'min_approved_contributions_amount' => 1000.00,
    ],

    /*
     |--------------------------------------------------------------------------
     | Loan Amount Limits
     |--------------------------------------------------------------------------
     */
    'limits' => [
        // Maximum loan principal is (net contributions * ratio).
        // Example: ratio 3 means a member with ₱1,000 net contributions can borrow up to ₱3,000.
        'loan_to_contribution_ratio' => 3.0,
    ],
];

