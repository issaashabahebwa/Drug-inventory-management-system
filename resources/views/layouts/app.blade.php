<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Kiswa Health Centre III</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- Theme restore: runs before paint to prevent flash of wrong theme --}}
    <script>
        (function(){
            var t = localStorage.getItem('kiswa-theme');
            if (t === 'dark') document.documentElement.setAttribute('data-theme','dark');
        })();
    </script>

    @stack('styles')
</head>
<body>

@php
    $navLowStock   = \App\Models\Drug::where('is_active', true)->whereRaw('total_quantity <= reorder_level')->count();
    $navNearExpiry = \App\Models\StockBatch::where('status','active')
                        ->where('quantity_remaining','>',0)
                        ->whereBetween('expiry_date',[now(),now()->addDays(90)])->count();
    $user = auth()->user();
@endphp

{{-- ══════════════════════════════════════════════════════
     SIDEBAR
══════════════════════════════════════════════════════ --}}
<nav id="sidebar" aria-label="Main navigation">

    {{-- Brand --}}
    <div class="sidebar-brand d-flex align-items-center gap-2">
        <div class="sidebar-brand-mark">
            <img src="{{ asset('images/logo.png') }}" alt="Kiswa Health Centre III Logo">
        </div>
        <div>
            <div class="sidebar-brand-text">Kiswa Health Centre III</div>
            <div class="sidebar-brand-sub">Drug Inventory System</div>
        </div>
    </div>

    {{-- Alert summary pills --}}
    @if($navLowStock > 0 || $navNearExpiry > 0)
    <div class="sidebar-alerts">
        @if($navLowStock > 0)
        <a href="{{ route('procurement.create') }}" class="sidebar-alert-pill amber text-decoration-none">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ $navLowStock }} low stock
            <span class="pill-count">!</span>
        </a>
        @endif
        @if($navNearExpiry > 0)
        <a href="{{ route('reports.expiry') }}" class="sidebar-alert-pill red text-decoration-none">
            <i class="bi bi-calendar-x-fill"></i>
            {{ $navNearExpiry }} near expiry
            <span class="pill-count">!</span>
        </a>
        @endif
    </div>
    @endif

    {{-- Navigation --}}
    <ul class="nav flex-column pb-2" style="flex:1;">

        <li class="nav-section-label">Main</li>

        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
                @if($navLowStock + $navNearExpiry > 0)
                <span class="badge bg-danger ms-auto">{{ $navLowStock + $navNearExpiry }}</span>
                @endif
            </a>
        </li>

        <li class="nav-section-label">Inventory</li>

        <li class="nav-item">
            <a href="{{ route('drugs.index') }}"
               class="nav-link {{ request()->routeIs('drugs.*') ? 'active' : '' }}">
                <i class="bi bi-capsule"></i>
                <span>Drug Register</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('stock.index') }}"
               class="nav-link {{ request()->routeIs('stock.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>Stock Batches</span>
                @if($navNearExpiry > 0)
                <span class="badge bg-warning text-dark ms-auto">{{ $navNearExpiry }}</span>
                @endif
            </a>
        </li>

        <li class="nav-section-label">Pharmacy</li>

        <li class="nav-item">
            <a href="{{ route('dispensing.create') }}"
               class="nav-link {{ request()->routeIs('dispensing.create') ? 'active' : '' }}">
                <i class="bi bi-clipboard2-pulse"></i>
                <span>Dispense Drug</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('dispensing.index') }}"
               class="nav-link {{ request()->routeIs('dispensing.index') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i>
                <span>Dispensing Log</span>
            </a>
        </li>

        <li class="nav-section-label">Procurement</li>

        <li class="nav-item">
            <a href="{{ route('suppliers.index') }}"
               class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i>
                <span>Suppliers</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('procurement.index') }}"
               class="nav-link {{ request()->routeIs('procurement.*') ? 'active' : '' }}">
                <i class="bi bi-cart3"></i>
                <span>Procurement</span>
                @if($navLowStock > 0)
                <span class="badge bg-warning text-dark ms-auto">{{ $navLowStock }}</span>
                @endif
            </a>
        </li>

        <li class="nav-section-label">Reports</li>

        <li class="nav-item">
            <a href="{{ route('reports.stock-balance') }}"
               class="nav-link {{ request()->routeIs('reports.stock-balance') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i>
                <span>Stock Balance</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('reports.expiry') }}"
               class="nav-link {{ request()->routeIs('reports.expiry') ? 'active' : '' }}">
                <i class="bi bi-calendar-x"></i>
                <span>Expiry Report</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('reports.dispensing') }}"
               class="nav-link {{ request()->routeIs('reports.dispensing') ? 'active' : '' }}">
                <i class="bi bi-graph-up"></i>
                <span>Dispensing Report</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('reports.usage-pattern') }}"
               class="nav-link {{ request()->routeIs('reports.usage-pattern') ? 'active' : '' }}">
                <i class="bi bi-activity"></i>
                <span>Usage Pattern</span>
            </a>
        </li>

        @if($user->isAdmin())
        <li class="nav-section-label">Administration</li>

        <li class="nav-item">
            <a href="{{ route('users.index') }}"
               class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>User Management</span>
            </a>
        </li>
        @endif

    </ul>

    {{-- Back to homepage --}}
    <a href="{{ route('home') }}" class="sidebar-home-link">
        <i class="bi bi-house-door"></i>
        <span>Back to Homepage</span>
        <i class="bi bi-arrow-up-right ms-auto" style="font-size:.6rem;opacity:.5;"></i>
    </a>

    <div class="sidebar-footer">
        v2.0 &mdash; Kiswa Health Centre III
    </div>
</nav>

{{-- Mobile overlay --}}
<div id="sidebarOverlay" class="d-lg-none" aria-hidden="true"></div>

{{-- ══════════════════════════════════════════════════════
     MOH-STYLE HEADER
     Layer 1 — Uganda flag colour strip
     Layer 2 — White branding bar
     Layer 3 — Deep-green navigation bar
══════════════════════════════════════════════════════ --}}
<header id="site-header">

    {{-- ── Layer 1: Uganda flag strip ───────────────────── --}}
    <div class="header-flag-strip no-print">
        <div class="flag-black"></div>
        <div class="flag-yellow"></div>
        <div class="flag-red"></div>
        <div class="flag-black"></div>
        <div class="flag-yellow"></div>
        <div class="flag-red"></div>
    </div>

    {{-- ── Layer 2: White branding bar ──────────────────── --}}
    <div class="header-brand-bar">
        <div class="header-brand-inner">

            {{-- Mobile sidebar toggle --}}
            <button id="sidebarToggle" class="sidebar-mob-toggle d-lg-none" aria-label="Toggle navigation">
                <i class="bi bi-list"></i>
            </button>

            {{-- Coat of arms / logo --}}
            <div class="header-crest">
                @if(config('app.banner_image'))
                    <img src="{{ asset(config('app.banner_image')) }}"
                         alt="Kiswa Health Centre III Crest"
                         class="crest-img">
                @else
                    <div class="crest-circle">
                        <i class="bi bi-shield-fill-check"></i>
                    </div>
                @endif
            </div>

            {{-- Facility name block --}}
            <div class="header-name-block">
                <a href="{{ route('home') }}" class="header-facility-name text-decoration-none d-inline-flex align-items-center gap-1"
                   title="Back to Homepage">
                    Kiswa Health Centre III
                    <i class="bi bi-box-arrow-up-left" style="font-size:.65rem;opacity:.45;"></i>
                </a>
                <div class="header-facility-sub">Drug Inventory &amp; Dispensing System &nbsp;|&nbsp; Republic of Uganda</div>
            </div>

            {{-- Right-side: hotline + date --}}
            <div class="header-brand-right d-none d-md-flex">
                <div class="header-hotline">
                    <i class="bi bi-telephone-fill"></i>
                    <div>
                        <div class="hotline-label">Health Hotline</div>
                        <div class="hotline-number">0800-100-066</div>
                    </div>
                </div>
                <div class="header-brand-divider"></div>
                <div class="header-date">
                    <i class="bi bi-calendar3"></i>
                    <span>{{ now()->format('D, d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Layer 3: Deep-green nav bar ──────────────────── --}}
    <nav class="header-navbar" aria-label="Primary navigation">
        <div class="header-navbar-inner">

            {{-- Page context (title + breadcrumb) --}}
            <div class="navbar-page-ctx">
                <span class="navbar-page-title">@yield('page-title', 'Dashboard')</span>
                @hasSection('breadcrumb')
                <span class="navbar-sep d-none d-md-inline">›</span>
                <span class="navbar-breadcrumb d-none d-md-inline">@yield('breadcrumb')</span>
                @endif
            </div>

            {{-- Right: alerts + user --}}
            <div class="navbar-actions">

                {{-- Notifications --}}
                <div class="dropdown">
                    <button class="navbar-notif-btn {{ ($navLowStock + $navNearExpiry) > 0 ? 'has-alerts' : '' }}"
                            data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications"
                            data-bs-toggle="tooltip"
                            title="{{ $navLowStock }} low-stock · {{ $navNearExpiry }} near-expiry">
                        <i class="bi bi-bell{{ ($navLowStock + $navNearExpiry) > 0 ? '-fill' : '' }}"></i>
                        @if(($navLowStock + $navNearExpiry) > 0)
                        <span class="navbar-notif-dot">{{ $navLowStock + $navNearExpiry }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end notif-panel shadow-lg">
                        <div class="notif-panel-head">
                            <span><i class="bi bi-bell-fill me-1"></i>Notifications</span>
                            @if(($navLowStock + $navNearExpiry) > 0)
                            <span class="notif-new-pill">{{ $navLowStock + $navNearExpiry }} new</span>
                            @endif
                        </div>

                        @if($navLowStock == 0 && $navNearExpiry == 0)
                        <div class="notif-clear">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>All clear — no alerts right now</span>
                        </div>
                        @endif

                        @if($navLowStock > 0)
                        <a href="{{ route('procurement.create') }}" class="notif-row amber">
                            <div class="notif-row-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
                            <div class="notif-row-body">
                                <div class="notif-row-title">Low Stock Alert</div>
                                <div class="notif-row-sub">{{ $navLowStock }} drug(s) at or below reorder level</div>
                            </div>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                        @endif

                        @if($navNearExpiry > 0)
                        <a href="{{ route('reports.expiry') }}" class="notif-row red">
                            <div class="notif-row-icon"><i class="bi bi-calendar-x-fill"></i></div>
                            <div class="notif-row-body">
                                <div class="notif-row-title">Expiry Warning</div>
                                <div class="notif-row-sub">{{ $navNearExpiry }} batch(es) expire within 90 days</div>
                            </div>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                        @endif

                        <div class="notif-panel-foot">
                            <a href="{{ route('dashboard') }}">Go to Dashboard →</a>
                        </div>
                    </div>
                </div>

                {{-- User dropdown --}}
                <div class="dropdown">
                    <a href="#" class="navbar-user-chip" data-bs-toggle="dropdown"
                       aria-expanded="false" aria-haspopup="true">
                        @if($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                 style="width:28px;height:28px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                        @else
                            <div class="navbar-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        @endif
                        <div class="d-none d-sm-block">
                            <div class="navbar-uname">{{ explode(' ', $user->name)[0] }}</div>
                            <div class="navbar-urole">{{ $user->role_label }}</div>
                        </div>
                        <i class="bi bi-chevron-down navbar-chevron"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg mt-1 user-menu">
                        <li>
                            <div class="user-menu-profile">
                                @if($user->avatar_url)
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                         style="width:40px;height:40px;border-radius:50%;object-fit:cover;flex-shrink:0;border:2px solid rgba(255,255,255,.3);">
                                @else
                                    <div class="user-menu-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                @endif
                                <div>
                                    <div class="user-menu-name">{{ $user->name }}</div>
                                    <div class="user-menu-email">{{ $user->email }}</div>
                                    <span class="user-menu-badge">{{ $user->role_label }}</span>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile') }}">
                                <i class="bi bi-person-circle"></i> My Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile') }}#tab-theme"
                               onclick="document.querySelector('[data-tab=\'tab-theme\']')?.click()">
                                <i class="bi bi-palette"></i> Appearance
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        @if($user->isAdmin())
                        <li>
                            <a class="dropdown-item" href="{{ route('users.index') }}">
                                <i class="bi bi-people-fill"></i> User Management
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Sign Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

            </div>{{-- /.navbar-actions --}}
        </div>{{-- /.header-navbar-inner --}}
    </nav>

</header>{{-- /#site-header --}}

{{-- ══════════════════════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════════════════════ --}}
<main id="main-content">

    {{-- Global system alert banners --}}
    @if($navLowStock > 0)
    <div class="alert-banner warning alert-dismissible mb-3 no-print" role="alert">
        <div class="alert-banner-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
        <div class="alert-banner-body">
            <strong>Low Stock Alert:</strong>
            {{ $navLowStock }} drug(s) are at or below their reorder level.
            <a href="{{ route('procurement.create') }}" class="ms-2">Generate Requisition →</a>
        </div>
        <button type="button" class="btn-close btn-sm ms-auto" data-bs-dismiss="alert" aria-label="Close"
                style="font-size:.7rem;"></button>
    </div>
    @endif

    @if($navNearExpiry > 0)
    <div class="alert-banner danger alert-dismissible mb-3 no-print" role="alert">
        <div class="alert-banner-icon"><i class="bi bi-calendar-x-fill"></i></div>
        <div class="alert-banner-body">
            <strong>Expiry Warning:</strong>
            {{ $navNearExpiry }} batch(es) will expire within 90 days.
            <a href="{{ route('reports.expiry') }}" class="ms-2">View Expiry Report →</a>
        </div>
        <button type="button" class="btn-close btn-sm ms-auto" data-bs-dismiss="alert" aria-label="Close"
                style="font-size:.7rem;"></button>
    </div>
    @endif

    {{-- Session flash --}}
    @if(session('success'))
    <div class="alert-banner success alert-dismissible alert-auto-dismiss mb-3" role="alert">
        <div class="alert-banner-icon"><i class="bi bi-check-circle-fill"></i></div>
        <div class="alert-banner-body">{{ session('success') }}</div>
        <button type="button" class="btn-close btn-sm ms-auto" data-bs-dismiss="alert" aria-label="Close"
                style="font-size:.7rem;"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-banner danger alert-dismissible alert-auto-dismiss mb-3" role="alert">
        <div class="alert-banner-icon"><i class="bi bi-x-circle-fill"></i></div>
        <div class="alert-banner-body">{{ session('error') }}</div>
        <button type="button" class="btn-close btn-sm ms-auto" data-bs-dismiss="alert" aria-label="Close"
                style="font-size:.7rem;"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert-banner danger alert-dismissible mb-3" role="alert">
        <div class="alert-banner-icon"><i class="bi bi-exclamation-circle-fill"></i></div>
        <div class="alert-banner-body">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $error)
                <li style="font-size:.8rem;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close btn-sm ms-auto" data-bs-dismiss="alert" aria-label="Close"
                style="font-size:.7rem;"></button>
    </div>
    @endif

    @yield('content')



</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
