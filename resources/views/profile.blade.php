@extends('layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@push('styles')
<style>
/* ── Profile layout ──────────────────────────────────────── */
.profile-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 1.5rem;
    align-items: start;
}

/* ── Avatar card ─────────────────────────────────────────── */
.avatar-card {
    text-align: center;
}
.avatar-wrap {
    position: relative;
    display: inline-block;
    margin-bottom: 1rem;
}
.avatar-img {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--border-color);
    background: var(--k-blue-xlight);
}
.avatar-initials {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--moh-green), var(--moh-green-light));
    color: #fff;
    font-size: 2.4rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 4px solid var(--border-color);
}
.avatar-upload-btn {
    position: absolute;
    bottom: 4px;
    right: 4px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: var(--moh-green);
    color: #fff;
    border: 2px solid #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .75rem;
    cursor: pointer;
    transition: background .15s;
}
.avatar-upload-btn:hover { background: var(--moh-green-dark); }

.profile-name    { font-size: 1.1rem; font-weight: 800; color: var(--k-blue-deep); margin: 0 0 2px; }
.profile-role    { font-size: .75rem; color: var(--text-muted); margin-bottom: .3rem; }
.profile-email   { font-size: .78rem; color: var(--text-secondary); }

/* ── Section tabs ────────────────────────────────────────── */
.profile-tab-nav {
    display: flex;
    gap: .25rem;
    border-bottom: 2px solid var(--border-color);
    margin-bottom: 1.5rem;
}
.profile-tab-btn {
    padding: .6rem 1.1rem;
    font-size: .82rem;
    font-weight: 600;
    color: var(--text-muted);
    background: transparent;
    border: none;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: .4rem;
    transition: color .15s, border-color .15s;
}
.profile-tab-btn:hover { color: var(--moh-green); }
.profile-tab-btn.active {
    color: var(--moh-green);
    border-bottom-color: var(--moh-green);
}
.profile-tab-panel { display: none; }
.profile-tab-panel.active { display: block; }

/* ── Theme toggle ────────────────────────────────────────── */
.theme-toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.25rem;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    background: var(--bg-muted);
    margin-bottom: 1rem;
}
.theme-toggle-info strong { font-size: .9rem; font-weight: 700; color: var(--text-primary); display: block; }
.theme-toggle-info span   { font-size: .75rem; color: var(--text-muted); }

/* Toggle switch */
.theme-switch {
    position: relative;
    width: 52px;
    height: 28px;
    flex-shrink: 0;
}
.theme-switch input { opacity: 0; width: 0; height: 0; }
.theme-switch-track {
    position: absolute;
    inset: 0;
    background: var(--border-color);
    border-radius: 99px;
    cursor: pointer;
    transition: background .2s;
}
.theme-switch-track::after {
    content: '';
    position: absolute;
    top: 3px; left: 3px;
    width: 22px; height: 22px;
    border-radius: 50%;
    background: #fff;
    transition: transform .2s;
    box-shadow: 0 1px 4px rgba(0,0,0,.2);
}
.theme-switch input:checked + .theme-switch-track { background: var(--moh-green); }
.theme-switch input:checked + .theme-switch-track::after { transform: translateX(24px); }

/* ── Password strength bar ───────────────────────────────── */
.pw-strength-bar {
    height: 4px;
    border-radius: 99px;
    background: var(--border-color);
    margin-top: .4rem;
    overflow: hidden;
}
.pw-strength-fill {
    height: 100%;
    border-radius: 99px;
    transition: width .3s, background .3s;
    width: 0%;
}

@media (max-width: 768px) {
    .profile-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

<div class="profile-grid">

    {{-- ── LEFT: Avatar + identity card ───────────────────────── --}}
    <div class="card avatar-card p-3 animate-fade-up">

        {{-- Avatar display --}}
        <div class="avatar-wrap mx-auto">
            @if($user->avatar_url)
                <img id="avatarPreview" src="{{ $user->avatar_url }}" alt="Profile picture" class="avatar-img">
            @else
                <div class="avatar-initials" id="avatarInitials">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            @endif

            {{-- Hidden file input triggered by the camera button --}}
            <form id="avatarForm" method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
                @csrf
                <input type="file" id="avatarInput" name="avatar" accept="image/jpeg,image/png,image/webp"
                       style="display:none;" onchange="this.form.submit()">
                <label for="avatarInput" class="avatar-upload-btn" title="Change picture">
                    <i class="bi bi-camera-fill"></i>
                </label>
            </form>
        </div>

        <p class="profile-name">{{ $user->name }}</p>
        <p class="profile-role">{{ $user->role_label }}</p>
        <p class="profile-email"><i class="bi bi-envelope me-1"></i>{{ $user->email }}</p>

        @if($user->phone)
        <p class="fs-xs text-muted mb-0"><i class="bi bi-telephone me-1"></i>{{ $user->phone }}</p>
        @endif

        @if($user->avatar)
        <div class="mt-3">
            <form method="POST" action="{{ route('profile.avatar.remove') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger w-100"
                        data-confirm="Remove your profile picture?">
                    <i class="bi bi-trash3 me-1"></i>Remove Picture
                </button>
            </form>
        </div>
        @endif

        <hr class="my-3">

        {{-- Quick info --}}
        <ul class="list-unstyled text-start mb-0" style="font-size:.78rem;color:var(--text-muted);">
            <li class="mb-2">
                <i class="bi bi-person-badge me-2" style="color:var(--moh-green);"></i>
                <strong style="color:var(--text-primary);">{{ $user->role_label }}</strong>
            </li>
            <li class="mb-2">
                <i class="bi bi-calendar3 me-2" style="color:var(--moh-green);"></i>
                Joined {{ $user->created_at->format('M Y') }}
            </li>
            <li>
                <i class="bi bi-circle-fill me-2" style="color:{{ $user->is_active ? '#16a34a' : '#dc2626' }};font-size:.5rem;"></i>
                {{ $user->is_active ? 'Active account' : 'Inactive account' }}
            </li>
        </ul>

    </div>

    {{-- ── RIGHT: Tabbed settings panel ───────────────────────── --}}
    <div class="animate-fade-up stagger-2">

        {{-- Tab navigation --}}
        <div class="profile-tab-nav">
            <button class="profile-tab-btn active" data-tab="tab-name">
                <i class="bi bi-person"></i> Display Name
            </button>
            <button class="profile-tab-btn" data-tab="tab-password">
                <i class="bi bi-key"></i> Password
            </button>
            <button class="profile-tab-btn" data-tab="tab-theme">
                <i class="bi bi-palette"></i> Appearance
            </button>
        </div>

        {{-- ── Tab: Display Name ──────────────────────────────── --}}
        <div class="profile-tab-panel active" id="tab-name">
            <div class="card">
                <div class="card-header">
                    <div class="card-header-icon" style="background:#dcfce7;color:#15803d;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    Update Display Name
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.name') }}" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" id="name" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required maxlength="100">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            <div class="form-text">Email cannot be changed. Contact an administrator if needed.</div>
                        </div>
                        <button type="submit" class="btn btn-kiswa">
                            <i class="bi bi-check-lg me-1"></i>Save Name
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── Tab: Password ──────────────────────────────────── --}}
        <div class="profile-tab-panel" id="tab-password">
            <div class="card">
                <div class="card-header">
                    <div class="card-header-icon" style="background:#dbeafe;color:#1d4ed8;">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    Change Password
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.password') }}" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <div class="input-group">
                                <input type="password" id="current_password" name="current_password"
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       required autocomplete="current-password">
                                <button class="btn btn-outline-secondary toggle-pw" type="button" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('current_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       required autocomplete="new-password" id="newPwInput">
                                <button class="btn btn-outline-secondary toggle-pw" type="button" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="pw-strength-bar mt-1">
                                <div class="pw-strength-fill" id="pwStrengthFill"></div>
                            </div>
                            <div class="form-text" id="pwStrengthLabel">Min 8 characters, upper &amp; lowercase, and a number.</div>
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="form-control" required autocomplete="new-password">
                                <button class="btn btn-outline-secondary toggle-pw" type="button" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-kiswa">
                            <i class="bi bi-lock-fill me-1"></i>Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── Tab: Appearance ────────────────────────────────── --}}
        <div class="profile-tab-panel" id="tab-theme">
            <div class="card">
                <div class="card-header">
                    <div class="card-header-icon" style="background:#ede9fe;color:#7c3aed;">
                        <i class="bi bi-palette-fill"></i>
                    </div>
                    Appearance
                </div>
                <div class="card-body">

                    {{-- Dark / Light toggle --}}
                    <div class="theme-toggle-row">
                        <div class="theme-toggle-info">
                            <strong><i class="bi bi-moon-stars me-1"></i>Dark Mode</strong>
                            <span>Switch between light and dark interface</span>
                        </div>
                        <label class="theme-switch" aria-label="Toggle dark mode">
                            <input type="checkbox" id="darkModeToggle">
                            <span class="theme-switch-track"></span>
                        </label>
                    </div>

                    {{-- Preview swatches --}}
                    <div class="mt-3">
                        <div class="form-label mb-2">Theme Preview</div>
                        <div class="d-flex gap-3 flex-wrap">
                            <div class="theme-preview-card" id="previewLight"
                                 style="flex:1;min-width:140px;border-radius:10px;border:2px solid var(--border-color);overflow:hidden;cursor:pointer;">
                                <div style="background:#E8F1F5;padding:.6rem .8rem;border-bottom:1px solid #C5DCE8;">
                                    <div style="width:60%;height:8px;background:#0B4F6C;border-radius:3px;"></div>
                                </div>
                                <div style="background:#fff;padding:.6rem .8rem;">
                                    <div style="width:80%;height:6px;background:#E8F1F5;border-radius:3px;margin-bottom:.4rem;"></div>
                                    <div style="width:50%;height:6px;background:#E8F1F5;border-radius:3px;"></div>
                                </div>
                                <div style="text-align:center;padding:.4rem;background:#f8fafc;font-size:.7rem;font-weight:700;color:#0B4F6C;">
                                    <i class="bi bi-sun me-1"></i>Light
                                </div>
                            </div>
                            <div class="theme-preview-card" id="previewDark"
                                 style="flex:1;min-width:140px;border-radius:10px;border:2px solid var(--border-color);overflow:hidden;cursor:pointer;">
                                <div style="background:#1e293b;padding:.6rem .8rem;border-bottom:1px solid #334155;">
                                    <div style="width:60%;height:8px;background:#38bdf8;border-radius:3px;"></div>
                                </div>
                                <div style="background:#0f172a;padding:.6rem .8rem;">
                                    <div style="width:80%;height:6px;background:#1e293b;border-radius:3px;margin-bottom:.4rem;"></div>
                                    <div style="width:50%;height:6px;background:#1e293b;border-radius:3px;"></div>
                                </div>
                                <div style="text-align:center;padding:.4rem;background:#1e293b;font-size:.7rem;font-weight:700;color:#94a3b8;">
                                    <i class="bi bi-moon-stars me-1"></i>Dark
                                </div>
                            </div>
                        </div>
                        <div class="form-text mt-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Your preference is saved in your browser and applied on every page.
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>{{-- /.right panel --}}
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Tab switching ────────────────────────────────────────────
    const tabBtns   = document.querySelectorAll('.profile-tab-btn');
    const tabPanels = document.querySelectorAll('.profile-tab-panel');

    tabBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            tabBtns.forEach(b => b.classList.remove('active'));
            tabPanels.forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById(btn.dataset.tab).classList.add('active');
        });
    });

    // ── Open password tab if there's a password error ────────────
    @if($errors->has('current_password') || $errors->has('password'))
    document.querySelector('[data-tab="tab-password"]').click();
    @endif

    // ── Show/hide password toggle ────────────────────────────────
    document.querySelectorAll('.toggle-pw').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const input = btn.previousElementSibling;
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.querySelector('i').className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    });

    // ── Password strength meter ──────────────────────────────────
    const pwInput  = document.getElementById('password');
    const fill     = document.getElementById('pwStrengthFill');
    const label    = document.getElementById('pwStrengthLabel');

    if (pwInput && fill) {
        pwInput.addEventListener('input', function () {
            const v = this.value;
            let score = 0;
            if (v.length >= 8)             score++;
            if (/[A-Z]/.test(v))           score++;
            if (/[a-z]/.test(v))           score++;
            if (/[0-9]/.test(v))           score++;
            if (/[^A-Za-z0-9]/.test(v))   score++;

            const pct  = (score / 5) * 100;
            const info = ['', 'Very weak', 'Weak', 'Fair', 'Strong', 'Very strong'][score];
            const clr  = ['', '#dc2626',   '#ea580c', '#d97706', '#16a34a', '#15803d'][score];

            fill.style.width      = pct + '%';
            fill.style.background = clr;
            if (label) label.textContent = score > 0 ? info : 'Min 8 characters, upper & lowercase, and a number.';
        });
    }

    // ── Dark mode toggle ─────────────────────────────────────────
    const toggle       = document.getElementById('darkModeToggle');
    const previewLight = document.getElementById('previewLight');
    const previewDark  = document.getElementById('previewDark');

    function applyTheme(dark) {
        document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
        localStorage.setItem('kiswa-theme', dark ? 'dark' : 'light');
        if (toggle) toggle.checked = dark;
        highlightPreview(dark);
    }

    function highlightPreview(dark) {
        if (!previewLight || !previewDark) return;
        previewLight.style.borderColor = dark ? 'var(--border-color)' : 'var(--moh-green)';
        previewLight.style.boxShadow   = dark ? 'none' : '0 0 0 3px rgba(26,92,42,.15)';
        previewDark.style.borderColor  = dark ? 'var(--moh-green)' : 'var(--border-color)';
        previewDark.style.boxShadow    = dark ? '0 0 0 3px rgba(26,92,42,.15)' : 'none';
    }

    // Restore saved preference
    const saved = localStorage.getItem('kiswa-theme');
    applyTheme(saved === 'dark');

    if (toggle) {
        toggle.addEventListener('change', function () { applyTheme(this.checked); });
    }

    // Click on preview swatches
    previewLight && previewLight.addEventListener('click', function () { applyTheme(false); });
    previewDark  && previewDark.addEventListener('click',  function () { applyTheme(true);  });

    // ── Avatar preview before upload ─────────────────────────────
    const avatarInput = document.getElementById('avatarInput');
    if (avatarInput) {
        avatarInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                // Swap initials div for a real img if needed
                let img = document.getElementById('avatarPreview');
                const initials = document.getElementById('avatarInitials');
                if (!img) {
                    img = document.createElement('img');
                    img.id        = 'avatarPreview';
                    img.alt       = 'Profile picture';
                    img.className = 'avatar-img';
                    if (initials) initials.replaceWith(img);
                }
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

});
</script>
@endpush
