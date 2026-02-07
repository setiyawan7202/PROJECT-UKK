<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceSchedule;
use App\Models\MaintenanceLog;
use App\Models\Kategori;
use App\Models\Barang;
use App\Models\BarangUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    /**
     * Display maintenance schedules list
     */
    public function index()
    {
        $schedules = MaintenanceSchedule::with(['kategori', 'barang', 'creator'])
            ->orderBy('next_maintenance_at')
            ->paginate(10);

        // Get upcoming maintenance (next 7 days)
        $upcoming = MaintenanceSchedule::with(['kategori', 'barang'])
            ->dueForReminder()
            ->get();

        return view('admin.maintenance.index', compact('schedules', 'upcoming'));
    }

    /**
     * Show form to create new schedule
     */
    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $barangs = Barang::orderBy('nama_barang')->get();
        return view('admin.maintenance.create', compact('kategoris', 'barangs'));
    }

    /**
     * Store new maintenance schedule
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:kategori,barang',
            'kategori_id' => 'required_if:type,kategori|nullable|exists:kategori,id',
            'barang_id' => 'required_if:type,barang|nullable|exists:barang,id',
            'interval_days' => 'required|integer|min:1',
            'next_maintenance_at' => 'required|date|after_or_equal:today',
            'reminder_days' => 'nullable|array',
        ]);

        $schedule = MaintenanceSchedule::create([
            'kategori_id' => $validated['type'] === 'kategori' ? $validated['kategori_id'] : null,
            'barang_id' => $validated['type'] === 'barang' ? $validated['barang_id'] : null,
            'interval_days' => $validated['interval_days'],
            'next_maintenance_at' => $validated['next_maintenance_at'],
            'reminder_days' => $validated['reminder_days'] ?? [7, 3, 0],
            'created_by' => Auth::id(),
        ]);

        \App\Helpers\ActivityLogger::log('Maintenance Schedule', 'Membuat jadwal maintenance baru');

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Jadwal maintenance berhasil dibuat.');
    }

    /**
     * Edit maintenance schedule
     */
    public function edit(MaintenanceSchedule $maintenance)
    {
        $kategoris = Kategori::orderBy('nama')->get();
        $barangs = Barang::orderBy('nama_barang')->get();
        return view('admin.maintenance.edit', compact('maintenance', 'kategoris', 'barangs'));
    }

    /**
     * Update maintenance schedule
     */
    public function update(Request $request, MaintenanceSchedule $maintenance)
    {
        $validated = $request->validate([
            'interval_days' => 'required|integer|min:1',
            'next_maintenance_at' => 'required|date',
            'reminder_days' => 'nullable|array',
        ]);

        $maintenance->update([
            'interval_days' => $validated['interval_days'],
            'next_maintenance_at' => $validated['next_maintenance_at'],
            'reminder_days' => $validated['reminder_days'] ?? [7, 3, 0],
        ]);

        \App\Helpers\ActivityLogger::log('Maintenance Schedule', 'Mengupdate jadwal maintenance');

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Jadwal maintenance berhasil diupdate.');
    }

    /**
     * Delete maintenance schedule
     */
    public function destroy(MaintenanceSchedule $maintenance)
    {
        $maintenance->delete();

        \App\Helpers\ActivityLogger::log('Maintenance Schedule', 'Menghapus jadwal maintenance');

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Jadwal maintenance berhasil dihapus.');
    }

    /**
     * Display maintenance logs
     */
    public function logs(Request $request)
    {
        $query = MaintenanceLog::with(['barangUnit.barang', 'performer']);

        if ($request->has('unit_id')) {
            $query->where('barang_unit_id', $request->unit_id);
        }

        $logs = $query->orderBy('maintenance_date', 'desc')->paginate(15);

        return view('admin.maintenance.logs', compact('logs'));
    }

    /**
     * Show form to record maintenance activity
     */
    public function createLog()
    {
        $units = BarangUnit::with('barang')->orderBy('kode_unit')->get();
        $schedules = MaintenanceSchedule::with(['kategori', 'barang'])->get();
        return view('admin.maintenance.create-log', compact('units', 'schedules'));
    }

    /**
     * Store maintenance log
     */
    public function storeLog(Request $request)
    {
        $validated = $request->validate([
            'barang_unit_id' => 'required|exists:barang_unit,id',
            'schedule_id' => 'nullable|exists:maintenance_schedules,id',
            'maintenance_date' => 'required|date',
            'description' => 'required|string',
            'technician_name' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $log = MaintenanceLog::create([
            'barang_unit_id' => $validated['barang_unit_id'],
            'schedule_id' => $validated['schedule_id'],
            'maintenance_date' => $validated['maintenance_date'],
            'description' => $validated['description'],
            'technician_name' => $validated['technician_name'],
            'performed_by' => Auth::id(),
            'notes' => $validated['notes'],
        ]);

        // Update schedule's next maintenance date if linked
        if ($validated['schedule_id']) {
            $schedule = MaintenanceSchedule::find($validated['schedule_id']);
            if ($schedule) {
                $schedule->calculateNextMaintenance();
            }
        }

        \App\Helpers\ActivityLogger::log('Maintenance Log', 'Mencatat aktivitas maintenance untuk unit: ' . $log->barangUnit->kode_unit);

        return redirect()->route('admin.maintenance.logs')
            ->with('success', 'Aktivitas maintenance berhasil dicatat.');
    }

    /**
     * Get maintenance history for a specific unit (AJAX)
     */
    public function getUnitHistory($unitId)
    {
        $logs = MaintenanceLog::with('performer')
            ->where('barang_unit_id', $unitId)
            ->orderBy('maintenance_date', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'logs' => $logs,
        ]);
    }

    /**
     * Analytics page
     */
    public function analytics()
    {
        // Most frequently maintained units
        $frequentlyMaintained = MaintenanceLog::selectRaw('barang_unit_id, COUNT(*) as total')
            ->groupBy('barang_unit_id')
            ->orderByDesc('total')
            ->limit(10)
            ->with('barangUnit.barang')
            ->get();

        // Overdue schedules
        $overdue = MaintenanceSchedule::with(['kategori', 'barang'])
            ->whereDate('next_maintenance_at', '<', today())
            ->get();

        // Maintenance this month
        $thisMonth = MaintenanceLog::whereMonth('maintenance_date', now()->month)
            ->whereYear('maintenance_date', now()->year)
            ->count();

        return view('admin.maintenance.analytics', compact('frequentlyMaintained', 'overdue', 'thisMonth'));
    }
}
