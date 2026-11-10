<?php

namespace App\Http\Controllers;

use App\Models\DispensingRecord;
use App\Models\Drug;
use App\Models\StockBatch;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // ── 1. Stock Balance Report ────────────────────────────────────────
    public function stockBalance(Request $request)
    {
        $query = Drug::where('is_active', true);

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($status = $request->input('status')) {
            match ($status) {
                'adequate' => $query->whereRaw('total_quantity > reorder_level'),
                'low'      => $query->whereRaw('total_quantity <= reorder_level AND total_quantity > 0'),
                'out'      => $query->where('total_quantity', 0),
                default    => null,
            };
        }

        $drugs      = $query->orderBy('category')->orderBy('name')->get();
        $categories = Drug::categories();

        $summary = [
            'total'    => $drugs->count(),
            'adequate' => $drugs->filter(fn($d) => $d->stock_status === 'adequate')->count(),
            'low'      => $drugs->filter(fn($d) => $d->stock_status === 'low')->count(),
            'out'      => $drugs->filter(fn($d) => $d->stock_status === 'out_of_stock')->count(),
        ];

        return view('reports.stock-balance', compact('drugs', 'categories', 'summary'));
    }

    // ── 2. Expiry Report ───────────────────────────────────────────────
    public function expiry(Request $request)
    {
        $today  = Carbon::today();
        $window = (int) $request->input('window', 90);  // days ahead

        $query = StockBatch::with(['drug', 'supplier'])
            ->where('quantity_remaining', '>', 0);

        if ($request->input('include_expired')) {
            $query->where('expiry_date', '<=', $today->copy()->addDays($window));
        } else {
            $query->whereBetween('expiry_date', [$today, $today->copy()->addDays($window)]);
        }

        $batches = $query->orderBy('expiry_date')->get();

        $expired  = StockBatch::where('expiry_date', '<', $today)->where('quantity_remaining', '>', 0)->count();
        $critical = StockBatch::whereBetween('expiry_date', [$today, $today->copy()->addDays(30)])->where('quantity_remaining', '>', 0)->count();
        $warning  = StockBatch::whereBetween('expiry_date', [$today->copy()->addDays(31), $today->copy()->addDays(60)])->where('quantity_remaining', '>', 0)->count();
        $soon     = StockBatch::whereBetween('expiry_date', [$today->copy()->addDays(61), $today->copy()->addDays(90)])->where('quantity_remaining', '>', 0)->count();

        return view('reports.expiry', compact('batches', 'window', 'expired', 'critical', 'warning', 'soon'));
    }

    // ── 3. Dispensing Summary Report ──────────────────────────────────
    public function dispensing(Request $request)
    {
        $from = $request->input('date_from', Carbon::today()->subDays(30)->toDateString());
        $to   = $request->input('date_to',   Carbon::today()->toDateString());

        $records = DispensingRecord::with(['drug', 'dispensedBy'])
            ->whereBetween('dispensed_date', [$from, $to])
            ->orderByDesc('dispensed_date')
            ->get();

        // Top drugs by quantity dispensed
        $topDrugs = DispensingRecord::with('drug')
            ->whereBetween('dispensed_date', [$from, $to])
            ->selectRaw('drug_id, SUM(quantity_dispensed) as total_qty, COUNT(*) as transaction_count')
            ->groupBy('drug_id')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // Daily totals for the period
        $dailyTotals = DispensingRecord::whereBetween('dispensed_date', [$from, $to])
            ->selectRaw('dispensed_date, SUM(quantity_dispensed) as total_qty, COUNT(*) as transactions')
            ->groupBy('dispensed_date')
            ->orderBy('dispensed_date')
            ->get();

        $totalUnits        = $records->sum('quantity_dispensed');
        $totalTransactions = $records->count();

        return view('reports.dispensing', compact(
            'records', 'topDrugs', 'dailyTotals',
            'totalUnits', 'totalTransactions', 'from', 'to'
        ));
    }

    // ── 4. Usage Pattern Report ────────────────────────────────────────
    public function usagePattern(Request $request)
    {
        $months = (int) $request->input('months', 3);

        $from = Carbon::today()->subMonths($months)->startOfMonth();

        $usageData = DispensingRecord::with('drug')
            ->where('dispensed_date', '>=', $from)
            ->selectRaw('drug_id, SUM(quantity_dispensed) as total_dispensed')
            ->groupBy('drug_id')
            ->orderByDesc('total_dispensed')
            ->get();

        // Classify as fast/slow moving
        $avg       = $usageData->avg('total_dispensed') ?? 0;
        $fastMoving = $usageData->filter(fn($r) => $r->total_dispensed >= $avg);
        $slowMoving = $usageData->filter(fn($r) => $r->total_dispensed < $avg);

        return view('reports.usage-pattern', compact(
            'usageData', 'fastMoving', 'slowMoving', 'months', 'avg'
        ));
    }
}
