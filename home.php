<?php
session_start();

if (empty($_SESSION['pt_authed'])) {
    header('Location: index.php');
    exit;
}

$first = htmlspecialchars($_SESSION['pt_first'] ?? 'friend');
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
<style>
  body { background: var(--pine); color: var(--porcelain); min-height: 100svh; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 2rem; }
  h1 { font-family: var(--display); font-size: clamp(2rem, 8vw, 3.5rem); font-weight: 400; letter-spacing: -0.025em; }
  p { font-size: 0.9375rem; font-weight: 300; opacity: 0.6; }
  a { font-family: var(--body); font-size: 0.75rem; font-weight: 500; letter-spacing: 0.15em; text-transform: uppercase; color: var(--olivine); text-decoration: none; opacity: 0.8; }
  a:hover { opacity: 1; }
</style>
</head>
<body>
  <h1>You're in, <?= $first ?>.</h1>
  <p>The wedding website is coming. Check back soon.</p>
  <a href="logout.php">Log out</a>
</body>
</html>
