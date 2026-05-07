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
<link href="https://fonts.googleapis.com/css2?family=Gloock&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/tokens.css">
<link rel="stylesheet" href="css/gate.css">
</head>
<body>
<div class="gate">
  <header class="gate__wordmark">perfectly <em>taylored</em></header>
  <main class="gate__main">
    <form class="gate__form" method="post" action="auth.php" autocomplete="off" novalidate>
      <div class="gate__field">
        <label class="gate__label" for="first_name">First name</label>
        <input class="gate__input" type="text" id="first_name" name="first_name" autocomplete="off" spellcheck="false">
      </div>
      <div class="gate__field">
        <label class="gate__label" for="last_name">Last name</label>
        <input class="gate__input" type="text" id="last_name" name="last_name" autocomplete="off" spellcheck="false">
      </div>
      <div class="gate__field">
        <label class="gate__label" for="password">Password</label>
        <input class="gate__input" type="password" id="password" name="password" autocomplete="off">
      </div>
      <button class="gate__submit" type="submit" disabled>Open the invitation</button>
    </form>
<?php if ($err): ?>
    <p class="gate__error">That doesn't match our list. Try again?</p>
<?php endif; ?>
  </main>
  <footer class="gate__tagline">It's only forever, not long at all.</footer>
</div>
<script src="js/gate.js"></script>
</body>
</html>
