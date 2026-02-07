<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\MaintenanceSchedule;

class MaintenanceDueNotification extends Notification
{
    use Queueable;

    public $schedule;
    public $daysUntil;

    /**
     * Create a new notification instance.
     */
    public function __construct(MaintenanceSchedule $schedule, int $daysUntil)
    {
        $this->schedule = $schedule;
        $this->daysUntil = $daysUntil;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $targetName = $this->schedule->kategori ? $this->schedule->kategori->nama_kategori : ($this->schedule->barang ? $this->schedule->barang->nama_barang : 'Unknown');

        $subject = $this->daysUntil <= 0
            ? '🚨 MAINTENANCE DUE: ' . $targetName
            : '🛠️ Reminder Maintenance: ' . $targetName;

        return (new MailMessage)
            ->subject($subject)
            ->view('mail.maintenance_due', [
                'schedule' => $this->schedule,
                'daysUntil' => $this->daysUntil,
                'notifiable' => $notifiable
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
