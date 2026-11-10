<?php

namespace App\Http\Controllers;

use App\Models\DispensingRecord;
use App\Models\Drug;
use App\Models\StockBatch;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // ── Summary cards ──────────────────────────────────────────────
        $totalDrugs        = Drug::where('is_active', true)->count();
        $totalBatches      = StockBatch::where('status', 'active')->count();
        $todayDispensed    = DispensingRecord::whereDate('dispensed_date', $today)->count();

        // ── Low-stock drugs ────────────────────────────────────────────
        $lowStockDrugs = Drug::where('is_active', true)
            ->whereRaw('total_quantity <= reorder_level')
            ->orderBy('total_quantity')
            ->get();

        // ── Near-expiry batches (within 90 days) ──────────────────────
        $nearExpiryBatches = StockBatch::with('drug')
            ->where('status', 'active')
            ->where('quantity_remaining', '>', 0)
            ->whereBetween('expiry_date', [$today, $today->copy()->addDays(90)])
            ->orderBy('expiry_date')
            ->get();

        // ── Expired batches ────────────────────────────────────────────
        $expiredBatches = StockBatch::with('drug')
            ->where('status', 'active')
            ->where('expiry_date', '<', $today)
            ->count();

        // ── Recent dispensing (last 7 days) ───────────────────────────
        $recentDispensing = DispensingRecord::with(['drug', 'dispensedBy'])
            ->where('dispensed_date', '>=', $today->copy()->subDays(7))
            ->orderByDesc('dispensed_date')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // ── Category stock summary ─────────────────────────────────────
        $categoryStats = Drug::where('is_active', true)
            ->selectRaw('category, COUNT(*) as drug_count, SUM(total_quantity) as total_qty')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        // ── Monthly dispensing trend (last 6 months) ──────────────────
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $today->copy()->subMonths($i);
            $monthlyTrend[] = [
                'month' => $month->format('M Y'),
                'count' => DispensingRecord::whereYear('dispensed_date', $month->year)
                    ->whereMonth('dispensed_date', $month->month)
                    ->sum('quantity_dispensed'),
            ];
        }

        return view('dashboard', compact(
            'totalDrugs',
            'totalBatches',
            'todayDispensed',
            'lowStockDrugs',
            'nearExpiryBatches',
            'expiredBatches',
            'recentDispensing',
            'categoryStats',
            'monthlyTrend'
        ));
    }
}
