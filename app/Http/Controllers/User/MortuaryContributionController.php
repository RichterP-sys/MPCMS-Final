<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MortuaryContributionController extends Controller
{
    /**
     * Show the form for paying mortuary aid.
     */
    public function create()
    {
        $member = auth()->guard('member')->user();

        return view('UserSide.mortuary.create', compact('member'));
    }

    /**
     * Store a new mortuary aid contribution.
     */
    public function store(Request $request)
    {
        $member = auth()->guard('member')->user();

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'contribution_date' => ['nullable', 'date'],
        ]);

        $date = !empty($validated['contribution_date'])
            ? Carbon::parse($validated['contribution_date'])
            : Carbon::now();

        Contribution::create([
            'member_id' => $member->id,
            'amount' => $validated['amount'],
            'contribution_type' => 'mortuary',
            'contribution_date' => $date,
            'application_date' => $date,
            'status' => 'pending',
        ]);

        ActivityLogService::log(
            'contribution',
            'Submitted Mortuary Aid contribution of ₱' . number_format($validated['amount'], 2),
            $request
        );

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Your Mortuary Aid contribution has been submitted and is pending confirmation.');
    }
}

