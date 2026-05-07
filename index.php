<?php
session_start();

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
<div class="pt-gate" id="ptGate">

  <div class="pt-gate__top">perfectly taylored</div>

  <div class="pt-gate__main">
    <div>
      <span class="pt-gate__eyebrow">Invitation only</span>
      <h1 class="pt-gate__title">Come on in.</h1>
    </div>
    <p class="pt-gate__deck">Enter your name and the password from your invitation text.</p>

    <form class="pt-gate__form" id="ptGateForm" method="post" action="auth.php" autocomplete="off" novalidate>
      <label class="pt-gate__field">
        <span class="pt-gate__label">First name</span>
        <input class="pt-gate__input" id="ptGateFirst" name="first_name" type="text" autocomplete="given-name" autocapitalize="words" spellcheck="false">
      </label>
      <label class="pt-gate__field">
        <span class="pt-gate__label">Last name</span>
        <input class="pt-gate__input" id="ptGateLast" name="last_name" type="text" autocomplete="family-name" autocapitalize="words" spellcheck="false">
      </label>
      <label class="pt-gate__field">
        <span class="pt-gate__label">Password</span>
        <div class="pt-gate__inputwrap">
          <input class="pt-gate__input pt-gate__input--with-icon" id="ptGatePass" name="password" type="text" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false">
          <button type="button" class="pt-gate__input-help" id="ptGateHintBtn" aria-label="Password hint">?</button>
          <div class="pt-gate__tip is-hidden" id="ptGateTip">Your password was sent to you along with the link to this website via text message.</div>
        </div>
        <button type="button" class="pt-gate__lost" id="ptGateLost">Lost your password? We'll send it.</button>
      </label>
      <div class="pt-gate__hint<?php echo $err ? ' is-error' : ''; ?>" id="ptGateHint"><?php echo $err ? "That doesn't match our list. Try again?" : ''; ?></div>
      <div class="pt-gate__actions">
        <button type="submit" class="pt-gate__submit" id="ptGateSubmit" disabled>Enter &rarr;</button>
      </div>
    </form>
  </div>

  <div class="pt-gate__bottom">San Diego &middot; Oct 24, 2026</div>

</div>
<script src="js/gate.js"></script>
</body>
</html>
