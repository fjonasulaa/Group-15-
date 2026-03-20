<?php
session_start();

// Guard — must have gone through login first
if (!isset($_SESSION['2fa_code'])) {
    header('Location: log-in.php');
    exit;
}

$error   = $_SESSION['2fa_error']   ?? '';
$success = $_SESSION['2fa_success'] ?? '';
unset($_SESSION['2fa_error'], $_SESSION['2fa_success']);

// Dev helper: show code on localhost so you can test without a mail server
$is_local = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']);
$dev_code = ($is_local && isset($_SESSION['2fa_code'])) ? $_SESSION['2fa_code'] : '';

$expires_in = max(0, (int)(($_SESSION['2fa_expires'] ?? time()) - time()));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Two-Factor Authentication | Wine Exchange</title>
  <link rel="icon" type="image/x-icon" href="../../images/icon.png">
  <link rel="stylesheet" href="../css/styles.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Jost:wght@300;400;500&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --burgundy:      #6B1E30;
      --burgundy-deep: #4E1522;
      --gold:          #C9A84C;
      --gold-light:    #E8D5A3;
      --gold-pale:     #F5EDD8;
      --white:         #FDFAF5;
      --text-dark:     #2A1018;
      --text-mid:      #7A4A55;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      background: var(--background-colour, var(--white));
      font-family: 'Jost', sans-serif;
    }

    .twofa-wrap {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 16px;
    }

    .card {
      position: relative;
      background: var(--white);
      border: 1px solid var(--gold-light);
      border-radius: 6px;
      padding: 52px 48px 44px;
      width: 100%;
      max-width: 460px;
      box-shadow: 0 2px 8px rgba(107,30,48,0.06), 0 20px 60px rgba(107,30,48,0.10);
      animation: fadeUp 0.5s ease both;
    }

    @keyframes fadeUp {
      from { opacity:0; transform:translateY(16px); }
      to   { opacity:1; transform:translateY(0); }
    }

    .card::before {
      content:'';
      position:absolute;
      top:0;left:0;right:0;
      height:3px;
      background:linear-gradient(90deg,var(--burgundy),var(--gold),var(--burgundy));
      border-radius:6px 6px 0 0;
    }

    .shield {
      width:52px;height:52px;
      background:linear-gradient(135deg,var(--burgundy),var(--burgundy-deep));
      border-radius:50%;
      display:flex;align-items:center;justify-content:center;
      margin:0 auto 22px;
      box-shadow:0 4px 16px rgba(107,30,48,0.28);
    }

    .shield svg { width:24px;height:24px;fill:var(--gold-light); }

    h1 {
      font-family:'Cormorant Garamond',serif;
      font-size:1.8rem;font-weight:600;
      color:var(--text-dark);
      text-align:center;letter-spacing:0.02em;
      margin-bottom:8px;
    }

    .subtitle {
      font-size:0.82rem;font-weight:300;
      color:var(--text-mid);text-align:center;
      line-height:1.65;margin-bottom:26px;
    }

    /* Dev banner — only visible on localhost */
    .dev-banner {
      background:#fff8e1;
      border:1px dashed #C9A84C;
      border-radius:4px;
      padding:10px 14px;
      margin-bottom:18px;
      text-align:center;
      font-size:0.78rem;
      color:#7A5A10;
    }

    .dev-banner strong { font-size:1.3rem;letter-spacing:0.2em;color:var(--burgundy); }

    .message {
      font-size:0.78rem;text-align:center;
      padding:10px 14px;border-radius:3px;
      margin-bottom:16px;
    }

    .message.error {
      background:rgba(107,30,48,0.08);
      color:var(--burgundy);
      border:1px solid rgba(107,30,48,0.18);
    }

    .message.success {
      background:rgba(201,168,76,0.12);
      color:#7A5A10;
      border:1px solid rgba(201,168,76,0.30);
    }

    .otp-row {
      display:flex;gap:9px;justify-content:center;
      margin-bottom:16px;
    }

    .otp-input {
      width:50px;height:60px;
      border:1.5px solid var(--gold-light);
      border-radius:4px;
      background:var(--gold-pale);
      font-family:'Cormorant Garamond',serif;
      font-size:1.6rem;font-weight:600;
      color:var(--text-dark);text-align:center;
      outline:none;
      transition:border-color 0.2s,box-shadow 0.2s,background 0.2s;
    }

    .otp-input::placeholder { color:var(--gold-light); }

    .otp-input:focus {
      border-color:var(--burgundy);
      background:var(--white);
      box-shadow:0 0 0 3px rgba(107,30,48,0.10);
    }

    .otp-input.filled { border-color:var(--gold);background:var(--white); }

    .otp-sep {
      display:flex;align-items:center;
      color:var(--gold);font-size:1.1rem;padding-bottom:2px;
    }

    .timer {
      text-align:center;font-size:0.74rem;
      color:var(--text-mid);letter-spacing:0.04em;
      margin-bottom:22px;
    }

    .timer span { color:var(--burgundy);font-weight:500; }

    input[name="code"] { display:none; }

    .submit-btn {
      display:block;width:100%;padding:14px;
      background:linear-gradient(135deg,var(--burgundy),var(--burgundy-deep));
      color:var(--gold-light);
      font-family:'Jost',sans-serif;
      font-size:0.82rem;font-weight:500;
      letter-spacing:0.14em;text-transform:uppercase;
      border:none;border-radius:4px;cursor:pointer;
      transition:transform 0.15s,box-shadow 0.15s;
      box-shadow:0 4px 14px rgba(107,30,48,0.26);
      margin-bottom:20px;
    }

    .submit-btn:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(107,30,48,0.34); }
    .submit-btn:active { transform:translateY(0); }
    .submit-btn:disabled { opacity:0.55;cursor:not-allowed;transform:none; }

    .resend-row {
      display:flex;align-items:center;justify-content:center;
      gap:6px;font-size:0.78rem;color:var(--text-mid);
    }

    .resend-link {
      color:var(--burgundy);text-decoration:none;
      font-weight:500;border-bottom:1px solid transparent;
      transition:border-color 0.2s;
    }

    .resend-link:hover { border-bottom-color:var(--burgundy); }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="twofa-wrap">
  <div class="card">

    <div class="shield">
      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 1L3 5v6c0 5.25 3.75 10.15 9 11.35C17.25 21.15 21 16.25 21 11V5L12 1zm-1 14l-3-3 1.41-1.41L11 12.17l4.59-4.58L17 9l-6 6z"/>
      </svg>
    </div>

    <h1>Verify Your Identity</h1>
    <p class="subtitle">
      Enter the 6-digit code sent to<br/>
      <strong style="color:var(--text-dark);"><?= htmlspecialchars($_SESSION['2fa_email'] ?? '') ?></strong>
    </p>

    <?php if ($dev_code): ?>
      <div class="dev-banner">
        🛠 <strong>Dev mode</strong> — your code is: <strong><?= $dev_code ?></strong><br/>
        <span style="font-size:0.72rem;opacity:0.7;">(This banner only shows on localhost)</span>
      </div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form id="twofa-form" action="verify-2fa.php" method="POST" autocomplete="off">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
      <input type="hidden" name="code" id="combined-code">

      <div class="otp-row">
        <input class="otp-input" type="text" maxlength="1" inputmode="numeric" placeholder="·" required>
        <input class="otp-input" type="text" maxlength="1" inputmode="numeric" placeholder="·" required>
        <input class="otp-input" type="text" maxlength="1" inputmode="numeric" placeholder="·" required>
        <span class="otp-sep">—</span>
        <input class="otp-input" type="text" maxlength="1" inputmode="numeric" placeholder="·" required>
        <input class="otp-input" type="text" maxlength="1" inputmode="numeric" placeholder="·" required>
        <input class="otp-input" type="text" maxlength="1" inputmode="numeric" placeholder="·" required>
      </div>

      <div class="timer">Code expires in: <span id="timer-display">10:00</span></div>

      <button type="submit" class="submit-btn" id="submit-btn">Confirm Code</button>
    </form>

    <div class="resend-row">
      <span>Didn't receive it?</span>
      <a href="resend-2fa.php" class="resend-link" id="resend-link">Resend Code</a>
    </div>

  </div>
</div>

<?php include 'footer.php'; ?>

<script>
  const inputs     = Array.from(document.querySelectorAll('.otp-input'));
  const combined   = document.getElementById('combined-code');
  const submitBtn  = document.getElementById('submit-btn');
  const timerEl    = document.getElementById('timer-display');

  // ── OTP input behaviour ───────────────────────────────────────────────
  inputs.forEach((input, i) => {
    input.addEventListener('input', () => {
      input.value = input.value.replace(/\D/g, '');
      input.value ? input.classList.add('filled') : input.classList.remove('filled');
      if (input.value && i < inputs.length - 1) inputs[i + 1].focus();
    });

    input.addEventListener('keydown', e => {
      if (e.key === 'Backspace' && !input.value && i > 0) {
        inputs[i - 1].value = '';
        inputs[i - 1].classList.remove('filled');
        inputs[i - 1].focus();
      }
    });

    input.addEventListener('paste', e => {
      e.preventDefault();
      const pasted = (e.clipboardData || window.clipboardData)
        .getData('text').replace(/\D/g, '').slice(0, 6);
      pasted.split('').forEach((ch, j) => {
        if (inputs[j]) { inputs[j].value = ch; inputs[j].classList.add('filled'); }
      });
      const next = inputs[Math.min(pasted.length, inputs.length - 1)];
      if (next) next.focus();
    });
  });

  // ── Combine on submit ─────────────────────────────────────────────────
  document.getElementById('twofa-form').addEventListener('submit', e => {
    const code = inputs.map(i => i.value).join('');
    if (code.length < 6) { e.preventDefault(); alert('Please enter all 6 digits.'); return; }
    combined.value = code;
    submitBtn.textContent = 'Verifying…';
    submitBtn.disabled = true;
  });

  // ── Countdown ─────────────────────────────────────────────────────────
  let seconds = <?= $expires_in ?>;

  function fmt(s) {
    return String(Math.floor(s/60)).padStart(2,'0') + ':' + String(s%60).padStart(2,'0');
  }

  timerEl.textContent = fmt(seconds);

  const tick = setInterval(() => {
    seconds--;
    if (seconds <= 0) {
      clearInterval(tick);
      timerEl.textContent = '00:00';
      submitBtn.disabled = true;
      submitBtn.textContent = 'Code Expired';
      return;
    }
    timerEl.textContent = fmt(seconds);
  }, 1000);
</script>
</body>
</html>