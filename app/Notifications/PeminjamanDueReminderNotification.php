<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Peminjaman;

class PeminjamanDueReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Peminjaman $peminjaman;
    protected int $daysUntilDue;

    /**
     * Create a new notification instance.
     */
    public function __construct(Peminjaman $peminjaman, int $daysUntilDue)
    {
        $this->peminjaman = $peminjaman;
        $this->daysUntilDue = $daysUntilDue;
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
        $urgency = $this->daysUntilDue == 0 ? '🚨' : ($this->daysUntilDue == 1 ? '⚠️' : '');
        $subject = $this->daysUntilDue == 0
            ? '🚨 JATUH TEMPO HARI INI - ' . $this->peminjaman->kode
            : '⚠️ Reminder Pengembalian - ' . $this->peminjaman->kode;

        return (new MailMessage)
            ->subject($subject)
            ->view('mail.peminjaman_due_reminder', [
                'peminjaman' => $this->peminjaman,
                'daysUntilDue' => $this->daysUntilDue,
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
            'peminjaman_id' => $this->peminjaman->id,
            'kode' => $this->peminjaman->kode,
            'days_until_due' => $this->daysUntilDue,
        ];
    }
}
