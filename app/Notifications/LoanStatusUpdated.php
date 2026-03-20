<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Loan;

class LoanStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var string */
    protected $status;

    /** @var Loan */
    protected $loan;

    public function __construct(string $status, Loan $loan)
    {
        $this->status = $status;
        $this->loan = $loan;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->status === 'approved' ? 'Your Loan Has Been Approved' : 'Your Loan Application Update';

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->first_name . '!')
            ->line('Your loan #' . $this->loan->id . ' has been ' . $this->status . '.')
            ->line('Amount: ₱' . number_format((float) $this->loan->amount, 2))
            ->action('View Dashboard', route('user.dashboard'))
            ->line('Thank you for being a valued member.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Your loan #' . $this->loan->id . ' has been ' . $this->status . '.',
            'loan_id' => $this->loan->id,
            'status' => $this->status,
            'amount' => (float) $this->loan->amount,
            'url' => route('user.dashboard'),
        ];
    }
}


