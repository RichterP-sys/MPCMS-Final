<?php

namespace App\Console\Commands;

use App\Models\Loan;
use App\Models\Notification;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendLoanReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'loans:send-reminders';

    /**
     * The console command description.
     */
    protected $description = 'Send loan due date reminders to members';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for loans due soon or overdue...');
        
        $loans = Loan::with('member')
            ->where('status', 'approved')
            ->where('remaining_balance', '>', 0)
            ->whereNotNull('approval_date')
            ->get();
        
        $remindersSent = 0;
        $overdueSent = 0;
        
        foreach ($loans as $loan) {
            // Parse loan_term string to get months (e.g. "6 months", "1 year")
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
            
            // Check if notification was already sent today
            $existingNotification = Notification::where('notifiable_type', \App\Models\Member::class)
                ->where('notifiable_id', $loan->member_id)
                ->whereIn('type', ['loan_due_soon', 'loan_overdue'])
                ->whereDate('created_at', today())
                ->where('data->loan_id', $loan->id)
                ->exists();
            
            if ($existingNotification) {
                continue;
            }
            
            // Send overdue notification
            if ($daysUntilDue < 0) {
                NotificationService::loanOverdue($loan, abs($daysUntilDue));
                $overdueSent++;
                $this->warn("Overdue notice sent to {$loan->member->first_name} {$loan->member->last_name} - {$daysUntilDue} days");
            }
            // Send reminder for loans due within 7 days
            elseif ($daysUntilDue <= 7 && $daysUntilDue >= 0) {
                NotificationService::loanDueSoon($loan, $daysUntilDue);
                $remindersSent++;
                $this->info("Reminder sent to {$loan->member->first_name} {$loan->member->last_name} - {$daysUntilDue} days until due");
            }
            // Send reminder for loans due within 30 days (weekly reminder)
            elseif ($daysUntilDue <= 30 && $daysUntilDue > 7) {
                // Only send weekly reminders (on the same day of week as due date)
                if (now()->dayOfWeek === $dueDate->dayOfWeek) {
                    NotificationService::loanDueSoon($loan, $daysUntilDue);
                    $remindersSent++;
                    $this->info("Weekly reminder sent to {$loan->member->first_name} {$loan->member->last_name} - {$daysUntilDue} days until due");
                }
            }
        }
        
        $this->info("Done! Sent {$remindersSent} reminders and {$overdueSent} overdue notices.");
        
        return Command::SUCCESS;
    }
}
