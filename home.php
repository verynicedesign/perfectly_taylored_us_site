<?php
session_start();

if (empty($_SESSION['pt_authed'])) {
    header('Location: index.php');
    exit;
}

$guest = isset($_SESSION['pt_guest']) ? $_SESSION['pt_guest'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Perfectly Taylored</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Gloock&family=Inter:wght@300;400;500;600&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/tokens.css">
</head>
<body>
<p>Authenticated as <?php echo htmlspecialchars($guest, ENT_QUOTES, 'UTF-8'); ?>. Wedding content goes here.</p>
<p><a href="logout.php">Log out</a></p>
</body>
</html>
