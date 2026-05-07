<?php
session_start();

/* Only accept POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

/* Read inputs */
$first    = trim($_POST['first_name'] ?? '');
$last     = trim($_POST['last_name']  ?? '');
$password = trim($_POST['password']   ?? '');

/* Require all three fields */
if ($first === '' || $last === '' || $password === '') {
    header('Location: index.php?err=1');
    exit;
}

/* Normalize the submitted full name for comparison */
function normalize_name(string $s): string {
    return strtolower(preg_replace('/\s+/', ' ', trim($s)));
}

$submitted_full = normalize_name($first . ' ' . $last);

/* Load guest list */
$json_path = __DIR__ . '/data/guests.json';
if (!file_exists($json_path)) {
    header('Location: index.php?err=1');
    exit;
}

$guests_data = json_decode(file_get_contents($json_path), true);
if (!$guests_data || !isset($guests_data['parties'])) {
    header('Location: index.php?err=1');
    exit;
}

/* Find matching party:
   1. Password must match exactly (not lowercased)
   2. Submitted name must match a member in that party */
$matched_party = null;

foreach ($guests_data['parties'] as $party) {
    if (($party['password'] ?? '') !== $password) {
        continue;
    }
    foreach ($party['members'] as $member) {
        $member_full = normalize_name($member['first'] . ' ' . $member['last']);
        if ($member_full === $submitted_full) {
            $matched_party = $party;
            break 2;
        }
    }
}

if ($matched_party === null) {
    header('Location: index.php?err=1');
    exit;
}

/* Auth success */
$_SESSION['pt_authed']    = true;
$_SESSION['pt_first']     = $first;
$_SESSION['pt_last']      = $last;
$_SESSION['pt_party_id']  = $matched_party['id'];

session_regenerate_id(true);
header('Location: home.php');
exit;
