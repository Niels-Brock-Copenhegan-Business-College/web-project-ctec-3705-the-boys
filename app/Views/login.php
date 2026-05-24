<?php
$error   = $error   ?? null;
$flash   = $flash   ?? [];
$oldUser = $oldUser ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in | UniHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { height: 100%; margin: 0;
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            -webkit-font-smoothing: antialiased; }

        /* ── Split layout ───────────────────────────────────── */
        .sl-page { min-height: 100vh; display: grid; grid-template-columns: 1fr 1fr; }

        /* ── Left brand panel ───────────────────────────────── */
        .sl-brand {
            background: linear-gradient(145deg, #001a3d 0%, #003380 50%, #005ce6 100%);
            display: flex; flex-direction: column;
            justify-content: space-between;
            padding: 3rem 3.5rem;
            position: relative; overflow: hidden;
        }
        .sl-brand__bubble {
            position: absolute; border-radius: 50%;
            background: rgba(255,255,255,.05);
        }
        .sl-brand__bubble--1 { width:400px;height:400px; top:-130px; right:-110px; }
        .sl-brand__bubble--2 { width:260px;height:260px; bottom:-70px; left:-70px; }
        .sl-brand__bubble--3 { width:160px;height:160px; bottom:140px; right:40px; background:rgba(255,255,255,.03); }

        .sl-brand__top { position:relative; z-index:1; }
        .sl-brand__mark {
            width: 3.2rem; height: 3.2rem; border-radius: 12px;
            background: linear-gradient(135deg, #e8a020, #ffd37a);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; font-weight: 800; color: #11213a;
            box-shadow: 0 6px 20px rgba(232,160,32,.3);
            margin-bottom: 1.5rem;
        }
        .sl-brand__name { font-size:.75rem; font-weight:700; letter-spacing:.12em;
            text-transform:uppercase; color:rgba(255,255,255,.5); margin-bottom:.4rem; }
        .sl-brand__title { font-size:2rem; font-weight:700; color:#fff; line-height:1.2; margin-bottom:.75rem; }
        .sl-brand__sub { font-size:.92rem; color:rgba(255,255,255,.6); line-height:1.65; max-width:300px; }

        .sl-brand__mid { position:relative; z-index:1; }
        .sl-feature {
            display:flex; align-items:flex-start; gap:.85rem;
            margin-bottom:1.2rem;
        }
        .sl-feature__icon {
            width:2.2rem; height:2.2rem; border-radius:9px; flex-shrink:0;
            background:rgba(255,255,255,.1);
            display:flex; align-items:center; justify-content:center;
            font-size:1rem; color:rgba(255,255,255,.8);
        }
        .sl-feature__text { font-size:.82rem; color:rgba(255,255,255,.65); line-height:1.5; }
        .sl-feature__text strong { color:#fff; display:block; font-size:.88rem; margin-bottom:.1rem; }

        .sl-brand__bot { position:relative; z-index:1; font-size:.72rem; color:rgba(255,255,255,.3); }

        /* ── Right form panel ───────────────────────────────── */
        .sl-form-panel {
            background: #f4f7fb;
            display: flex; flex-direction: column;
            justify-content: center; align-items: center;
            padding: 2.5rem 2rem;
            overflow-y: auto;
        }
        .sl-form-wrap { width:100%; max-width:400px; }

        /* Header */
        .sl-hd { margin-bottom:1.75rem; }
        .sl-hd__eyebrow { font-size:.72rem; font-weight:700; text-transform:uppercase;
            letter-spacing:.1em; color:#0052cc; margin-bottom:.3rem; }
        .sl-hd__title { font-size:1.6rem; font-weight:700; color:#0d1b2a; margin:0 0 .3rem; }
        .sl-hd__sub { font-size:.84rem; color:#64748b; }

        /* Card */
        .sl-card {
            background:#fff; border-radius:18px; border:1px solid #e0e7ef;
            box-shadow: 0 4px 28px rgba(0,20,70,.07);
            padding:1.85rem;
            animation: slUp .3s ease both;
        }
        @keyframes slUp {
            from { opacity:0; transform:translateY(10px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* Label */
        .sl-label { font-size:.78rem; font-weight:600; color:#374151;
            margin-bottom:.32rem; display:block; }

        /* Input */
        .sl-field { position:relative; margin-bottom:1.1rem; }
        .sl-field__icon {
            position:absolute; left:.9rem; top:50%; transform:translateY(-50%);
            color:#94a3b8; font-size:.95rem; pointer-events:none;
        }
        .sl-input {
            width:100%; padding:.72rem 2.65rem .72rem 2.4rem;
            border:1.5px solid #dde3ec; border-radius:10px;
            font-size:.92rem; color:#0d1b2a; background:#f9fafb;
            outline:none; transition:border-color .15s, box-shadow .15s, background .15s;
        }
        .sl-input:focus {
            border-color:#0052cc; background:#fff;
            box-shadow: 0 0 0 3.5px rgba(0,82,204,.11);
        }
        .sl-input.err { border-color:#dc2626; }
        .sl-input.err:focus { box-shadow: 0 0 0 3.5px rgba(220,38,38,.1); }

        /* Eye toggle */
        .sl-eye {
            position:absolute; right:.8rem; top:50%; transform:translateY(-50%);
            background:none; border:none; color:#94a3b8; cursor:pointer; padding:.2rem;
            font-size:.95rem; line-height:1;
        }
        .sl-eye:hover { color:#475569; }



        /* Submit */
        .sl-btn {
            width:100%; padding:.82rem 1.5rem;
            background: linear-gradient(135deg, #0041a8, #0061d6);
            color:#fff; border:none; border-radius:10px;
            font-size:.95rem; font-weight:600; cursor:pointer;
            display:flex; align-items:center; justify-content:center; gap:.5rem;
            box-shadow: 0 4px 16px rgba(0,65,168,.25);
            transition: filter .15s, transform .12s;
            margin-bottom:1.1rem;
        }
        .sl-btn:hover  { filter:brightness(1.07); transform:translateY(-1px); }
        .sl-btn:active { transform:translateY(0); filter:brightness(.96); }


        /* Error banner */
        .sl-error {
            display:flex; align-items:flex-start; gap:.6rem;
            background:#fef2f2; border:1px solid #fecaca; border-radius:10px;
            padding:.82rem 1rem; font-size:.83rem; color:#b91c1c;
            margin-bottom:1.15rem;
        }
        .sl-error i { flex-shrink:0; font-size:.95rem; margin-top:.06rem; }

        /* Success flash */
        .sl-success {
            display:flex; align-items:flex-start; gap:.6rem;
            background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px;
            padding:.82rem 1rem; font-size:.83rem; color:#15803d;
            margin-bottom:1.15rem;
        }

        /* Back link */
        .sl-back { text-align:center; margin-top:1.4rem; font-size:.78rem; color:#94a3b8; }
        .sl-back a { color:#64748b; text-decoration:none; font-weight:500; }
        .sl-back a:hover { color:#0052cc; }

        /* ── Mobile ─────────────────────────────────────────── */
        @media (max-width:767px) {
            .sl-page { grid-template-columns:1fr; }
            .sl-brand { display:none; }
            .sl-form-panel { padding:2rem 1.25rem; background:#fff; }
        }
    </style>
</head>
<body>
<div class="sl-page">

    <!-- ── Left brand panel ──────────────────────────────── -->
    <aside class="sl-brand" aria-hidden="true">
        <div class="sl-brand__bubble sl-brand__bubble--1"></div>
        <div class="sl-brand__bubble sl-brand__bubble--2"></div>
        <div class="sl-brand__bubble sl-brand__bubble--3"></div>

        <div class="sl-brand__top">
            <div class="sl-brand__mark">U</div>
            <div class="sl-brand__name">UniHub</div>
            <div class="sl-brand__title">Your university,<br>all in one place.</div>
            <div class="sl-brand__sub">Browse programmes, track your interests, and stay connected with everything happening on campus.</div>
        </div>

        <div class="sl-brand__mid">
            <div class="sl-feature">
                <div class="sl-feature__icon"><i class="bi bi-journals"></i></div>
                <div class="sl-feature__text">
                    <strong>Explore programmes</strong>
                    Undergraduate &amp; postgraduate courses across all departments.
                </div>
            </div>
            <div class="sl-feature">
                <div class="sl-feature__icon"><i class="bi bi-bell"></i></div>
                <div class="sl-feature__text">
                    <strong>Register your interest</strong>
                    Get updates on the programmes you care about most.
                </div>
            </div>
            <div class="sl-feature">
                <div class="sl-feature__icon"><i class="bi bi-people"></i></div>
                <div class="sl-feature__text">
                    <strong>Staff &amp; module hub</strong>
                    Manage assignments, team contacts, and course details.
                </div>
            </div>
        </div>

        <div class="sl-brand__bot">&copy; <?= date('Y') ?> UniHub. All rights reserved.</div>
    </aside>

    <!-- ── Right form panel ──────────────────────────────── -->
    <main class="sl-form-panel" id="main-content">
    <div class="sl-form-wrap">

        <div class="sl-hd">
            <div class="sl-hd__eyebrow">Welcome back</div>
            <h1 class="sl-hd__title">Sign in to UniHub</h1>
            <p class="sl-hd__sub">Enter your credentials to continue.</p>
        </div>

        <!-- Error -->
        <?php if ($error): ?>
            <div class="sl-error" role="alert">
                <i class="bi bi-exclamation-circle-fill"></i>
                <?= htmlspecialchars($error, ENT_QUOTES) ?>
            </div>
        <?php endif; ?>

        <!-- Flash success -->
        <?php if (!empty($flash['success'])): ?>
            <div class="sl-success" role="status">
                <i class="bi bi-check-circle-fill"></i>
                <?= htmlspecialchars($flash['success'], ENT_QUOTES) ?>
            </div>
        <?php endif; ?>

        <div class="sl-card">
            <form method="POST" action="<?= base_url('/login') ?>" novalidate>
                <?= csrf_field() ?>

                <!-- Username -->
                <div>
                    <label class="sl-label" for="username">Username</label>
                    <div class="sl-field">
                        <i class="bi bi-person sl-field__icon"></i>
                        <input type="text" id="username" name="username"
                               class="sl-input <?= $error ? 'err' : '' ?>"
                               value="<?= htmlspecialchars($oldUser, ENT_QUOTES) ?>"
                               autocomplete="username" required autofocus>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="sl-label" for="password">Password</label>
                    <div class="sl-field">
                        <i class="bi bi-lock sl-field__icon"></i>
                        <input type="password" id="password" name="password"
                               class="sl-input <?= $error ? 'err' : '' ?>"
                               autocomplete="current-password" required>
                        <button type="button" class="sl-eye" id="eyeBtn" aria-label="Show password">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="sl-btn">
                    <i class="bi bi-box-arrow-in-right"></i> Sign in
                </button>

            </form>
        </div>

        <div class="sl-back">
            <a href="<?= base_url('/') ?>"><i class="bi bi-arrow-left me-1"></i>Back to UniHub</a>
        </div>

    </div>
    </main>

</div>

<script>
    const btn  = document.getElementById('eyeBtn');
    const inp  = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    btn?.addEventListener('click', () => {
        const show = inp.type === 'password';
        inp.type   = show ? 'text' : 'password';
        icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    });
</script>
</body>
</html>