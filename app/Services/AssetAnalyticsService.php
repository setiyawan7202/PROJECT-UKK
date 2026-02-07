<?php

namespace App\Services;

use App\Models\BarangUnit;
use App\Models\Pengembalian;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssetAnalyticsService
{
    /**
     * Get Assets with Risk Assessment
     */
    public function getAssetRisks($limit = 20)
    {
        // 1. Get all units with their history
        $units = BarangUnit::with(['barang', 'barang.kategori'])
            ->where('status', '!=', 'hilang')
            ->get();

        $risks = [];

        foreach ($units as $unit) {
            $riskScore = 0;
            $riskFactors = [];

            // Factor 1: Age (Assuming created_at is purchase date for simplicity)
            $yearsOld = $unit->created_at->diffInYears(now());
            if ($yearsOld > 5) {
                $riskScore += 30;
                $riskFactors[] = "Umur > 5 tahun ({$yearsOld} th)";
            } elseif ($yearsOld > 3) {
                $riskScore += 15;
            }

            // Factor 2: Damage History
            // Count how many times this specific unit returned with damage
            $damageCount = DB::table('pengembalian')
                ->join('peminjaman', 'pengembalian.peminjaman_id', '=', 'peminjaman.id')
                ->where('peminjaman.barang_unit_id', $unit->id)
                ->whereIn('pengembalian.kondisi', ['rusak_ringan', 'rusak_berat'])
                ->count();

            if ($damageCount >= 3) {
                $riskScore += 50;
                $riskFactors[] = "Rusak {$damageCount}x tahun ini";
            } elseif ($damageCount >= 1) {
                $riskScore += 20;
            }

            // Factor 3: Current Status
            if ($unit->status == 'rusak') {
                $riskScore += 20;
            }

            // Recommendation Logic
            $recommendation = 'KEEP';
            $recommendationColor = 'green';

            if ($riskScore >= 70 || ($yearsOld > 5 && $unit->status == 'rusak')) {
                $recommendation = 'REPLACE';
                $recommendationColor = 'red';
            } elseif ($riskScore >= 40 || $unit->status == 'rusak') {
                $recommendation = 'REPAIR';
                $recommendationColor = 'yellow';
            }

            if ($riskScore >= 30) {
                $risks[] = [
                    'unit' => $unit,
                    'score' => min($riskScore, 100),
                    'factors' => implode(', ', $riskFactors),
                    'recommendation' => $recommendation,
                    'color' => $recommendationColor
                ];
            }
        }

        // Sort by risk score desc
        usort($risks, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return array_slice($risks, 0, $limit);
    }

    /**
     * Get Location Stats
     */
    public function getLocationStats()
    {
        // Group assets by Ruangan Location
        // Need to join barang_unit -> barang -> ruangan

        $stats = DB::table('barang_unit')
            ->join('barang', 'barang_unit.barang_id', '=', 'barang.id')
            ->join('ruangan', 'barang.ruangan_id', '=', 'ruangan.id')
            ->whereNull('barang_unit.deleted_at')
            ->select('ruangan.lokasi', DB::raw('count(*) as total'))
            ->groupBy('ruangan.lokasi')
            ->orderByDesc('total')
            ->get();

        return $stats;
    }
}
