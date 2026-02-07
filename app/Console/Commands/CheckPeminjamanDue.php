<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Notifications\PeminjamanDueReminderNotification;
use Illuminate\Support\Facades\Log;

class CheckPeminjamanDue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peminjaman:check-due';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for peminjaman that are due soon and send email reminders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking peminjaman due dates...');

        // Get active peminjaman that are due within 1 day (H-1 and H-0)
        $peminjamans = Peminjaman::where('status', 'dipinjam')
            ->whereDate('tgl_kembali_rencana', '>=', now()->toDateString())
            ->whereDate('tgl_kembali_rencana', '<=', now()->addDay()->toDateString())
            ->with(['user', 'barang', 'barangUnit'])
            ->get();

        $count = 0;
        foreach ($peminjamans as $peminjaman) {
            /** @var \App\Models\Peminjaman $peminjaman */
            $daysUntil = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($peminjaman->tgl_kembali_rencana)->startOfDay(), false);
            $daysUntilInt = (int) $daysUntil;

            // Only send for H-0 (today) and H-1 (tomorrow)
            if (in_array($daysUntilInt, [0, 1])) {
                $user = $peminjaman->user;

                if ($user && $user->email) {
                    try {
                        $user->notify(new PeminjamanDueReminderNotification($peminjaman, $daysUntilInt));

                        $this->info("Reminder sent to {$user->email} for {$peminjaman->kode} (due in {$daysUntilInt} days)");
                        Log::info("Due reminder sent", [
                            'kode' => $peminjaman->kode,
                            'email' => $user->email,
                            'days_until' => $daysUntilInt
                        ]);

                        $count++;
                    } catch (\Exception $e) {
                        $this->error("Failed to send reminder for {$peminjaman->kode}: " . $e->getMessage());
                        Log::error("Failed to send due reminder", [
                            'kode' => $peminjaman->kode,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }
        }

        $this->info("Sent {$count} reminder emails.");

        return 0;
    }
}
