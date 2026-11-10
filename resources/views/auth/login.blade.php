<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Kiswa Health Centre III</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green:       #176b4a;
            --green-dark:  #104c36;
            --green-mid:   #1a7d57;
            --lime:        #d7ed78;
            --ink:         #172a22;
            --ink-mid:     #2d4a3a;
            --muted:       #64746b;
            --paper:       #f6f7f1;
            --line:        #dce3d8;
            --coral:       #e78369;
            --display:     'Playfair Display', Georgia, serif;
            --body:        'Plus Jakarta Sans', system-ui, sans-serif;
        }

        html, body {
            height: 100%;
            font-family: var(--body);
            background: var(--paper);
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
        }

        /* ── Layout ─────────────────────────────────────── */
        .login-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* ── Left: Visual panel ─────────────────────────── */
        .login-visual {
            position: relative;
            overflow: hidden;
            background: var(--green-dark);
        }
        .login-visual-bg {
            position: absolute;
            inset: 0;
            background-image: url('/images/banner.png');
            background-size: cover;
            background-position: center;
        }
        .login-visual-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                145deg,
                rgba(16,76,54,.95) 0%,
                rgba(23,107,74,.75) 50%,
                rgba(16,76,54,.88) 100%
            );
        }
        /* Decorative circles */
        .login-visual::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 320px; height: 320px;
            border-radius: 50%;
            border: 1px solid rgba(215,237,120,.15);
            box-shadow: 0 0 0 60px rgba(215,237,120,.05),
                        0 0 0 120px rgba(215,237,120,.03);
            z-index: 1;
        }
        .login-visual::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 240px; height: 240px;
            border-radius: 50%;
            border: 1px solid rgba(215,237,120,.12);
            z-index: 1;
        }

        .login-visual-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            padding: 2.5rem;
        }

        /* Brand in visual */
        .vb-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .vb-logo {
            width: 48px; height: 48px;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid rgba(255,255,255,.25);
            flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(0,0,0,.25);
        }
        .vb-logo img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .vb-name { font-family: var(--display); font-size: 15px; font-weight: 700; color: #fff; line-height: 1.25; }
        .vb-sub  { font-size: 10.5px; font-weight: 500; color: rgba(255,255,255,.6); margin-top: 2px; }

        /* Headline block */
        .vb-headline {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 0;
        }
        .vb-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--lime);
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            margin-bottom: 1.2rem;
        }
        .vb-eyebrow::before {
            content: '';
            width: 22px; height: 2px;
            background: var(--coral);
            border-radius: 2px;
        }
        .vb-h1 {
            font-family: var(--display);
            font-size: clamp(32px, 3.5vw, 48px);
            font-weight: 800;
            line-height: 1.1;
            color: #fff;
            margin-bottom: 1.2rem;
        }
        .vb-h1 em { color: var(--lime); font-style: normal; display: block; }
        .vb-desc {
            font-size: 14px;
            line-height: 1.8;
            color: rgba(255,255,255,.65);
            max-width: 360px;
        }

        /* Stats row */
        .vb-stats {
            display: flex;
            gap: 1.5rem;
            padding-top: 2.5rem;
            border-top: 1px solid rgba(255,255,255,.12);
        }
        .vb-stat-val {
            font-family: var(--display);
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--lime);
            line-height: 1;
        }
        .vb-stat-label {
            font-size: 10px;
            font-weight: 600;
            color: rgba(255,255,255,.5);
            text-transform: uppercase;
            letter-spacing: .07em;
            margin-top: 4px;
        }

        /* Uganda flag strip at bottom of visual */
        .vb-flag {
            display: flex;
            height: 4px;
            border-radius: 0 0 0 0;
            overflow: hidden;
            margin-top: 1.5rem;
        }
        .vb-flag span { flex: 1; }

        /* ── Right: Form panel ──────────────────────────── */
        .login-form-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 3.5rem;
            background: #fff;
            overflow-y: auto;
        }

        .lf-inner { max-width: 400px; width: 100%; margin: 0 auto; }

        /* Form header */
        .lf-header { margin-bottom: 2rem; }
        .lf-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0faf5;
            border: 1px solid #c3e8d4;
            color: var(--green);
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 99px;
            margin-bottom: 1rem;
        }
        .lf-header h1 {
            font-family: var(--display);
            font-size: 2rem;
            font-weight: 800;
            color: var(--ink);
            line-height: 1.2;
            margin-bottom: .4rem;
        }
        .lf-header p {
            font-size: 13.5px;
            color: var(--muted);
        }

        /* Flash alerts */
        .lf-alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: .75rem 1rem;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 1.25rem;
        }
        .lf-alert.success { background: #f0fdf4; border: 1px solid #86efac; color: #166534; }
        .lf-alert.error   { background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; }
        .lf-alert i { font-size: 15px; flex-shrink: 0; }

        /* Form fields */
        .lf-group { margin-bottom: 1.1rem; }
        .lf-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--ink-mid);
            margin-bottom: .45rem;
        }
        .lf-input-wrap { position: relative; }
        .lf-input-icon {
            position: absolute;
            left: .95rem;
            top: 50%; transform: translateY(-50%);
            color: var(--muted);
            font-size: 15px;
            pointer-events: none;
        }
        .lf-input {
            width: 100%;
            padding: .78rem 1rem .78rem 2.65rem;
            border: 1.5px solid var(--line);
            border-radius: 10px;
            font-family: var(--body);
            font-size: 14px;
            color: var(--ink);
            background: var(--paper);
            outline: none;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }
        .lf-input::placeholder { color: #a8b5ae; }
        .lf-input:focus {
            border-color: var(--green);
            background: #fff;
            box-shadow: 0 0 0 3.5px rgba(23,107,74,.12);
        }
        .lf-input.is-invalid { border-color: #ef4444; }
        .lf-input.is-invalid:focus { box-shadow: 0 0 0 3.5px rgba(239,68,68,.1); }

        .lf-pw-toggle {
            position: absolute;
            right: .95rem;
            top: 50%; transform: translateY(-50%);
            background: none; border: none;
            color: var(--muted); font-size: 15px;
            cursor: pointer; padding: 0;
            transition: color .15s;
        }
        .lf-pw-toggle:hover { color: var(--green); }

        .lf-invalid-msg {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11.5px;
            color: #ef4444;
            margin-top: .35rem;
        }

        /* Row: remember + forgot */
        .lf-row-split {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .lf-remember {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 12.5px;
            color: var(--ink-mid);
            cursor: pointer;
        }
        .lf-remember input { accent-color: var(--green); width: 15px; height: 15px; cursor: pointer; }

        /* Submit button */
        .lf-btn {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 10px;
            background: var(--green);
            color: #fff;
            font-family: var(--body);
            font-size: 14.5px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(23,107,74,.30);
            transition: background .18s, box-shadow .18s, transform .12s;
        }
        .lf-btn:hover {
            background: var(--green-dark);
            box-shadow: 0 6px 20px rgba(23,107,74,.38);
            transform: translateY(-1px);
        }
        .lf-btn:active { transform: translateY(0); }
        .lf-btn:disabled { opacity: .7; pointer-events: none; }

        /* Divider */
        .lf-divider {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin: 1.5rem 0 1.1rem;
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
        }
        .lf-divider::before,
        .lf-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--line);
        }

        /* Demo credentials box */
        .lf-demo {
            background: #f8faf7;
            border: 1px solid var(--line);
            border-radius: 12px;
            overflow: hidden;
        }
        .lf-demo-head {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: .65rem 1rem;
            background: #f0f5ef;
            border-bottom: 1px solid var(--line);
            font-size: 10.5px;
            font-weight: 700;
            color: var(--green);
            text-transform: uppercase;
            letter-spacing: .07em;
        }
        .lf-demo-row {
            display: grid;
            grid-template-columns: 1fr auto auto;
            align-items: center;
            gap: .75rem;
            padding: .6rem 1rem;
            border-bottom: 1px solid var(--line);
            cursor: pointer;
            transition: background .12s;
        }
        .lf-demo-row:last-child { border-bottom: none; }
        .lf-demo-row:hover { background: #eef5eb; }
        .lf-demo-role {
            font-size: 12px;
            font-weight: 600;
            color: var(--ink-mid);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .lf-demo-role i { color: var(--green); font-size: 13px; }
        .lf-demo-cred {
            font-size: 11px;
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: var(--ink);
            background: rgba(23,107,74,.08);
            padding: 2px 7px;
            border-radius: 5px;
        }
        .lf-demo-hint {
            font-size: 10px;
            font-weight: 700;
            color: var(--green);
            opacity: 0;
            transition: opacity .15s;
        }
        .lf-demo-row:hover .lf-demo-hint { opacity: 1; }

        /* Footer */
        .lf-footer {
            margin-top: 1.5rem;
            font-size: 11px;
            color: var(--muted);
            text-align: center;
        }

        /* Uganda flag strip top of form */
        .lf-flag {
            display: flex;
            height: 3px;
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .lf-flag span { flex: 1; }

        /* ── Responsive ─────────────────────────────────── */
        @media (max-width: 860px) {
            .login-visual { display: none; }
            .login-form-panel { padding: 2.5rem 1.75rem; }
            .login-wrap { grid-template-columns: 1fr; }
        }
        @media (max-width: 480px) {
            .login-form-panel { padding: 2rem 1.25rem; }
            .lf-header h1 { font-size: 1.7rem; }
        }
    </style>
</head>
<body>
<div class="login-wrap">

    {{-- ═══════════ LEFT VISUAL PANEL ═══════════ --}}
    <div class="login-visual">
        <div class="login-visual-bg"></div>
        <div class="login-visual-overlay"></div>
        <div class="login-visual-content">

            {{-- Brand --}}
            <a class="vb-brand" href="{{ url('/') }}">
                <div class="vb-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Kiswa Health Centre III">
                </div>
                <div>
                    <div class="vb-name">Kiswa Health Centre III</div>
                    <div class="vb-sub">Kampala, Uganda</div>
                </div>
            </a>

            {{-- Headline --}}
            <div class="vb-headline">
                <span class="vb-eyebrow">Staff workspace</span>
                <h1 class="vb-h1">
                    Care starts with
                    <em>good records.</em>
                </h1>
                <p class="vb-desc">
                    A complete medicine inventory and dispensing system built for the pharmacy team at Kiswa Health Centre III.
                </p>
            </div>

            {{-- Stats --}}
            <div>
                <div class="vb-stats">
                    <div>
                        <div class="vb-stat-val">FEFO</div>
                        <div class="vb-stat-label">Dispensing</div>
                    </div>
                    <div>
                        <div class="vb-stat-val">Live</div>
                        <div class="vb-stat-label">Stock Alerts</div>
                    </div>
                    <div>
                        <div class="vb-stat-val">3</div>
                        <div class="vb-stat-label">User Roles</div>
                    </div>
                </div>
                {{-- Uganda flag --}}
                <div class="vb-flag" style="margin-top:1.25rem;">
                    <span style="background:#111;"></span>
                    <span style="background:#fcd205;"></span>
                    <span style="background:#d90000;"></span>
                    <span style="background:#111;"></span>
                    <span style="background:#fcd205;"></span>
                    <span style="background:#d90000;"></span>
                </div>
            </div>

        </div>
    </div>

    {{-- ═══════════ RIGHT FORM PANEL ═══════════ --}}
    <div class="login-form-panel">
        <div class="lf-inner">

            {{-- Uganda flag strip --}}
            <div class="lf-flag">
                <span style="background:#111;"></span>
                <span style="background:#fcd205;"></span>
                <span style="background:#d90000;"></span>
                <span style="background:#111;"></span>
                <span style="background:#fcd205;"></span>
                <span style="background:#d90000;"></span>
            </div>

            {{-- Header --}}
            <div class="lf-header">
                <div class="lf-badge">
                    <i class="bi bi-shield-check-fill"></i> Authorised staff only
                </div>
                <h1>Welcome back</h1>
                <p>Sign in to your Kiswa Health workspace</p>
            </div>

            {{-- Flash messages --}}
            @if(session('success'))
            <div class="lf-alert success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="lf-alert error">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ $errors->first() }}
            </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login.post') }}" id="loginForm" novalidate>
                @csrf

                {{-- Email --}}
                <div class="lf-group">
                    <label class="lf-label" for="email">Email address</label>
                    <div class="lf-input-wrap">
                        <i class="bi bi-envelope lf-input-icon"></i>
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="you@kiswahealth.ug"
                               autocomplete="email" autofocus
                               class="lf-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               required>
                    </div>
                    @error('email')
                    <div class="lf-invalid-msg"><i class="bi bi-x-circle-fill"></i>{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="lf-group">
                    <label class="lf-label" for="password">Password</label>
                    <div class="lf-input-wrap">
                        <i class="bi bi-lock lf-input-icon"></i>
                        <input type="password" id="password" name="password"
                               placeholder="••••••••"
                               autocomplete="current-password"
                               class="lf-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                               required>
                        <button type="button" class="lf-pw-toggle" id="pwToggle" aria-label="Toggle password">
                            <i class="bi bi-eye" id="pwIcon"></i>
                        </button>
                    </div>
                    @error('password')
                    <div class="lf-invalid-msg"><i class="bi bi-x-circle-fill"></i>{{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember --}}
                <div class="lf-row-split">
                    <label class="lf-remember">
                        <input type="checkbox" name="remember" id="remember">
                        Keep me signed in
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="lf-btn" id="signInBtn">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Sign In
                </button>
            </form>

            {{-- Demo accounts --}}
            <div class="lf-divider">Demo accounts</div>

            <div class="lf-demo">
                <div class="lf-demo-head">
                    <i class="bi bi-info-circle"></i>
                    Click a row to auto-fill
                </div>
                <div class="lf-demo-row" onclick="fill('admin@kiswahealth.ug','admin@123')">
                    <span class="lf-demo-role"><i class="bi bi-shield-check"></i> Administrator</span>
                    <span class="lf-demo-cred">admin@123</span>
                    <span class="lf-demo-hint">Fill ↗</span>
                </div>
                <div class="lf-demo-row" onclick="fill('stores@kiswahealth.ug','stores@123')">
                    <span class="lf-demo-role"><i class="bi bi-box-seam"></i> Stores In-Charge</span>
                    <span class="lf-demo-cred">stores@123</span>
                    <span class="lf-demo-hint">Fill ↗</span>
                </div>
                <div class="lf-demo-row" onclick="fill('pharmacy@kiswahealth.ug','pharmacy@123')">
                    <span class="lf-demo-role"><i class="bi bi-capsule"></i> Pharmacy Tech</span>
                    <span class="lf-demo-cred">pharmacy@123</span>
                    <span class="lf-demo-hint">Fill ↗</span>
                </div>
            </div>

            <div class="lf-footer">
                &copy; {{ date('Y') }} Kiswa Health Centre III &mdash; Kiswa, Kampala, Uganda
            </div>

        </div>
    </div>

</div>

<script>
    // Password toggle
    document.getElementById('pwToggle').addEventListener('click', function () {
        var inp  = document.getElementById('password');
        var icon = document.getElementById('pwIcon');
        var show = inp.type === 'password';
        inp.type     = show ? 'text' : 'password';
        icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
    });

    // Auto-fill demo credentials
    function fill(email, pw) {
        document.getElementById('email').value    = email;
        document.getElementById('password').value = pw;
        document.getElementById('email').focus();
    }

    // Loading state
    document.getElementById('loginForm').addEventListener('submit', function () {
        var btn = document.getElementById('signInBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" style="width:16px;height:16px;border-width:2px;"></span> Signing in…';
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
