<?php
$error = $error ?? null;
$sent  = $sent  ?? false;
$flash = $flash  ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password | UniHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body {
            height: 100%; margin: 0;
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Split layout ─────────────────────────────────── */
        .sl-page { min-height: 100vh; display: grid; grid-template-columns: 1fr 1fr; }

        /* ── Left brand panel (identical to login) ────────── */
        .sl-brand {
            background: linear-gradient(145deg, #001a3d 0%, #003380 50%, #005ce6 100%);
            display: flex; flex-direction: column;
            justify-content: space-between;
            padding: 3rem 3.5rem;
            position: relative; overflow: hidden;
        }
        .sl-brand__bubble { position:absolute; border-radius:50%; background:rgba(255,255,255,.05); }
        .sl-brand__bubble--1 { width:400px;height:400px; top:-130px; right:-110px; }
        .sl-brand__bubble--2 { width:260px;height:260px; bottom:-70px; left:-70px; }
        .sl-brand__bubble--3 { width:160px;height:160px; bottom:140px; right:40px; background:rgba(255,255,255,.03); }
        .sl-brand__top { position:relative; z-index:1; }
        .sl-brand__mark {
            width:3.2rem; height:3.2rem; border-radius:12px;
            background:linear-gradient(135deg,#e8a020,#ffd37a);
            display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; font-weight:800; color:#11213a;
            box-shadow:0 6px 20px rgba(232,160,32,.3); margin-bottom:1.5rem;
        }
        .sl-brand__name { font-size:.75rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:rgba(255,255,255,.5); margin-bottom:.4rem; }
        .sl-brand__title { font-size:2rem; font-weight:700; color:#fff; line-height:1.2; margin-bottom:.75rem; }
        .sl-brand__sub { font-size:.92rem; color:rgba(255,255,255,.6); line-height:1.65; max-width:300px; }
        .sl-brand__bot { position:relative; z-index:1; font-size:.72rem; color:rgba(255,255,255,.3); }

        /* ── Right form panel ─────────────────────────────── */
        .sl-form-panel {
            background: #f4f7fb;
            display: flex; flex-direction: column;
            justify-content: center; align-items: center;
            padding: 2.5rem 2rem; overflow-y: auto;
        }
        .sl-form-wrap { width: 100%; max-width: 400px; }

        /* Header */
        .sl-hd { margin-bottom: 1.75rem; }
        .sl-hd__back {
            display: inline-flex; align-items: center; gap: .4rem;
            font-size: .8rem; color: #64748b; text-decoration: none; font-weight: 500;
            margin-bottom: 1.1rem;
        }
        .sl-hd__back:hover { color: #0052cc; }
        .sl-hd__icon {
            width: 3rem; height: 3rem; border-radius: 12px;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; color: #0052cc; margin-bottom: 1rem;
        }
        .sl-hd__eyebrow { font-size:.72rem; font-weight:700; text-transform:uppercase;
            letter-spacing:.1em; color:#0052cc; margin-bottom:.3rem; }
        .sl-hd__title { font-size:1.55rem; font-weight:700; color:#0d1b2a; margin:0 0 .35rem; }
        .sl-hd__sub { font-size:.84rem; color:#64748b; line-height:1.55; }

        /* Card */
        .sl-card {
            background: #fff; border-radius: 18px; border: 1px solid #e0e7ef;
            box-shadow: 0 4px 28px rgba(0,20,70,.07);
            padding: 1.85rem;
            animation: slUp .3s ease both;
        }
        @keyframes slUp {
            from { opacity:0; transform:translateY(10px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* Label + input */
        .sl-label { font-size:.78rem; font-weight:600; color:#374151; margin-bottom:.32rem; display:block; }
        .sl-field { position:relative; margin-bottom:1.35rem; }
        .sl-field__icon { position:absolute; left:.9rem; top:50%; transform:translateY(-50%);
            color:#94a3b8; font-size:.95rem; pointer-events:none; }
        .sl-input {
            width:100%; padding:.72rem 1rem .72rem 2.4rem;
            border:1.5px solid #dde3ec; border-radius:10px;
            font-size:.92rem; color:#0d1b2a; background:#f9fafb;
            outline:none; transition:border-color .15s, box-shadow .15s, background .15s;
        }
        .sl-input:focus { border-color:#0052cc; background:#fff; box-shadow:0 0 0 3.5px rgba(0,82,204,.11); }
        .sl-input.err  { border-color:#dc2626; }

        /* Submit */
        .sl-btn {
            width:100%; padding:.82rem 1.5rem;
            background:linear-gradient(135deg,#0041a8,#0061d6);
            color:#fff; border:none; border-radius:10px;
            font-size:.95rem; font-weight:600; cursor:pointer;
            display:flex; align-items:center; justify-content:center; gap:.5rem;
            box-shadow:0 4px 16px rgba(0,65,168,.25);
            transition:filter .15s, transform .12s;
        }
        .sl-btn:hover  { filter:brightness(1.07); transform:translateY(-1px); }
        .sl-btn:active { transform:translateY(0); filter:brightness(.96); }

        /* Error */
        .sl-error {
            display:flex; align-items:flex-start; gap:.6rem;
            background:#fef2f2; border:1px solid #fecaca; border-radius:10px;
            padding:.82rem 1rem; font-size:.83rem; color:#b91c1c; margin-bottom:1.1rem;
        }
        .sl-error i { flex-shrink:0; font-size:.95rem; margin-top:.06rem; }

        /* ── Sent state ───────────────────────────────────── */
        .sl-sent {
            text-align: center;
            animation: slUp .35s ease both;
        }
        .sl-sent__circle {
            width: 5rem; height: 5rem; border-radius: 50%;
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; color: #16a34a;
            margin: 0 auto 1.25rem;
            box-shadow: 0 4px 18px rgba(22,163,74,.15);
        }
        .sl-sent__title { font-size: 1.35rem; font-weight: 700; color: #0d1b2a; margin-bottom: .5rem; }
        .sl-sent__body  { font-size: .87rem; color: #64748b; line-height: 1.65; margin-bottom: 1.75rem; }

        /* Back link */
        .sl-back { text-align:center; margin-top:1.4rem; font-size:.78rem; color:#94a3b8; }
        .sl-back a { color:#64748b; text-decoration:none; font-weight:500; }
        .sl-back a:hover { color:#0052cc; }

        /* ── Mobile ───────────────────────────────────────── */
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
            <div class="sl-brand__title">We'll help you<br>get back in.</div>
            <div class="sl-brand__sub">Enter the email address associated with your account and we'll send you what you need.</div>
        </div>
        <div class="sl-brand__bot">&copy; <?= date('Y') ?> UniHub. All rights reserved.</div>
    </aside>

    <!-- ── Right form panel ──────────────────────────────── -->
    <main class="sl-form-panel" id="main-content">
    <div class="sl-form-wrap">

        <?php if ($sent): ?>

            <!-- ── Sent confirmation ──────────────────────── -->
            <div class="sl-sent">
                <div class="sl-sent__circle"><i class="bi bi-envelope-check-fill"></i></div>
                <div class="sl-sent__title">Check your inbox</div>
                <div class="sl-sent__body">
                    If we found anything linked to that email address, we've sent you the details.<br><br>
                    Don't see it? Check your spam folder, or try again with a different address.
                </div>
                <a href="<?= base_url('/login') ?>" class="sl-btn text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Back to sign in
                </a>
            </div>

        <?php else: ?>

            <div class="sl-hd">
                <a href="<?= base_url('/login') ?>" class="sl-hd__back">
                    <i class="bi bi-arrow-left"></i> Back to sign in
                </a>
                <div class="sl-hd__icon"><i class="bi bi-key"></i></div>
                <div class="sl-hd__eyebrow">Account recovery</div>
                <h1 class="sl-hd__title">Forgot your password?</h1>
                <p class="sl-hd__sub">No problem. Enter your email address and we'll send you a link to get back into your account.</p>
            </div>

            <?php if ($error): ?>
                <div class="sl-error" role="alert">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <?= htmlspecialchars($error, ENT_QUOTES) ?>
                </div>
            <?php endif; ?>

            <div class="sl-card">
                <form method="POST" action="<?= base_url('/forgot') ?>" novalidate>

                    <label class="sl-label" for="email">Email address</label>
                    <div class="sl-field">
                        <i class="bi bi-envelope sl-field__icon"></i>
                        <input type="email" id="email" name="email"
                               class="sl-input <?= $error ? 'err' : '' ?>"
                               autocomplete="email" required autofocus
                               placeholder="you@example.com">
                    </div>

                    <button type="submit" class="sl-btn">
                        <i class="bi bi-send"></i> Send recovery email
                    </button>

                </form>
            </div>

            <div class="sl-back">
                Remembered it?
                <a href="<?= base_url('/login') ?>">Sign in instead</a>
            </div>

        <?php endif; ?>

    </div>
    </main>

</div>
</body>
</html>