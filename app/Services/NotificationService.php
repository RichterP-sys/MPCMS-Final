<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Member;
use App\Models\Loan;
use App\Models\Contribution;
use App\Models\CooperativeAnnouncement;
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * Notify member when loan is approved
     */
    public static function loanApproved(Loan $loan)
    {
        Notification::createForMember($loan->member, 'loan_approved', [
            'title' => 'Loan Approved',
            'message' => "Your loan application for ₱" . number_format($loan->amount, 2) . " has been approved.",
            'icon' => 'fa-check-circle',
            'color' => 'emerald',
            'loan_id' => $loan->id,
        ]);
    }

    /**
     * Notify member when loan is rejected
     */
    public static function loanRejected(Loan $loan)
    {
        Notification::createForMember($loan->member, 'loan_rejected', [
            'title' => 'Loan Application Update',
            'message' => "Your loan application for ₱" . number_format($loan->amount, 2) . " was not approved.",
            'icon' => 'fa-times-circle',
            'color' => 'red',
            'loan_id' => $loan->id,
        ]);
    }

    /**
     * Notify member when loan is due soon
     */
    public static function loanDueSoon(Loan $loan, int $daysRemaining)
    {
        Notification::createForMember($loan->member, 'loan_due_soon', [
            'title' => 'Loan Payment Reminder',
            'message' => "Your loan payment of ₱" . number_format($loan->remaining_balance, 2) . " is due in {$daysRemaining} days.",
            'icon' => 'fa-clock',
            'color' => 'amber',
            'loan_id' => $loan->id,
            'days_remaining' => $daysRemaining,
        ]);
    }

    /**
     * Notify member when loan is overdue
     */
    public static function loanOverdue(Loan $loan, int $daysOverdue)
    {
        Notification::createForMember($loan->member, 'loan_overdue', [
            'title' => 'Loan Payment Overdue',
            'message' => "Your loan payment of ₱" . number_format($loan->remaining_balance, 2) . " is {$daysOverdue} days overdue. Please settle immediately.",
            'icon' => 'fa-exclamation-triangle',
            'color' => 'red',
            'loan_id' => $loan->id,
            'days_overdue' => $daysOverdue,
        ]);
    }

    /**
     * Send formal payment demand with legal notice to overdue borrower.
     * References applicable laws of the Republic of the Philippines.
     */
    public static function sendOverduePaymentDemand(Loan $loan, int $daysOverdue)
    {
        $balance = (float) ($loan->remaining_balance ?? $loan->amount ?? 0);

        $message = "FINAL DEMAND FOR PAYMENT — Your loan account (Loan #{$loan->id}) has an outstanding balance of ₱" . number_format($balance, 2) . ", which is {$daysOverdue} days overdue. " .
            "You are hereby formally demanded to settle this obligation within seven (7) days from receipt of this notice. " .
            "Failure to comply may result in legal action pursuant to the Civil Code of the Philippines (Articles 1170, 2208), Republic Act No. 9520 (Philippine Cooperative Code of 2008), and other applicable laws of the Republic of the Philippines. " .
            "The cooperative reserves the right to pursue all legal remedies including but not limited to collection suits, civil action for damages, and reporting to credit bureaus.";

        return Notification::createForMember($loan->member, 'loan_payment_demand', [
            'title' => 'Formal Demand for Payment — Legal Notice',
            'message' => $message,
            'icon' => 'fa-gavel',
            'color' => 'red',
            'loan_id' => $loan->id,
            'days_overdue' => $daysOverdue,
            'balance' => $balance,
            'is_legal_demand' => true,
        ]);
    }

    /**
     * Notify member when contribution is recorded
     */
    public static function contributionRecorded(Contribution $contribution)
    {
        Notification::createForMember($contribution->member, 'contribution_recorded', [
            'title' => 'Contribution Recorded',
            'message' => "Your contribution of ₱" . number_format($contribution->amount, 2) . " has been recorded and is pending approval.",
            'icon' => 'fa-coins',
            'color' => 'blue',
            'contribution_id' => $contribution->id,
        ]);
    }

    /**
     * Notify member when contribution is approved
     */
    public static function contributionApproved(Contribution $contribution)
    {
        Notification::createForMember($contribution->member, 'contribution_approved', [
            'title' => 'Contribution Approved',
            'message' => "Your contribution of ₱" . number_format($contribution->amount, 2) . " has been approved.",
            'icon' => 'fa-check-circle',
            'color' => 'emerald',
            'contribution_id' => $contribution->id,
        ]);
    }

    /**
     * Send payment reminder when member didn't pay (admin marked as didn't pay).
     */
    public static function sendPaymentReminder(Loan $loan)
    {
        $balance = (float) ($loan->remaining_balance ?? $loan->amount ?? 0);
        $monthly = (float) ($loan->monthly_repayment ?? 0);

        $message = "Payment reminder: Your loan (Loan #{$loan->id}) has an outstanding balance of ₱" . number_format($balance, 2);
        if ($monthly > 0) {
            $message .= ". Your monthly payment is ₱" . number_format($monthly, 2);
        }
        $message .= ". Please settle your payment at your earliest convenience.";

        return Notification::createForMember($loan->member, 'payment_reminder', [
            'title' => 'Loan Payment Reminder',
            'message' => $message,
            'icon' => 'fa-bell',
            'color' => 'amber',
            'loan_id' => $loan->id,
            'balance' => $balance,
        ]);
    }

    /**
     * Notify member of repayment received
     */
    public static function repaymentReceived(Loan $loan, float $amount)
    {
        Notification::createForMember($loan->member, 'repayment_received', [
            'title' => 'Payment Received',
            'message' => "Your loan payment of ₱" . number_format($amount, 2) . " has been received. Remaining balance: ₱" . number_format($loan->remaining_balance, 2),
            'icon' => 'fa-receipt',
            'color' => 'emerald',
            'loan_id' => $loan->id,
        ]);
    }

    /**
     * Notify all members of new meeting/election
     */
    public static function newMeeting(CooperativeAnnouncement $announcement)
    {
        $type = $announcement->type === 'election' ? 'Election Notice' : 'Meeting Schedule';
        $dateInfo = $announcement->scheduled_date ? " on " . $announcement->scheduled_date->format('M d, Y') : '';
        
        Notification::createForAllMembers('meeting_notice', [
            'title' => $type,
            'message' => $announcement->title . $dateInfo,
            'icon' => $announcement->type === 'election' ? 'fa-vote-yea' : 'fa-calendar-alt',
            'color' => $announcement->type === 'election' ? 'purple' : 'indigo',
            'announcement_id' => $announcement->id,
            'priority' => $announcement->priority,
        ]);
    }

    /**
     * Notify all members of new offering
     */
    public static function newOffering(CooperativeAnnouncement $announcement)
    {
        Notification::createForAllMembers('new_offering', [
            'title' => 'New Cooperative Offering',
            'message' => $announcement->title . ($announcement->description ? ': ' . Str::limit($announcement->description, 100) : ''),
            'icon' => 'fa-gift',
            'color' => 'pink',
            'announcement_id' => $announcement->id,
        ]);
    }

    /**
     * Notify member of account balance update
     */
    public static function balanceUpdate(Member $member, float $newBalance)
    {
        Notification::createForMember($member, 'balance_update', [
            'title' => 'Account Balance Updated',
            'message' => "Your account balance has been updated. Current balance: ₱" . number_format($newBalance, 2),
            'icon' => 'fa-wallet',
            'color' => 'blue',
        ]);
    }

    /**
     * Welcome notification for new members
     */
    public static function welcomeMember(Member $member)
    {
        Notification::createForMember($member, 'welcome', [
            'title' => 'Welcome to the Cooperative!',
            'message' => "Hello {$member->first_name}! Your account has been activated. Start exploring our services.",
            'icon' => 'fa-hand-wave',
            'color' => 'indigo',
        ]);
    }

    /**
     * General announcement to all members
     */
    public static function generalAnnouncement(CooperativeAnnouncement $announcement)
    {
        Notification::createForAllMembers('announcement', [
            'title' => $announcement->title,
            'message' => $announcement->description ?? 'New announcement from the cooperative.',
            'icon' => 'fa-bullhorn',
            'color' => 'blue',
            'announcement_id' => $announcement->id,
            'priority' => $announcement->priority,
        ]);
    }
}
