<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

function pt_normalize($s) {
    $s = trim((string)$s);
    $s = function_exists('mb_strtolower') ? mb_strtolower($s, 'UTF-8') : strtolower($s);
    $s = preg_replace('/\s+/u', ' ', $s);
    return $s;
}

$first = isset($_POST['first_name']) ? $_POST['first_name'] : '';
$last  = isset($_POST['last_name'])  ? $_POST['last_name']  : '';
$pass  = isset($_POST['password'])   ? $_POST['password']   : '';

$first_norm = pt_normalize($first);
$last_norm  = pt_normalize($last);
$fullname   = pt_normalize($first_norm . ' ' . $last_norm);

$dataPath = __DIR__ . '/data/guests.json';
$raw = @file_get_contents($dataPath);
if ($raw === false) {
    header('Location: index.php?err=1');
    exit;
}

$data = json_decode($raw, true);
if (!is_array($data) || !isset($data['password']) || !isset($data['guests']) || !is_array($data['guests'])) {
    header('Location: index.php?err=1');
    exit;
}

if (!hash_equals((string)$data['password'], (string)$pass)) {
    header('Location: index.php?err=1');
    exit;
}

$matchedOriginal = null;
foreach ($data['guests'] as $guest) {
    if (pt_normalize($guest) === $fullname) {
        $matchedOriginal = $guest;
        break;
    }
}

if ($matchedOriginal === null) {
    header('Location: index.php?err=1');
    exit;
}

session_regenerate_id(true);
$_SESSION['pt_authed'] = true;
$_SESSION['pt_guest']  = $matchedOriginal;

header('Location: home.php');
exit;
