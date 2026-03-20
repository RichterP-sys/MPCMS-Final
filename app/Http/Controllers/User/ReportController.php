<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Contribution;
use App\Models\Loan;
use App\Models\Dividend;

class ReportController extends Controller
{
    public function index()
    {
        $member = Auth::guard('member')->user();

        // Get all contributions and loans for the member
        $contributions = Contribution::where('member_id', $member->id)->latest()->get();
        $loans = Loan::where('member_id', $member->id)->latest()->get();
        
        // Get dividends for the member
        $dividends = Dividend::where('member_id', $member->id)->orderByDesc('year')->get();

        return view('UserSide.reports.report', compact('member', 'contributions', 'loans', 'dividends'));
    }
}
