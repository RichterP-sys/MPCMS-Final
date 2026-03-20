<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class MemberAccountUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The admin who made the update.
     *
     * @var string
     */
    public $adminName;

    /**
     * The time of the update.
     *
     * @var string
     */
    public $updateTime;

    /**
     * The fields that were updated.
     *
     * @var array
     */
    public $updatedFields;

    /**
     * Create a new notification instance.
     *
     * @param string $adminName
     * @param array $updatedFields
     * @return void
     */
    public function __construct($adminName, array $updatedFields = [])
    {
        $this->adminName = $adminName;
        $this->updateTime = Carbon::now()->toDateTimeString();
        $this->updatedFields = $updatedFields;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('Your Account Has Been Updated')
            ->greeting('Hello ' . $notifiable->first_name . '!')
            ->line('Your account information was recently updated by an administrator.');

        if (!empty($this->updatedFields)) {
            $mailMessage->line('The following fields were updated:');
            foreach ($this->updatedFields as $field => $value) {
                $mailMessage->line("- " . ucfirst(str_replace('_', ' ', $field)));
            }
        }

        $mailMessage->line('If you did not request these changes, please contact support immediately.')
            ->action('View Your Account', route('user.dashboard'))
            ->line('Thank you for using our service!');

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'admin_name' => $this->adminName,
            'updated_at' => $this->updateTime,
            'updated_fields' => $this->updatedFields,
            'message' => 'Your account information was updated by ' . $this->adminName . '.',
            'url' => route('user.dashboard'),
        ];
    }
}
