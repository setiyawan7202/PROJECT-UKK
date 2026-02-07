<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MaintenanceSchedule;
// use App\Notifications\MaintenanceDueNotification; // Future implementation
use Illuminate\Support\Facades\Log;

class CheckMaintenanceDue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:check-due';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for maintenance schedules that are due or approaching due date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking maintenance schedules...');

        $schedules = MaintenanceSchedule::whereNotNull('next_maintenance_at')
            ->where('next_maintenance_at', '<=', now()->addDays(7))
            ->get();

        $count = 0;
        foreach ($schedules as $schedule) {
            /** @var \App\Models\MaintenanceSchedule $schedule */
            $daysUntil = now()->diffInDays($schedule->next_maintenance_at, false);

            // Check based on standard reminder days [7, 3, 0]
            // Calculate exact days integers to match
            $daysUntilInt = (int) ceil($daysUntil); // 7.0 -> 7, 0.5 -> 1 (tomorrow), -1 (overdue)

            if (in_array($daysUntilInt, [7, 3, 0])) {
                $targetName = $schedule->kategori ? $schedule->kategori->nama : ($schedule->barang ? $schedule->barang->nama_barang : 'Unknown');
                $message = "Maintenance Due: {$targetName} is due in {$daysUntilInt} days ({$schedule->next_maintenance_at->format('Y-m-d')}).";

                if ($daysUntilInt == 0) {
                    $message = "URGENT: Maintenance Due TODAY for {$targetName}.";
                }

                $this->info($message);
                Log::info($message);

                // Trigger notifications to Admins
                // Find admins
                $admins = \App\Models\Auth::where('role', 'admin')->orWhere('role', 'superadmin')->get();
                foreach ($admins as $admin) {
                    if ($admin->email) {
                        try {
                            $admin->notify(new \App\Notifications\MaintenanceDueNotification($schedule, $daysUntilInt));
                        } catch (\Exception $e) {
                            Log::error("Failed to notify admin {$admin->email} for maintenance: " . $e->getMessage());
                        }
                    }
                }

                $count++;
            }
        }

        $this->info("Found {$count} schedules requiring reminder.");
    }
}
