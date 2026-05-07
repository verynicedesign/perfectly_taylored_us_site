<?php
session_start();

/* Already logged in — skip gate */
if (!empty($_SESSION['pt_authed'])) {
    header('Location: home.php');
    exit;
}

$err = isset($_GET['err']) && $_GET['err'] === '1';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Perfectly Taylored</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@1,9..144,400&family=Gloock&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/tokens.css">
<link rel="stylesheet" href="css/gate.css">
</head>
<body>

<!-- PASSWORD GATE -->
<div class="pt-gate" id="ptGate">
  <div class="pt-gate__top">perfectly taylored</div>

  <!-- FORM STATE -->
  <div class="pt-gate__main" id="ptGateMain">
    <div>
      <span class="pt-gate__eyebrow">Invitation only</span>
      <h1 class="pt-gate__title">Come on in.</h1>
    </div>
    <p class="pt-gate__deck">Enter your name and the password from your invitation text.</p>
    <form class="pt-gate__form" id="ptGateForm" novalidate>
      <label class="pt-gate__field">
        <span class="pt-gate__label">First name</span>
        <input class="pt-gate__input" id="ptGateFirst" type="text" name="first_name" autocomplete="given-name" autocapitalize="words" spellcheck="false">
      </label>
      <label class="pt-gate__field">
        <span class="pt-gate__label">Last name</span>
        <input class="pt-gate__input" id="ptGateLast" type="text" name="last_name" autocomplete="family-name" autocapitalize="words" spellcheck="false">
      </label>
      <label class="pt-gate__field">
        <span class="pt-gate__label">Password</span>
        <div class="pt-gate__inputwrap">
          <input class="pt-gate__input pt-gate__input--with-icon" id="ptGatePass" type="text" name="password" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false">
          <button type="button" class="pt-gate__input-help" id="ptGateHintBtn" aria-label="What's my password?">
            <svg viewBox="0 0 16 16" width="14" height="14" aria-hidden="true"><circle cx="8" cy="8" r="7" fill="none" stroke="currentColor" stroke-width="1.2"/><path d="M5.6 5.9c.1-1.2 1.1-2 2.4-2 1.4 0 2.4.8 2.4 2 0 .9-.5 1.4-1.4 2-.7.5-1 .8-1 1.6v.3" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/><circle cx="8" cy="11.7" r="0.7" fill="currentColor"/></svg>
          </button>
          <div class="pt-gate__tip is-hidden" id="ptGateTip">Your password was sent to you along with the link to this website via text message.</div>
        </div>
        <button type="button" class="pt-gate__lost" id="ptGateLost">Lost your password? We'll send it.</button>
      </label>
      <div class="pt-gate__hint<?= $err ? ' is-error' : '' ?>" id="ptGateHint"><?= $err ? "That doesn't match our list. Try again?" : '' ?></div>
      <div class="pt-gate__actions">
        <button type="submit" class="pt-gate__submit" id="ptGateSubmit">Enter &rarr;</button>
      </div>
    </form>
  </div>

  <!-- WELCOME STATE (hidden initially) -->
  <div class="pt-gate__welcome is-hidden" id="ptGateWelcome">
    <span class="pt-gate__eyebrow">You're in</span>
    <h1 class="pt-gate__title">Welcome, <em id="ptGateWelcomeName"></em></h1>
  </div>

  <div class="pt-gate__bottom" id="ptGateBottom">San Diego &middot; Oct 24, 2026</div>
</div>

<!-- HELP MODAL -->
<div class="pt-help is-hidden" id="ptHelp">
  <div class="pt-help__scrim" id="ptHelpScrim"></div>
  <div class="pt-help__sheet">
    <button type="button" class="pt-help__close" id="ptHelpClose">&times;</button>
    <div id="ptHelpForm">
      <span class="pt-help__eyebrow">Password help</span>
      <h2 class="pt-help__title">Need your password?</h2>
      <p class="pt-help__deck">Enter your name and phone number and we'll resend your password.</p>
      <form class="pt-help__form" id="ptHelpFormEl" novalidate>
        <div class="pt-help__field">
          <span class="pt-help__label">Your name</span>
          <input class="pt-help__input" id="ptHelpName" type="text" autocapitalize="words" autocomplete="name">
        </div>
        <div class="pt-help__field">
          <span class="pt-help__label">Phone</span>
          <input class="pt-help__input pt-help__input--sans" id="ptHelpPhone" type="tel" inputmode="tel" autocomplete="tel" placeholder="(555) 123-4567">
        </div>
        <div class="pt-help__hint" id="ptHelpHint"></div>
        <div class="pt-help__actions">
          <button type="button" class="pt-help__cancel" id="ptHelpCancel">Cancel</button>
          <button type="submit" class="pt-help__submit" id="ptHelpSubmit">Send request &rarr;</button>
        </div>
      </form>
    </div>
    <div id="ptHelpSent" class="is-hidden" style="text-align:center;padding:1rem 0;">
      <span class="pt-help__eyebrow">Sent</span>
      <h2 class="pt-help__title">Got it.</h2>
      <p class="pt-help__deck">Joyce &amp; Ryan will text you back soon.</p>
      <div class="pt-help__actions" style="justify-content:center;margin-top:1.5rem;">
        <button type="button" class="pt-help__submit" id="ptHelpDone">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="js/gate.js"></script>
</body>
</html>
