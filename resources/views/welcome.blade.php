<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f6f7f1">
    <meta name="description" content="A clearer way to manage medicines, stock and dispensing at Kiswa Health Centre III.">
    <title>Kiswa Health Centre III | Care, in good order</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --paper: #f6f7f1;
            --ink: #172a22;
            --muted: #64746b;
            --green: #176b4a;
            --green-dark: #104c36;
            --lime: #d7ed78;
            --line: #dce3d8;
            --coral: #e78369;
            --display: 'Playfair Display', Georgia, serif;
            --body: 'Plus Jakarta Sans', system-ui, sans-serif;
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { margin: 0; background: var(--paper); color: var(--ink); font-family: var(--body); }
        a { color: inherit; }
        button { font: inherit; }
        .site-shell { width: min(1240px, calc(100% - 64px)); margin: 0 auto; }
        .topbar { height: 88px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); }
        .brand { display: inline-flex; align-items: center; gap: 12px; text-decoration: none; }
        .brand-mark { width: 42px; height: 42px; display: grid; place-items: center; border-radius: 13px; background: var(--green); color: white; font-size: 21px; }
        .brand-copy { display: grid; gap: 2px; }
        .brand-copy strong { font-family: var(--display); font-size: 14px; line-height: 1.15; }
        .brand-copy span { color: var(--muted); font-size: 11px; }
        .nav-links { display: flex; align-items: center; gap: 35px; }
        .nav-links a { color: #43564c; text-decoration: none; font-size: 13px; font-weight: 600; }
        .nav-links a:hover { color: var(--green); }
        .nav-action, .primary-action { display: inline-flex; align-items: center; justify-content: center; gap: 10px; min-height: 46px; padding: 0 19px; border: 0; border-radius: 5px; background: var(--green); color: #fff; text-decoration: none; font-size: 13px; font-weight: 700; transition: background .2s, transform .2s; }
        .nav-action:hover, .primary-action:hover { background: var(--green-dark); color: white; transform: translateY(-2px); }
        .menu-toggle { display: none; width: 42px; height: 42px; border: 1px solid var(--line); border-radius: 5px; background: transparent; color: var(--ink); font-size: 19px; }

        .hero { display: grid; grid-template-columns: 1.02fr .98fr; align-items: center; gap: clamp(40px, 7vw, 100px); padding: 78px 0 82px; }
        .eyebrow { display: inline-flex; align-items: center; gap: 9px; color: var(--green); font-size: 11px; font-weight: 700; letter-spacing: 1.3px; text-transform: uppercase; }
        .eyebrow::before { width: 24px; height: 2px; content: ''; background: var(--coral); }
        h1, h2, h3, p { margin-top: 0; }
        .hero h1 { max-width: 610px; margin: 22px 0 20px; font: 800 clamp(44px, 5.7vw, 76px)/1.04 var(--display); letter-spacing: 0; }
        .hero h1 em { color: var(--green); font-style: normal; }
        .hero-copy { max-width: 470px; margin-bottom: 29px; color: var(--muted); font-size: 16px; line-height: 1.75; }
        .hero-actions { display: flex; align-items: center; flex-wrap: wrap; gap: 14px; }
        .text-action { display: inline-flex; align-items: center; gap: 9px; color: var(--ink); text-decoration: none; font-size: 13px; font-weight: 700; }
        .text-action i { color: var(--green); transition: transform .2s; }
        .text-action:hover i { transform: translateX(4px); }
        .hero-note { display: flex; align-items: center; gap: 10px; margin-top: 42px; color: var(--muted); font-size: 12px; }
        .hero-note i { color: var(--green); font-size: 16px; }
        .hero-art { position: relative; min-height: 480px; animation-delay: .12s; }
        .photo-frame { position: absolute; inset: 0 18px 22px 35px; overflow: hidden; border-radius: 48% 48% 7px 7px; background: #dce6d4; }
        .photo-frame img { width: 100%; height: 100%; object-fit: cover; object-position: center; }
        .photo-frame::after { position: absolute; inset: 0; content: ''; background: linear-gradient(180deg, transparent 53%, rgba(12, 41, 28, .5)); }
        .image-caption { position: absolute; z-index: 1; bottom: 43px; left: 67px; color: white; }
        .image-caption span { display: block; margin-bottom: 6px; font-size: 11px; opacity: .8; }
        .image-caption strong { font: 700 18px var(--display); }
        .roundel { position: absolute; top: 60px; left: 0; width: 92px; height: 92px; display: grid; place-items: center; border: 1px solid rgba(23, 42, 34, .18); border-radius: 50%; background: var(--lime); color: var(--green-dark); text-align: center; transform: rotate(-10deg); }
        .roundel span { max-width: 58px; font-size: 10px; font-weight: 700; line-height: 1.3; text-transform: uppercase; }
        .art-index { position: absolute; right: 0; top: 40%; color: var(--green); font: 700 11px var(--display); writing-mode: vertical-rl; }

        .principles { display: grid; grid-template-columns: repeat(3, 1fr); border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); }
        .principle { display: flex; align-items: flex-start; gap: 15px; padding: 25px 26px; border-right: 1px solid var(--line); }
        .principle:first-child { padding-left: 0; }
        .principle:last-child { border-right: 0; }
        .principle-icon { width: 35px; height: 35px; flex: 0 0 35px; display: grid; place-items: center; border-radius: 50%; background: #e5eee2; color: var(--green); }
        .principle strong { display: block; margin: 1px 0 4px; font: 700 13px var(--display); }
        .principle p { margin: 0; color: var(--muted); font-size: 12px; line-height: 1.55; }

        .platform { display: grid; grid-template-columns: .82fr 1.18fr; gap: 90px; padding: 105px 0 112px; }
        .section-heading h2 { margin: 17px 0 15px; font: 800 clamp(34px, 4vw, 49px)/1.12 var(--display); letter-spacing: 0; }
        .section-heading p { max-width: 390px; color: var(--muted); font-size: 14px; line-height: 1.75; }
        .feature-panel { border-top: 1px solid var(--line); }
        .feature-tab { width: 100%; display: grid; grid-template-columns: 46px 1fr 24px; align-items: center; gap: 15px; padding: 19px 0; border: 0; border-bottom: 1px solid var(--line); background: transparent; color: var(--ink); text-align: left; cursor: pointer; }
        .feature-tab .number { color: #8b9b8e; font: 600 12px var(--display); }
        .feature-tab strong { font: 700 15px var(--display); }
        .feature-tab i { color: var(--green); transition: transform .25s; }
        .feature-tab[aria-selected="true"] i { transform: rotate(45deg); }
        .feature-detail { padding: 0 36px 22px 61px; border-bottom: 1px solid var(--line); color: var(--muted); font-size: 13px; line-height: 1.7; animation: appear .25s ease both; }
        .feature-detail[hidden] { display: none; }
        .feature-detail p { margin: 0; }
        .feature-detail a { display: inline-flex; align-items: center; gap: 8px; margin-top: 14px; color: var(--green); font-weight: 700; text-decoration: none; }

        .closing-band { position: relative; overflow: hidden; padding: 57px 0; background: var(--green-dark); color: white; }
        .closing-band::after { position: absolute; top: -130px; right: -42px; width: 440px; height: 440px; border: 1px solid rgba(215,237,120,.2); border-radius: 50%; box-shadow: 0 0 0 48px rgba(215,237,120,.035), 0 0 0 96px rgba(215,237,120,.025); content: ''; pointer-events: none; }
        .closing-content { position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between; gap: 30px; }
        .closing-content h2 { max-width: 610px; margin: 0; font: 700 clamp(26px, 3.2vw, 40px)/1.2 var(--display); }
        .closing-content .primary-action { flex: 0 0 auto; background: var(--lime); color: var(--green-dark); }
        .closing-content .primary-action:hover { background: #e4f49e; }
        footer { display: flex; justify-content: space-between; gap: 20px; padding: 25px 0; color: var(--muted); font-size: 11px; }
        footer a { color: var(--green); font-weight: 700; text-decoration: none; }
        .reveal { animation: rise .65s cubic-bezier(.2,.7,.2,1) both; }
        @keyframes rise { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes appear { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: translateY(0); } }

        @media (max-width: 800px) {
            .site-shell { width: min(100% - 40px, 620px); }
            .topbar { height: 74px; }
            .menu-toggle { display: grid; place-items: center; }
            .nav-links { position: absolute; z-index: 5; top: 66px; left: 20px; right: 20px; display: none; align-items: stretch; gap: 0; padding: 10px 18px; border: 1px solid var(--line); background: var(--paper); box-shadow: 0 12px 30px rgba(23,42,34,.1); }
            .nav-links.is-open { display: grid; }
            .nav-links a { padding: 13px 0; border-bottom: 1px solid var(--line); }
            .nav-links .nav-action { justify-content: center; margin: 10px 0 2px; padding: 0 16px; border: 0; }
            .hero { grid-template-columns: 1fr; gap: 38px; padding: 55px 0 58px; }
            .hero h1 { max-width: 550px; font-size: clamp(45px, 10vw, 68px); }
            .hero-art { min-height: min(112vw, 510px); }
            .photo-frame { inset: 0 12px 20px 34px; }
            .roundel { top: 38px; }
            .image-caption { bottom: 40px; left: 58px; }
            .principles { grid-template-columns: 1fr; }
            .principle, .principle:first-child { padding: 18px 0; border-right: 0; border-bottom: 1px solid var(--line); }
            .principle:last-child { border-bottom: 0; }
            .platform { grid-template-columns: 1fr; gap: 35px; padding: 72px 0 80px; }
            .closing-content { align-items: flex-start; flex-direction: column; }
        }
        @media (max-width: 420px) {
            .site-shell { width: calc(100% - 32px); }
            .brand-copy strong { font-size: 12px; }
            .brand-copy span { font-size: 10px; }
            .brand-mark { width: 38px; height: 38px; }
            .hero h1 { font-size: 43px; }
            .hero-copy { font-size: 14px; }
            .hero-art { min-height: 390px; }
            .photo-frame { inset: 0 7px 18px 24px; }
            .roundel { width: 76px; height: 76px; }
            .image-caption { left: 44px; bottom: 34px; }
            .feature-detail { padding-left: 45px; }
            footer { flex-direction: column; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <!-- ═══════════════════════════════ NAVBAR ═══════════════════════════════ -->
    <style>
        /* ── Navbar ────────────────────────────────────────────── */
        .topbar {
            position: sticky; top: 0; z-index: 100;
            width: 100%; max-width: none;
            height: 72px;
            padding: 0 max(calc((100% - 1240px) / 2), 28px);
            display: flex; align-items: center; justify-content: space-between; gap: 24px;
            background: rgba(246,247,241,.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(23,107,74,.13);
            box-shadow: 0 2px 18px rgba(23,107,74,.06);
            transition: background .3s, box-shadow .3s;
        }
        .topbar.scrolled {
            background: rgba(246,247,241,.98);
            box-shadow: 0 4px 24px rgba(23,107,74,.10);
        }

        /* Brand */
        .nb-brand {
            display: flex; align-items: center; gap: 12px;
            text-decoration: none; flex-shrink: 0;
        }
        .nb-logo {
            width: 48px; height: 48px; border-radius: 10px;
            background: var(--green-dark);
            display: grid; place-items: center;
            box-shadow: 0 2px 10px rgba(23,107,74,.30);
            overflow: hidden; flex-shrink: 0;
            border: 2px solid rgba(255,255,255,.18);
        }
        .nb-logo img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .nb-title { font-family: var(--display); font-size: 15px; font-weight: 700; color: var(--ink); line-height: 1.25; }
        .nb-sub   { font-size: 11px; font-weight: 500; color: #78867d; margin-top: 2px; }

        /* Links */
        .nb-links {
            display: flex; align-items: center; gap: 6px;
            list-style: none; margin: 0; padding: 0;
        }
        .nb-links a {
            position: relative;
            display: flex; align-items: center; gap: 6px;
            padding: 7px 13px; border-radius: 7px;
            color: #3a5245; text-decoration: none;
            font-size: 13px; font-weight: 600;
            transition: color .18s, background .18s;
        }
        .nb-links a:hover {
            color: var(--green);
            background: rgba(23,107,74,.07);
        }
        .nb-links a i { font-size: 14px; opacity: .75; }

        /* Pill CTA */
        .nb-cta {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 0 18px; height: 40px; border-radius: 99px;
            background: var(--green); color: #fff;
            font-size: 13px; font-weight: 700; text-decoration: none;
            border: none; cursor: pointer;
            box-shadow: 0 3px 12px rgba(23,107,74,.28);
            transition: background .18s, box-shadow .18s, transform .15s;
            white-space: nowrap; flex-shrink: 0;
        }
        .nb-cta:hover {
            background: var(--green-dark); color: #fff;
            box-shadow: 0 5px 18px rgba(23,107,74,.38);
            transform: translateY(-1px);
        }
        .nb-cta i { font-size: 13px; }

        /* Hamburger */
        .nb-toggle {
            display: none; width: 40px; height: 40px;
            border: 1.5px solid rgba(23,107,74,.2); border-radius: 8px;
            background: transparent; color: var(--ink);
            font-size: 20px; cursor: pointer; flex-shrink: 0;
            align-items: center; justify-content: center;
            transition: background .15s;
        }
        .nb-toggle:hover { background: rgba(23,107,74,.07); }

        /* Mobile drawer */
        @media (max-width: 768px) {
            .topbar { height: 64px; }
            .nb-toggle { display: flex; }
            .nb-links {
                position: fixed; inset: 64px 0 0 0; z-index: 99;
                flex-direction: column; align-items: stretch; gap: 0;
                padding: 16px 20px 24px; background: var(--paper);
                border-top: 1px solid var(--line);
                box-shadow: 0 12px 32px rgba(0,0,0,.12);
                transform: translateY(-12px); opacity: 0;
                pointer-events: none;
                transition: opacity .22s, transform .22s;
            }
            .nb-links.open {
                transform: translateY(0); opacity: 1;
                pointer-events: auto;
            }
            .nb-links a {
                padding: 14px 10px; border-radius: 0;
                border-bottom: 1px solid var(--line); font-size: 15px;
            }
            .nb-links a:last-of-type { border-bottom: none; }
            .nb-cta { display: none; }
            .nb-links .nb-mobile-cta {
                display: flex; margin-top: 14px;
                padding: 14px 18px; border-radius: 10px;
                background: var(--green); color: #fff;
                font-size: 14px; font-weight: 700; text-decoration: none;
                align-items: center; justify-content: center; gap: 8px;
            }
        }
    </style>

    <header class="topbar" id="site-topbar">
        <!-- Brand -->
        <a class="nb-brand" href="{{ url('/') }}" aria-label="Kiswa Health Centre III home">
            <div class="nb-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Kiswa Health Centre III Logo">
            </div>
            <div>
                <div class="nb-title">Kiswa Health Centre III</div>
                <div class="nb-sub">Kampala, Uganda</div>
            </div>
        </a>

        <!-- Hamburger (mobile) -->
        <button class="nb-toggle" id="nb-toggle" aria-label="Toggle navigation" aria-expanded="false">
            <i class="bi bi-list" id="nb-toggle-icon"></i>
        </button>

        <!-- Links -->
        <ul class="nb-links" id="nb-links" role="list">
            <li><a href="#approach"><i class="bi bi-box-seam"></i> What we manage</a></li>
            <li><a href="#system"><i class="bi bi-diagram-3"></i> How it works</a></li>
            <!-- Mobile CTA inside drawer -->
            <li><a href="{{ route('login') }}" class="nb-mobile-cta" aria-label="Staff sign in">
                <i class="bi bi-lock-fill"></i> Staff sign in
            </a></li>
        </ul>

        <!-- Desktop CTA -->
        <a class="nb-cta" href="{{ route('login') }}">
            <i class="bi bi-lock-fill"></i> Staff sign in
        </a>
    </header>

    <script>
        (function(){
            var btn  = document.getElementById('nb-toggle');
            var menu = document.getElementById('nb-links');
            var icon = document.getElementById('nb-toggle-icon');
            var bar  = document.getElementById('site-topbar');
            btn.addEventListener('click', function(){
                var open = menu.classList.toggle('open');
                btn.setAttribute('aria-expanded', open);
                icon.className = open ? 'bi bi-x-lg' : 'bi bi-list';
            });
            // Close on link click
            menu.querySelectorAll('a').forEach(function(a){
                a.addEventListener('click', function(){
                    menu.classList.remove('open');
                    btn.setAttribute('aria-expanded', false);
                    icon.className = 'bi bi-list';
                });
            });
            // Scrolled shadow
            window.addEventListener('scroll', function(){
                bar.classList.toggle('scrolled', window.scrollY > 10);
            }, {passive:true});
        })();
    </script>

    <main>
        <section class="hero-band">
        <div class="hero-visual" aria-hidden="true">
            <img src="{{ asset('images/banner.png') }}" alt="">
        </div>
        <div class="site-shell hero" aria-labelledby="hero-title">
            <div class="hero-copy-block reveal">
                <span class="eyebrow">A better rhythm for everyday care</span>
                <h1 id="hero-title">Good care starts <em>behind the scenes.</em></h1>
                <p class="hero-copy">A thoughtful way for our team to keep medicines moving, stock visible and every dispense accounted for.</p>
                <div class="hero-actions">
                    <a class="primary-action" href="{{ route('login') }}">Open staff workspace <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
                    <a class="text-action" href="#system">See how it works <i class="bi bi-arrow-down-right" aria-hidden="true"></i></a>
                </div>
                <div class="hero-note"><i class="bi bi-shield-check" aria-hidden="true"></i><span>For authorised Kiswa Health Centre staff</span></div>
            </div>
        </section>
            <style>
            /* Homepage refresh */
            #approach, #system, #contact { scroll-margin-top: 100px; }
            .topbar {
                position: sticky;
                top: 0;
                z-index: 20;
                width: 100%;
                height: 88px;
                max-width: none;
                padding: 0 max(calc((100% - 1240px) / 2), 32px);
                border-bottom: 1px solid rgba(23, 42, 34, .1);
                background: rgba(246, 247, 241, .96);
                backdrop-filter: blur(14px);
            }
            .brand-mark { position: relative; border-radius: 4px; background: var(--lime); color: var(--green-dark); }
            .brand-mark > span { position: absolute; right: 7px; bottom: 7px; width: 7px; height: 7px; border: 2px solid var(--lime); border-radius: 50%; background: var(--coral); }
            .brand-copy strong { font-size: 14px; }
            .brand-copy span { color: #78867d; font-size: 9px; font-weight: 700; letter-spacing: 1px; }
            .nav-links { gap: 30px; }
            .nav-links a:not(.nav-action) { position: relative; padding: 10px 0; }
            .nav-links a:not(.nav-action)::after { position: absolute; right: 0; bottom: 2px; left: 0; height: 1px; content: ''; background: var(--green); transform: scaleX(0); transform-origin: left; transition: transform .2s ease; }
            .nav-links a:not(.nav-action):hover::after { transform: scaleX(1); }
            .nav-action, .primary-action { min-height: 48px; border-radius: 3px; }
            .nav-action { gap: 9px; padding: 0 16px; }
            .nav-action:hover { background: var(--green-dark); }

            .hero-band { position: relative; min-height: 610px; overflow: hidden; background: #123e2e; color: #fff; }
            .hero-band::after { position: absolute; top: 0; right: 0; bottom: 0; width: 5px; content: ''; background: var(--lime); }
            .hero-visual { position: absolute; inset: 0; z-index: 0; }
            .hero-visual img { width: 100%; height: 100%; object-fit: cover; object-position: center 42%; }
            .hero-visual::after { position: absolute; inset: 0; content: ''; background: linear-gradient(90deg, rgba(10, 39, 28, .96) 0%, rgba(12, 45, 32, .88) 39%, rgba(12, 45, 32, .5) 69%, rgba(12, 45, 32, .2) 100%), linear-gradient(0deg, rgba(8, 29, 21, .46), transparent 48%); }
            .hero { position: relative; z-index: 1; display: flex; align-items: center; min-height: 610px; padding: 82px 0 90px; }
            .hero-copy-block { max-width: 650px; }
            .hero .eyebrow { color: var(--lime); }
            .hero .eyebrow::before { background: var(--coral); }
            .hero h1 { max-width: 660px; margin: 24px 0 22px; font-size: 64px; line-height: 1.06; }
            .hero h1 em { color: var(--lime); }
            .hero-copy { max-width: 430px; margin-bottom: 31px; color: rgba(255,255,255,.73); font-size: 15px; line-height: 1.8; }
            .hero .primary-action { min-height: 52px; padding: 0 21px; background: var(--lime); color: var(--green-dark); }
            .hero .primary-action:hover { background: #e6f5a4; color: var(--green-dark); }
            .hero .text-action { color: #fff; }
            .hero .text-action i, .hero-note i { color: var(--lime); }
            .hero-note { margin-top: 38px; color: rgba(255,255,255,.68); }

            .principles { position: relative; z-index: 2; grid-template-columns: repeat(3, 1fr); margin-top: 0; padding: 14px 0; border-top: 0; border-bottom: 1px solid var(--line); }
            .principle { padding: 24px 26px; }
            .principle:first-child { padding-left: 0; }
            .principle-icon { width: 39px; height: 39px; flex-basis: 39px; border-radius: 3px; background: #e5eee2; font-size: 16px; }
            .principle strong { font-size: 14px; }
            .principle p { font-size: 12px; }
            .platform { gap: 84px; padding-top: 108px; padding-bottom: 120px; }
            .section-heading h2 { font-size: 43px; }
            .feature-tab { padding: 21px 0; }
            .feature-tab strong { font-size: 16px; }
            .closing-band { padding: 66px 0; }
            .closing-band::after { top: -90px; right: 6%; width: 360px; height: 360px; border-radius: 4px; transform: rotate(45deg); }
            .closing-content h2 { font-size: 38px; }

            .site-footer { margin-top: 0; padding: 52px 0 0; background: #0c2d22; color: rgba(255,255,255,.7); }
            .footer-main { display: grid; grid-template-columns: 1.4fr .7fr 1fr; gap: 60px; padding-bottom: 43px; }
            .site-footer .brand-copy strong { color: #fff; }
            .site-footer .brand-copy span { color: rgba(255,255,255,.52); }
            .site-footer .brand-mark { background: var(--lime); color: var(--green-dark); }
            .footer-brand p, .footer-access p { max-width: 290px; margin: 20px 0 0; color: rgba(255,255,255,.58); font-size: 12px; line-height: 1.7; }
            .footer-label { display: block; margin: 3px 0 17px; color: var(--lime); font-size: 9px; font-weight: 700; letter-spacing: 1.5px; }
            .footer-navigation { display: flex; flex-direction: column; align-items: flex-start; gap: 12px; }
            .footer-navigation a, .footer-login { color: rgba(255,255,255,.86); font-size: 12px; text-decoration: none; }
            .footer-navigation a:hover, .footer-login:hover { color: var(--lime); }
            .footer-access p { margin: 0 0 14px; }
            .footer-login { display: inline-flex; align-items: center; gap: 9px; color: var(--lime); font-weight: 700; }
            .footer-bottom { display: flex; justify-content: space-between; gap: 20px; padding: 17px 0 20px; border-top: 1px solid rgba(255,255,255,.13); color: rgba(255,255,255,.48); font-size: 10px; }

            @media (max-width: 800px) {
                .topbar { height: 74px; padding: 0 20px; }
                .topbar .brand { position: absolute; left: 50%; transform: translateX(-50%); }
                .topbar .menu-toggle { position: relative; z-index: 1; margin-left: auto; }
                .menu-toggle { border-color: rgba(23,42,34,.18); border-radius: 3px; }
                .nav-links { top: 67px; left: 20px; right: 20px; gap: 0; padding: 10px 18px; border-color: var(--line); background: var(--paper); }
                .nav-links a:not(.nav-action) { padding: 13px 0; }
                .nav-links a:not(.nav-action)::after { display: none; }
                .nav-links .nav-action { min-height: 45px; }
                .hero-band, .hero { min-height: 600px; }
                .hero { display: flex; padding: 72px 0 76px; }
                .hero-visual img { object-position: 58% center; }
                .hero-visual::after { background: linear-gradient(90deg, rgba(10, 39, 28, .94), rgba(12, 45, 32, .64)), linear-gradient(0deg, rgba(8, 29, 21, .4), transparent 55%); }
                .hero h1 { max-width: 600px; font-size: 58px; }
                .principles { grid-template-columns: 1fr; padding: 7px 0; }
                .principle, .principle:first-child { padding: 18px 0; border-right: 0; border-bottom: 1px solid var(--line); }
                .principle:last-child { border-bottom: 0; }
                .platform { grid-template-columns: 1fr; gap: 36px; padding: 76px 0 84px; }
                .section-heading h2 { max-width: 560px; font-size: 40px; }
                .footer-main { grid-template-columns: 1.2fr 1fr; gap: 35px; }
                .footer-brand { grid-column: 1 / -1; }
            }
            @media (max-width: 420px) {
                .topbar { padding: 0 16px; }
                .brand { gap: 9px; }
                .brand-copy strong { font-size: 12px; }
                .brand-copy span { font-size: 8px; }
                .hero { padding-top: 48px; }
                .hero h1 { font-size: 45px; }
                .hero-copy { font-size: 14px; }
                .hero-band, .hero { min-height: 560px; }
                .hero { padding: 64px 0 70px; }
                .hero-visual img { object-position: 60% center; }
                .hero h1 { font-size: 45px; }
                .section-heading h2 { font-size: 34px; }
                .feature-tab { grid-template-columns: 32px 1fr 22px; gap: 10px; }
                .feature-tab strong { font-size: 14px; }
                .feature-detail { padding-left: 42px; }
                .closing-band { padding: 48px 0; }
                .closing-content h2 { font-size: 30px; }
                .footer-main { grid-template-columns: 1fr 1fr; gap: 30px 18px; }
                .footer-bottom { flex-direction: column; gap: 8px; }
            }
            </style>
        </div>

        <section class="site-shell principles" id="approach" aria-label="Our approach">
            <div class="principle"><span class="principle-icon"><i class="bi bi-box-seam" aria-hidden="true"></i></span><div><strong>Know what is on hand</strong><p>Keep medicine quantities and batches in view.</p></div></div>
            <div class="principle"><span class="principle-icon"><i class="bi bi-calendar2-check" aria-hidden="true"></i></span><div><strong>Stay ahead of expiry</strong><p>Make approaching dates easier to spot and act on.</p></div></div>
            <div class="principle"><span class="principle-icon"><i class="bi bi-journal-check" aria-hidden="true"></i></span><div><strong>Keep a clear record</strong><p>Connect dispensing activity with the stock it uses.</p></div></div>
        </section>

        <section class="site-shell platform" id="system" aria-labelledby="system-title">
            <div class="section-heading">
                <span class="eyebrow">One steady workflow</span>
                <h2 id="system-title">The right information, right where it is needed.</h2>
                <p>Simple, connected tools help the pharmacy team spend less time searching and more time supporting care.</p>
            </div>
            <div class="feature-panel" role="tablist" aria-label="System capabilities">
                <button class="feature-tab" id="tab-inventory" type="button" role="tab" aria-selected="true" aria-controls="detail-inventory">
                    <span class="number">01</span><strong>Inventory &amp; batches</strong><i class="bi bi-plus-lg" aria-hidden="true"></i>
                </button>
                <div class="feature-detail is-active" id="detail-inventory" role="tabpanel" aria-labelledby="tab-inventory"><p>See registered medicines, quantities on hand, batch details and reorder needs together.</p><a href="{{ route('login') }}">Go to staff sign in <i class="bi bi-arrow-right" aria-hidden="true"></i></a></div>

                <button class="feature-tab" id="tab-dispensing" type="button" role="tab" aria-selected="false" aria-controls="detail-dispensing" tabindex="-1">
                    <span class="number">02</span><strong>Dispensing records</strong><i class="bi bi-plus-lg" aria-hidden="true"></i>
                </button>
                <div class="feature-detail" id="detail-dispensing" role="tabpanel" aria-labelledby="tab-dispensing" hidden><p>Record patient references, quantities and dispensing dates, with a searchable activity history for the team.</p><a href="{{ route('login') }}">Go to staff sign in <i class="bi bi-arrow-right" aria-hidden="true"></i></a></div>

                <button class="feature-tab" id="tab-procurement" type="button" role="tab" aria-selected="false" aria-controls="detail-procurement" tabindex="-1">
                    <span class="number">03</span><strong>Suppliers &amp; procurement</strong><i class="bi bi-plus-lg" aria-hidden="true"></i>
                </button>
                <div class="feature-detail" id="detail-procurement" role="tabpanel" aria-labelledby="tab-procurement" hidden><p>Bring supplier details and stock requests into the same workflow, from low-stock review through requisition.</p><a href="{{ route('login') }}">Go to staff sign in <i class="bi bi-arrow-right" aria-hidden="true"></i></a></div>

                <button class="feature-tab" id="tab-reports" type="button" role="tab" aria-selected="false" aria-controls="detail-reports" tabindex="-1">
                    <span class="number">04</span><strong>Reports &amp; visibility</strong><i class="bi bi-plus-lg" aria-hidden="true"></i>
                </button>
                <div class="feature-detail" id="detail-reports" role="tabpanel" aria-labelledby="tab-reports" hidden><p>Review stock balance, expiry dates and usage patterns to support everyday planning.</p><a href="{{ route('login') }}">Go to staff sign in <i class="bi bi-arrow-right" aria-hidden="true"></i></a></div>
            </div>
        </section>

        <section class="closing-band" id="contact">
            <div class="site-shell closing-content">
                <h2>Made for the people who keep care moving.</h2>
                <a class="primary-action" href="{{ route('login') }}">Staff sign in <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
            </div>
        </section>
    </main>

    <footer id="site-footer" aria-label="Site footer" style="width:100%;display:block;">

        {{-- Uganda flag strip --}}
        <div style="display:flex;height:5px;width:100%;">
            <div style="flex:1;background:#111;"></div>
            <div style="flex:1;background:#fcd205;"></div>
            <div style="flex:1;background:#d90000;"></div>
            <div style="flex:1;background:#111;"></div>
            <div style="flex:1;background:#fcd205;"></div>
            <div style="flex:1;background:#d90000;"></div>
        </div>

        <div class="sf-wrap">

            {{-- Main bar --}}
            <div class="sf-main-bar">
                <div class="sf-inner">

                    {{-- Left: brand --}}
                    <div class="sf-brand">
                        <div class="sf-logo">
                            <img src="{{ asset('images/logo.png') }}" alt="Kiswa Health Centre III Logo">
                        </div>
                        <div>
                            <div class="sf-name">Kiswa Health Centre III</div>
                            <div class="sf-sub">Kampala, Uganda &mdash; Medicines Management</div>
                        </div>
                    </div>

                    {{-- Centre: quick nav --}}
                    <nav class="sf-nav" aria-label="Footer navigation">
                        <a href="#approach">Our Approach</a>
                        <span class="sf-sep" aria-hidden="true">/</span>
                        <a href="#system">How It Works</a>
                        <span class="sf-sep" aria-hidden="true">/</span>
                        <a href="{{ route('login') }}">Staff Sign In</a>
                    </nav>

                    {{-- Right: CTA --}}
                    <a href="{{ route('login') }}" class="sf-cta">
                        Sign in <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                    </a>

                </div>
            </div>

            {{-- Bottom strip --}}
            <div class="sf-bottom-bar">
                <div class="sf-inner sf-bottom">
                    <span>&copy; {{ now()->year }} Kiswa Health Centre III. Republic of Uganda.</span>
                    <span>Ministry of Health Uganda</span>
                </div>
            </div>

        </div>

    </footer>

    <style>
        /* Footer resets — break out of any ancestor width constraint */
        #site-footer {
            width: 100%;
            display: block;
            margin: 0;
            padding: 0;
        }
        .sf-wrap {
            width: 100%;
            background: #0c2d22;
            color: rgba(255,255,255,.65);
            font-family: var(--body);
        }
        .sf-main-bar {
            width: 100%;
            padding: 0 max(calc((100% - 1240px) / 2), 32px);
            box-sizing: border-box;
        }
        .sf-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            padding: 28px 0;
            flex-wrap: wrap;
            width: 100%;
        }
        .sf-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }
        .sf-logo {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: var(--green-dark);
            overflow: hidden;
            flex-shrink: 0;
            border: 1px solid rgba(255,255,255,.15);
        }
        .sf-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .sf-name {
            font-family: var(--display);
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            line-height: 1.25;
        }
        .sf-sub {
            font-size: 10px;
            color: rgba(255,255,255,.45);
            margin-top: 2px;
            letter-spacing: .02em;
        }
        .sf-nav {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .sf-nav a {
            color: rgba(255,255,255,.7);
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: color .15s;
        }
        .sf-nav a:hover { color: var(--lime); }
        .sf-sep { color: rgba(255,255,255,.2); font-size: 11px; }
        .sf-cta {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            background: var(--lime);
            color: var(--green-dark);
            border-radius: 3px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: background .15s;
            flex-shrink: 0;
        }
        .sf-cta:hover { background: #c8e060; color: var(--green-dark); }
        .sf-bottom-bar {
            width: 100%;
            padding: 0 max(calc((100% - 1240px) / 2), 32px);
            border-top: 1px solid rgba(255,255,255,.1);
            box-sizing: border-box;
        }
        .sf-bottom {
            padding: 12px 0 15px;
            font-size: 10px;
            color: rgba(255,255,255,.35);
        }
        @media (max-width: 700px) {
            .sf-main-bar,
            .sf-bottom-bar { padding: 0 20px; }
            .sf-inner       { flex-direction: column; align-items: flex-start; gap: 16px; }
            .sf-nav         { gap: 8px; }
            .sf-bottom      { flex-direction: column; gap: 4px; }
        }
    </style>

    <script>
        const menuButton = document.querySelector('.menu-toggle');
        const navigation = document.querySelector('#main-navigation');

        menuButton.addEventListener('click', () => {
            const isOpen = menuButton.getAttribute('aria-expanded') === 'true';
            menuButton.setAttribute('aria-expanded', String(!isOpen));
            menuButton.setAttribute('aria-label', isOpen ? 'Open navigation' : 'Close navigation');
            navigation.classList.toggle('is-open', !isOpen);
        });

        navigation.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                menuButton.setAttribute('aria-expanded', 'false');
                menuButton.setAttribute('aria-label', 'Open navigation');
                navigation.classList.remove('is-open');
            });
        });

        const featureTabs = [...document.querySelectorAll('.feature-tab')];

        featureTabs.forEach((tab, index) => {
            tab.addEventListener('click', () => {
                featureTabs.forEach((item) => {
                    const selected = item === tab;
                    const panel = document.getElementById(item.getAttribute('aria-controls'));
                    item.setAttribute('aria-selected', String(selected));
                    item.tabIndex = selected ? 0 : -1;
                    panel.hidden = !selected;
                    panel.classList.toggle('is-active', selected);
                });
            });

            tab.addEventListener('keydown', (event) => {
                if (!['ArrowDown', 'ArrowUp', 'Home', 'End'].includes(event.key)) return;
                event.preventDefault();
                const nextIndex = event.key === 'Home' ? 0 : event.key === 'End' ? featureTabs.length - 1 : (index + (event.key === 'ArrowDown' ? 1 : -1) + featureTabs.length) % featureTabs.length;
                featureTabs[nextIndex].focus();
                featureTabs[nextIndex].click();
            });
        });
    </script>
</body>
</html>