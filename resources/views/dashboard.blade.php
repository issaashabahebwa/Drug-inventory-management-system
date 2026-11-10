@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
.greeting-band {
    background: linear-gradient(135deg, #104c36 0%, #176b4a 60%, #1a7d57 100%);
    border-radius: 10px;
    padding: 1.5rem 1.75rem;
    color: #fff;
    position: relative;
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.greeting-band::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(215,237,120,.08);
}
.greeting-band::after {
    content: '';
    position: absolute;
    bottom: -40px; right: 100px;
    width: 140px; height: 140px;
    border-radius: 50%;
    background: rgba(215,237,120,.05);
}
.greeting-banner-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    opacity: 0.18;
    pointer-events: none;
    border-radius: 10px;
}
.greeting-text h2 {
    font-size: 1.3rem;
    font-weight: 800;
    font-family: 'Manrope', sans-serif;
    margin: 0 0 .2rem;
}
.greeting-text p {
    font-size: .78rem;
    opacity: .75;
    margin: 0;
}
.greeting-time-badge {
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.18);
    color: #fff;
    font-size: .72rem;
    font-weight: 600;
    padding: .3rem .75rem;
    border-radius: 99px;
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    margin-bottom: .5rem;
}

/* Chart card */
.chart-card-body {
    padding: 1rem 1.25rem;
}

/* ── Clickable stat cards ───────────────────────────────── */
.stat-card-link {
    display: block;
    text-decoration: none;
    color: inherit;
    border-radius: var(--radius-md);
    outline: none;
}

/* Disable the standalone hover on .stat-card when it's inside a link */
.stat-card-link .stat-card {
    cursor: pointer;
    transition: transform .2s ease, box-shadow .2s ease;
}
.stat-card-link .stat-card:hover {
    transform: none; /* handled by the link wrapper below */
    box-shadow: none;
}

/* Lift + deepen shadow on the whole card when hovering the link */
.stat-card-link:hover .stat-card,
.stat-card-link:focus-visible .stat-card {
    transform: translateY(-5px);
    box-shadow: 0 12px 28px rgba(0,0,0,.22);
}

/* Keyboard focus ring */
.stat-card-link:focus-visible {
    outline: 3px solid rgba(255,255,255,.8);
    outline-offset: 3px;
    border-radius: var(--radius-md);
}

/* "Go →" hint that appears on hover */
.stat-card-hint {
    position: absolute;
    bottom: .65rem;
    right: .85rem;
    font-size: .6rem;
    font-weight: 700;
    color: rgba(255,255,255,.55);
    letter-spacing: .04em;
    display: flex;
    align-items: center;
    gap: 3px;
    transition: color .18s, transform .18s;
    z-index: 1;
}
.stat-card-link:hover .stat-card-hint {
    color: rgba(255,255,255,.95);
    transform: translateX(3px);
}
</style>
@endpush

@section('content')

{{-- ── Greeting band ───────────────────────────────────── --}}
<div class="greeting-band animate-fade-up">
    @if(config('app.banner_image'))
        <img src="{{ asset(config('app.banner_image')) }}"
             alt=""
             class="greeting-banner-img"
             aria-hidden="true">
    @endif
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="greeting-text" style="position:relative;z-index:1;">
            <div class="greeting-time-badge">
                <i class="bi bi-calendar3" style="font-size:.7rem;opacity:.8;"></i>
                {{ now()->format('l, d F Y') }}
            </div>
            <h2>Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }},
                {{ explode(' ', auth()->user()->name)[0] }}</h2>
            <p>Here's what's happening at Kiswa Health Centre III today.</p>
        </div>
        <div class="d-flex gap-2 no-print" style="position:relative;z-index:1;">
            <a href="{{ route('dispensing.create') }}" class="btn btn-sm btn-light fw-600">
                <i class="bi bi-clipboard2-pulse me-1"></i>Dispense Drug
            </a>
            <a href="{{ route('stock.create') }}" class="btn btn-sm btn-outline-light fw-600">
                <i class="bi bi-plus-circle me-1"></i>Receive Stock
            </a>
        </div>
    </div>
</div>

{{-- ── Stat cards ──────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-sm-4 col-lg-2">
        <a href="{{ route('drugs.index') }}" class="stat-card-link" aria-label="View Drug Register">
            <div class="stat-card green animate-fade-up stagger-1">
                <div class="stat-card-icon"><i class="bi bi-capsule"></i></div>
                <div class="stat-card-value counter" data-target="{{ $totalDrugs }}">0</div>
                <div class="stat-card-label">Registered Drugs</div>
                <div class="stat-card-hint"><i class="bi bi-arrow-up-right"></i> View register</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-sm-4 col-lg-2">
        <a href="{{ route('stock.index') }}" class="stat-card-link" aria-label="View Stock Batches">
            <div class="stat-card blue animate-fade-up stagger-2">
                <div class="stat-card-icon"><i class="bi bi-box-seam"></i></div>
                <div class="stat-card-value counter" data-target="{{ $totalBatches }}">0</div>
                <div class="stat-card-label">Active Batches</div>
                <div class="stat-card-hint"><i class="bi bi-arrow-up-right"></i> View batches</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-sm-4 col-lg-2">
        <a href="{{ route('dispensing.index') }}" class="stat-card-link" aria-label="View Today's Dispensing">
            <div class="stat-card teal animate-fade-up stagger-3">
                <div class="stat-card-icon"><i class="bi bi-clipboard2-pulse"></i></div>
                <div class="stat-card-value counter" data-target="{{ $todayDispensed }}">0</div>
                <div class="stat-card-label">Dispensed Today</div>
                <div class="stat-card-hint"><i class="bi bi-arrow-up-right"></i> View log</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-sm-4 col-lg-2">
        <a href="{{ route('procurement.create') }}" class="stat-card-link" aria-label="Generate Requisition for Low Stock">
            <div class="stat-card {{ $lowStockDrugs->count() > 0 ? 'amber' : 'green' }} animate-fade-up stagger-4">
                <div class="stat-card-icon"><i class="bi bi-exclamation-triangle"></i></div>
                <div class="stat-card-value counter" data-target="{{ $lowStockDrugs->count() }}">0</div>
                <div class="stat-card-label">Low Stock Drugs</div>
                <div class="stat-card-hint"><i class="bi bi-arrow-up-right"></i> Requisition</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-sm-4 col-lg-2">
        <a href="{{ route('reports.expiry') }}" class="stat-card-link" aria-label="View Near-Expiry Report">
            <div class="stat-card {{ $nearExpiryBatches->count() > 0 ? 'orange' : 'green' }} animate-fade-up stagger-5">
                <div class="stat-card-icon"><i class="bi bi-calendar-x"></i></div>
                <div class="stat-card-value counter" data-target="{{ $nearExpiryBatches->count() }}">0</div>
                <div class="stat-card-label">Near-Expiry (90d)</div>
                <div class="stat-card-hint"><i class="bi bi-arrow-up-right"></i> Expiry report</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-sm-4 col-lg-2">
        <a href="{{ route('reports.expiry') }}" class="stat-card-link" aria-label="View Expired Batches">
            <div class="stat-card {{ $expiredBatches > 0 ? 'red' : 'green' }} animate-fade-up stagger-6">
                <div class="stat-card-icon"><i class="bi bi-x-circle"></i></div>
                <div class="stat-card-value counter" data-target="{{ $expiredBatches }}">0</div>
                <div class="stat-card-label">Expired Batches</div>
                <div class="stat-card-hint"><i class="bi bi-arrow-up-right"></i> View report</div>
            </div>
        </a>
    </div>
</div>

<div class="row g-3 mb-3">

    {{-- ── Low stock table ─────────────────────────────── --}}
    <div class="col-12 col-xl-6">
        <div class="card h-100 animate-fade-up stagger-2">
            <div class="card-header">
                <div class="card-header-icon" style="background:#fef3c7;color:#d97706;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                Low Stock Alerts
                @if($lowStockDrugs->count() > 0)
                <span class="badge ms-1" style="background:#fef3c7;color:#92400e;">{{ $lowStockDrugs->count() }}</span>
                @endif
                <div class="ms-auto no-print">
                    <a href="{{ route('procurement.create') }}" class="btn btn-sm btn-warning" style="font-size:.72rem;padding:.25rem .65rem;">
                        <i class="bi bi-cart-plus me-1"></i>Generate Requisition
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @if($lowStockDrugs->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon" style="background:#f0fdf4;color:#16a34a;">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <h5>All Adequately Stocked</h5>
                        <p>All registered drugs are above their reorder levels.</p>
                    </div>
                @else
                <div class="table-wrapper">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Drug</th>
                                <th class="text-center">In Stock</th>
                                <th class="text-center">Reorder</th>
                                <th class="text-center">Status</th>
                                <th class="no-print"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockDrugs as $drug)
                            <tr>
                                <td>
                                    <div class="fw-600 fs-sm">{{ $drug->name }}</div>
                                    <div class="fs-xs text-muted">{{ ucfirst($drug->formulation) }}
                                        @if($drug->strength)· {{ $drug->strength }}@endif
                                    </div>
                                    @php $pct = $drug->reorder_level > 0 ? min(100, round($drug->total_quantity / $drug->reorder_level * 100)) : 0; @endphp
                                    <div class="stock-bar mt-1">
                                        <div class="stock-bar-fill {{ $drug->total_quantity == 0 ? 'red' : 'amber' }}"
                                             style="width:{{ $pct }}%"></div>
                                    </div>
                                </td>
                                <td class="text-center fw-700 {{ $drug->total_quantity == 0 ? 'text-danger' : 'text-warning' }}">
                                    {{ number_format($drug->total_quantity) }}
                                </td>
                                <td class="text-center fs-sm text-muted">{{ number_format($drug->reorder_level) }}</td>
                                <td class="text-center">
                                    @if($drug->total_quantity == 0)
                                        <span class="status-pill red">Out of Stock</span>
                                    @else
                                        <span class="status-pill amber">Low Stock</span>
                                    @endif
                                </td>
                                <td class="text-center no-print">
                                    <a href="{{ route('stock.create', ['drug_id' => $drug->id]) }}"
                                       class="btn btn-sm btn-icon btn-outline-success" title="Receive stock">
                                        <i class="bi bi-plus-lg"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Near-expiry batches ──────────────────────────── --}}
    <div class="col-12 col-xl-6">
        <div class="card h-100 animate-fade-up stagger-3">
            <div class="card-header">
                <div class="card-header-icon" style="background:#fee2e2;color:#dc2626;">
                    <i class="bi bi-calendar-x-fill"></i>
                </div>
                Near-Expiry Batches
                @if($nearExpiryBatches->count() > 0)
                <span class="badge ms-1" style="background:#fee2e2;color:#991b1b;">{{ $nearExpiryBatches->count() }}</span>
                @endif
                <a href="{{ route('reports.expiry') }}" class="btn btn-sm ms-auto btn-outline-danger no-print"
                   style="font-size:.72rem;padding:.25rem .65rem;">
                    <i class="bi bi-file-earmark-text me-1"></i>Full Report
                </a>
            </div>
            <div class="card-body p-0">
                @if($nearExpiryBatches->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon" style="background:#f0fdf4;color:#16a34a;">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <h5>No Batches Expiring Soon</h5>
                        <p>No batches expire within the next 90 days.</p>
                    </div>
                @else
                <div class="table-wrapper">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Drug / Batch</th>
                                <th class="text-center">Expires</th>
                                <th class="text-center">Days Left</th>
                                <th class="text-center">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nearExpiryBatches->take(8) as $batch)
                            <tr>
                                <td>
                                    <div class="fw-600 fs-sm">{{ $batch->drug->name }}</div>
                                    <div class="fs-xs text-muted">{{ $batch->batch_number }}</div>
                                </td>
                                <td class="text-center fs-sm fw-500">{{ $batch->expiry_date->format('d M Y') }}</td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $batch->expiry_status }}">
                                        {{ $batch->days_to_expiry }}d
                                    </span>
                                </td>
                                <td class="text-center fs-sm fw-600">{{ number_format($batch->quantity_remaining) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-3">

    {{-- ── Monthly dispensing chart (Chart.js) ─────────── --}}
    <div class="col-12 col-lg-5">
        <div class="card h-100 animate-fade-up stagger-2">
            <div class="card-header">
                <div class="card-header-icon" style="background:#dbeafe;color:#2563eb;">
                    <i class="bi bi-bar-chart-line-fill"></i>
                </div>
                Monthly Dispensing Trend
                <span class="ms-auto fs-xs text-muted">Last 6 months</span>
            </div>
            <div class="chart-card-body" style="padding:1.25rem;">
                <div class="chart-container" style="height:170px;">
                    <canvas id="dispensingChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Category stock donut ─────────────────────────── --}}
    <div class="col-12 col-lg-3">
        <div class="card h-100 animate-fade-up stagger-3">
            <div class="card-header">
                <div class="card-header-icon" style="background:#ede9fe;color:#7c3aed;">
                    <i class="bi bi-pie-chart-fill"></i>
                </div>
                Stock by Category
            </div>
            <div class="card-body p-0">
                <div class="table-wrapper">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th class="text-center">Drugs</th>
                                <th class="text-end">Units</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categoryStats as $cat)
                            <tr>
                                <td class="fs-xs" style="max-width:120px;">
                                    <span title="{{ $cat->category }}">{{ Str::limit($cat->category, 22) }}</span>
                                </td>
                                <td class="text-center fs-xs">{{ $cat->drug_count }}</td>
                                <td class="text-end fs-xs fw-600">{{ number_format($cat->total_qty) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-muted text-center py-3 fs-sm">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Recent dispensing feed ───────────────────────── --}}
    <div class="col-12 col-lg-4">
        <div class="card h-100 animate-fade-up stagger-4">
            <div class="card-header">
                <div class="card-header-icon" style="background:#f0fdf4;color:#16a34a;">
                    <i class="bi bi-clock-history"></i>
                </div>
                Recent Dispensing
                <a href="{{ route('dispensing.index') }}" class="btn btn-sm btn-link p-0 ms-auto fs-xs no-print">
                    View all →
                </a>
            </div>
            <div class="card-body p-0" style="max-height:280px;overflow-y:auto;">
                @forelse($recentDispensing as $rec)
                <div class="d-flex align-items-start gap-2 px-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}"
                     style="transition:background .12s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                    <div class="flex-shrink-0 mt-1">
                        <div style="width:7px;height:7px;border-radius:50%;background:var(--k-green);margin-top:4px;"></div>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-600 fs-sm text-truncate">{{ $rec->drug->name }}</div>
                        <div class="fs-xs text-muted">
                            {{ $rec->patient_reference }}
                            &bull; <span class="fw-600">{{ number_format($rec->quantity_dispensed) }}</span> units
                        </div>
                        <div class="fs-xs" style="color:#94a3b8;">
                            {{ $rec->dispensed_date->format('d M Y') }}
                            &bull; {{ $rec->dispensedBy->name ?? 'Unknown' }}
                        </div>
                    </div>
                    <a href="{{ route('dispensing.show', $rec) }}" class="btn btn-sm btn-icon btn-outline-secondary flex-shrink-0 no-print"
                       style="width:26px;height:26px;font-size:.65rem;" title="View">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>
                @empty
                <div class="empty-state py-4">
                    <div class="empty-state-icon">
                        <i class="bi bi-clipboard2"></i>
                    </div>
                    <p class="fs-sm text-muted mb-0">No dispensing in the last 7 days.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Animated counters ─────────────────────────────────
    document.querySelectorAll('.counter[data-target]').forEach(function (el) {
        const target = parseInt(el.dataset.target, 10);
        if (isNaN(target) || target === 0) { el.textContent = '0'; return; }
        const duration = 900;
        const step     = Math.ceil(duration / (target > 50 ? 50 : target));
        let current    = 0;
        const timer    = setInterval(function () {
            current += Math.max(1, Math.ceil(target / 40));
            if (current >= target) { current = target; clearInterval(timer); }
            el.textContent = current.toLocaleString();
        }, step);
    });

    // ── Dispensing bar chart (Chart.js) ───────────────────
    const ctx = document.getElementById('dispensingChart');
    if (ctx) {
        const labels = @json(array_column($monthlyTrend, 'month'));
        const data   = @json(array_column($monthlyTrend, 'count'));

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Units Dispensed',
                    data: data,
                    backgroundColor: 'rgba(22,163,74,.7)',
                    borderColor: '#16a34a',
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false,
                    hoverBackgroundColor: '#16a34a',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 11, weight: '600' },
                        bodyFont: { size: 11 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: ctx => ' ' + ctx.parsed.y.toLocaleString() + ' units'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 }, color: '#94a3b8' }
                    },
                    y: {
                        grid: { color: 'rgba(0,0,0,.04)' },
                        ticks: {
                            font: { size: 10 },
                            color: '#94a3b8',
                            callback: v => v > 999 ? (v/1000).toFixed(1)+'k' : v
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
</script>
@endpush
