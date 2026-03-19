<?php
session_start();

// Redirect if not in a 2FA flow
if (!isset($_SESSION['2fa_code'])) {
    header('Location: /login.php');
    exit;
}

$error   = $_SESSION['2fa_error']   ?? '';
$success = $_SESSION['2fa_success'] ?? '';
unset($_SESSION['2fa_error'], $_SESSION['2fa_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Two-Factor Authentication</title>
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
      align-items: center;
      justify-content: center;
      background: var(--white);
      font-family: 'Jost', sans-serif;
      overflow: hidden;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background:
        radial-gradient(ellipse 60% 50% at 10% 20%, rgba(201,168,76,0.12) 0%, transparent 70%),
        radial-gradient(ellipse 50% 60% at 90% 80%, rgba(107,30,48,0.09) 0%, transparent 70%);
      pointer-events: none;
    }

    .card {
      position: relative;
      background: var(--white);
      border: 1px solid var(--gold-light);
      border-radius: 4px;
      padding: 56px 52px 48px;
      width: 100%;
      max-width: 460px;
      box-shadow:
        0 2px 8px rgba(107,30,48,0.06),
        0 20px 60px rgba(107,30,48,0.10);
      animation: fadeUp 0.6s ease both;
    }

    @keyframes fadeUp {
      from { opacity:0; transform: translateY(18px); }
      to   { opacity:1; transform: translateY(0); }
    }

    .card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--burgundy), var(--gold), var(--burgundy));
      border-radius: 4px 4px 0 0;
    }

    .card::after {
      content: '';
      position: absolute;
      bottom: 20px; right: 20px;
      width: 40px; height: 40px;
      border-right: 1px solid var(--gold-light);
      border-bottom: 1px solid var(--gold-light);
      opacity: 0.6;
    }

    .shield-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 52px; height: 52px;
      background: linear-gradient(135deg, var(--burgundy), var(--burgundy-deep));
      border-radius: 50%;
      margin: 0 auto 24px;
      box-shadow: 0 4px 16px rgba(107,30,48,0.30);
    }

    .shield-icon svg { width: 24px; height: 24px; fill: var(--gold-light); }

    h1 {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.85rem;
      font-weight: 600;
      color: var(--text-dark);
      text-align: center;
      letter-spacing: 0.02em;
      margin-bottom: 8px;
    }

    .subtitle {
      font-size: 0.82rem;
      font-weight: 300;
      color: var(--text-mid);
      text-align: center;
      letter-spacing: 0.04em;
      margin-bottom: 28px;
      line-height: 1.6;
    }

    .message {
      font-size: 0.78rem;
      text-align: center;
      padding: 10px 14px;
      border-radius: 3px;
      margin-bottom: 18px;
    }

    .message.error {
      background: rgba(107,30,48,0.08);
      color: var(--burgundy);
      border: 1px solid rgba(107,30,48,0.18);
    }

    .message.success {
      background: rgba(201,168,76,0.12);
      color: #7A5A10;
      border: 1px solid rgba(201,168,76,0.30);
    }

    .otp-row {
      display: flex;
      gap: 10px;
      justify-content: center;
      margin-bottom: 32px;
    }

    .otp-input {
      width: 52px; height: 62px;
      border: 1.5px solid var(--gold-light);
      border-radius: 3px;
      background: var(--gold-pale);
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.6rem;
      font-weight: 600;
      color: var(--text-dark);
      text-align: center;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
      caret-color: var(--burgundy);
    }

    .otp-input::placeholder { color: var(--gold-light); }

    .otp-input:focus {
      border-color: var(--burgundy);
      background: var(--white);
      box-shadow: 0 0 0 3px rgba(107,30,48,0.10);
    }

    .otp-input.filled {
      border-color: var(--gold);
      background: var(--white);
    }

    .otp-sep {
      display: flex;
      align-items: center;
      color: var(--gold);
      font-size: 1.2rem;
      padding-bottom: 4px;
    }

    /* Hidden combined field for POST */
    input[name="code"] { display: none; }

    .submit-btn {
      display: block;
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, var(--burgundy), var(--burgundy-deep));
      color: var(--gold-light);
      font-family: 'Jost', sans-serif;
      font-size: 0.82rem;
      font-weight: 500;
      letter-spacing: 0.14em;
      text-transform: uppercase;
      border: none;
      border-radius: 3px;
      cursor: pointer;
      transition: transform 0.15s, box-shadow 0.15s;
      box-shadow: 0 4px 14px rgba(107,30,48,0.28);
      margin-bottom: 24px;
    }

    .submit-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(107,30,48,0.36);
    }

    .submit-btn:active { transform: translateY(0); }

    .submit-btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    .resend-row {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      font-size: 0.78rem;
      color: var(--text-mid);
    }

    .resend-link {
      color: var(--burgundy);
      text-decoration: none;
      font-weight: 500;
      border-bottom: 1px solid transparent;
      transition: border-color 0.2s;
    }

    .resend-link:hover { border-bottom-color: var(--burgundy); }

    .resend-link.disabled {
      color: var(--text-mid);
      pointer-events: none;
      cursor: default;
    }

    .timer {
      text-align: center;
      font-size: 0.74rem;
      color: var(--text-mid);
      margin-bottom: 20px;
      letter-spacing: 0.04em;
    }

    .timer span { color: var(--burgundy); font-weight: 500; }
  </style>
</head>
<body>

<div class="card">

  <div class="shield-icon">
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path d="M12 1L3 5v6c0 5.25 3.75 10.15 9 11.35C17.25 21.15 21 16.25 21 11V5L12 1zm-1 14l-3-3 1.41-1.41L11 12.17l4.59-4.58L17 9l-6 6z"/>
    </svg>
  </div>

  <h1>Verify Your Identity</h1>
  <p class="subtitle">
    Enter the 6-digit code sent to your email address.<br/>
    It expires in <span id="countdown-inline" style="color:var(--burgundy);font-weight:500;">10:00</span>.
  </p>

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
      <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" placeholder="·" required>
      <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" placeholder="·" required>
      <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" placeholder="·" required>
      <span class="otp-sep">—</span>
      <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" placeholder="·" required>
      <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" placeholder="·" required>
      <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" placeholder="·" required>
    </div>

    <div class="timer">Code expires in: <span id="timer-display">10:00</span></div>

    <button type="submit" class="submit-btn" id="submit-btn">Confirm Code</button>
  </form>

  <div class="resend-row">
    <span>Didn't receive it?</span>
    <a href="resend-2fa.php" class="resend-link" id="resend-link">Resend Code</a>
  </div>

</div>

<script>
  // ── OTP input behaviour ──────────────────────────────────────────────────
  const inputs      = Array.from(document.querySelectorAll('.otp-input'));
  const combinedEl  = document.getElementById('combined-code');
  const submitBtn   = document.getElementById('submit-btn');

  inputs.forEach((input, i) => {
    input.addEventListener('input', () => {
      const val = input.value.replace(/\D/g, '');
      input.value = val;
      val ? input.classList.add('filled') : input.classList.remove('filled');
      if (val && i < inputs.length - 1) inputs[i + 1].focus();
    });

    input.addEventListener('keydown', (e) => {
      if (e.key === 'Backspace' && !input.value && i > 0) {
        inputs[i - 1].value = '';
        inputs[i - 1].classList.remove('filled');
        inputs[i - 1].focus();
      }
    });

    input.addEventListener('paste', (e) => {
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

  // Combine digits into hidden field on submit
  document.getElementById('twofa-form').addEventListener('submit', (e) => {
    const code = inputs.map(i => i.value).join('');
    if (code.length < 6) {
      e.preventDefault();
      alert('Please enter all 6 digits.');
      return;
    }
    combinedEl.value = code;
    submitBtn.textContent = 'Verifying…';
    submitBtn.disabled = true;
  });

  // ── Countdown timer ──────────────────────────────────────────────────────
  const EXPIRY_SECONDS = <?= max(0, (int)(($_SESSION['2fa_expires'] ?? time()) - time())) ?>;
  let seconds = EXPIRY_SECONDS > 0 ? EXPIRY_SECONDS : 600;

  const timerEl     = document.getElementById('timer-display');
  const countdownEl = document.getElementById('countdown-inline');
  const resendLink  = document.getElementById('resend-link');

  function formatTime(s) {
    const m = String(Math.floor(s / 60)).padStart(2, '0');
    const sec = String(s % 60).padStart(2, '0');
    return `${m}:${sec}`;
  }

  const tick = setInterval(() => {
    seconds--;
    if (seconds <= 0) {
      clearInterval(tick);
      timerEl.textContent = countdownEl.textContent = '00:00';
      submitBtn.disabled = true;
      submitBtn.textContent = 'Code Expired';
      resendLink.classList.remove('disabled');
      return;
    }
    const formatted = formatTime(seconds);
    timerEl.textContent = countdownEl.textContent = formatted;
  }, 1000);

  timerEl.textContent = countdownEl.textContent = formatTime(seconds);
</script>

</body>
</html>