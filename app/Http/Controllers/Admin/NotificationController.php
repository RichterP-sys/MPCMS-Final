<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Contribution;
use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\CooperativeAnnouncement;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index()
    {
        // 1. Get member account balances (top 10 by balance)
        $members = Member::with(['contributions', 'loans.repayments'])->get();
        
        $memberBalances = $members->map(function ($member) {
            $contributions = $member->contributions->where('status', 'approved')->sum('amount');
            $totalLoans = $member->loans->where('status', 'approved')->sum('amount');
            $totalRepayments = $member->loans->flatMap->repayments->sum('amount');
            $outstandingLoans = $totalLoans - $totalRepayments;
            
            return [
                'member' => $member,
                'contributions' => $contributions,
                'outstanding_loans' => $outstandingLoans,
                'net_balance' => $contributions - $outstandingLoans,
            ];
        })->sortByDesc('net_balance')->take(10);

        // 2. Get upcoming meetings and election notices
        $meetings = CooperativeAnnouncement::active()
            ->upcoming()
            ->meetings()
            ->orderBy('scheduled_date')
            ->take(5)
            ->get();

        // 3. Get loan due date alerts: overdue + loans due within 7 days
        $upcomingDueLoans = Loan::with('member')
            ->where('status', 'approved')
            ->where('remaining_balance', '>', 0)
            ->get()
            ->map(function ($loan) {
                // Calculate due date based on loan_term string from approval_date
                if ($loan->approval_date) {
                    $months = 12; // default
                    if ($loan->loan_term) {
                        if (preg_match('/(\d+)\s*month/i', $loan->loan_term, $m)) {
                            $months = (int) $m[1];
                        } elseif (preg_match('/(\d+)\s*year/i', $loan->loan_term, $m)) {
                            $months = (int) $m[1] * 12;
                        }
                    }
                    $dueDate = Carbon::parse($loan->approval_date)->addMonths($months);
                    $daysUntilDue = now()->diffInDays($dueDate, false);
                    
                    return [
                        'loan' => $loan,
                        'due_date' => $dueDate,
                        'days_until_due' => $daysUntilDue,
                        'is_overdue' => $daysUntilDue < 0,
                    ];
                }
                return null;
            })
            ->filter()
            ->filter(fn($item) => $item['is_overdue'] || $item['days_until_due'] <= 7) // Overdue OR due within 7 days
            ->sortBy('days_until_due')
            ->values();

        // 4. Get new cooperative offerings
        $offerings = CooperativeAnnouncement::active()
            ->offerings()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 5. Get general announcements
        $generalAnnouncements = CooperativeAnnouncement::active()
            ->where('type', 'general')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Calculate totals for stats
        $totalMembers = Member::count();
        $totalContributions = Contribution::where('status', 'approved')->sum('amount');
        $overdueLoansCount = $upcomingDueLoans->where('is_overdue', true)->count();
        $upcomingMeetingsCount = $meetings->count();

        return view('AdminSide.notifications.index', compact(
            'memberBalances',
            'meetings',
            'upcomingDueLoans',
            'offerings',
            'generalAnnouncements',
            'totalMembers',
            'totalContributions',
            'overdueLoansCount',
            'upcomingMeetingsCount'
        ));
    }

    /**
     * Store a new announcement
     */
    public function storeAnnouncement(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:meeting,election,offering,general',
            'scheduled_date' => 'nullable|date',
            'scheduled_time' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'priority' => 'required|in:low,normal,high,urgent',
        ]);

        $announcement = CooperativeAnnouncement::create(array_merge($validated, ['is_active' => true]));
        
        // Send notifications to all members based on type
        if (in_array($announcement->type, ['meeting', 'election'])) {
            \App\Services\NotificationService::newMeeting($announcement);
        } elseif ($announcement->type === 'offering') {
            \App\Services\NotificationService::newOffering($announcement);
        } else {
            \App\Services\NotificationService::generalAnnouncement($announcement);
        }

        return back()->with('success', 'Announcement created and members notified successfully.');
    }

    /**
     * Delete an announcement
     */
    public function destroyAnnouncement(CooperativeAnnouncement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Announcement deleted successfully.');
    }

    public function markAsRead($notification)
    {
        // Mark notification as read logic
        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        // Mark all notifications as read logic
        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy($id)
    {
        // Delete notification logic
        return back()->with('success', 'Notification deleted successfully.');
    }

    /**
     * Send formal payment demand with legal notice to overdue borrower
     */
    public function sendPaymentDemand(Loan $loan)
    {
        // Verify loan is approved and has balance
        if ($loan->status !== 'approved' || ($loan->remaining_balance ?? 0) <= 0) {
            return back()->with('error', 'This loan is not eligible for a payment demand.');
        }

        // Calculate due date and days overdue
        $months = 12;
        if ($loan->loan_term && preg_match('/(\d+)\s*month/i', $loan->loan_term, $m)) {
            $months = (int) $m[1];
        } elseif ($loan->loan_term && preg_match('/(\d+)\s*year/i', $loan->loan_term, $m)) {
            $months = (int) $m[1] * 12;
        }
        $dueDate = Carbon::parse($loan->approval_date)->addMonths($months);
        $daysOverdue = (int) $dueDate->diffInDays(now(), false);

        if ($daysOverdue <= 0) {
            return back()->with('error', 'This loan is not yet overdue. Payment demands are only for overdue accounts.');
        }

        NotificationService::sendOverduePaymentDemand($loan, $daysOverdue);

        return back()->with('success', 'Formal payment demand with legal notice has been sent to ' . $loan->member->first_name . ' ' . $loan->member->last_name . '.');
    }
}
