<?php
session_start();

header('Content-Type: application/json');

/* Only accept POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'error' => 'invalid_method']);
    exit;
}

/* Read inputs */
$first    = trim($_POST['first_name'] ?? '');
$last     = trim($_POST['last_name']  ?? '');
$password = trim(strtolower($_POST['password'] ?? ''));
$password = preg_replace('/\s+/', ' ', $password);

/* Require all three fields */
if ($first === '' || $last === '' || $password === '') {
    echo json_encode(['ok' => false, 'error' => 'missing_fields']);
    exit;
}

/* Normalize name for comparison */
function normalize_name(string $s): string {
    return strtolower(preg_replace('/\s+/', ' ', trim($s)));
}

$submitted_full = normalize_name($first . ' ' . $last);

/* Load guest list */
$json_path = __DIR__ . '/data/guests.json';
if (!file_exists($json_path)) {
    echo json_encode(['ok' => false, 'error' => 'no_guest_list']);
    exit;
}

$guests_data = json_decode(file_get_contents($json_path), true);
if (!$guests_data || !isset($guests_data['parties'])) {
    echo json_encode(['ok' => false, 'error' => 'invalid_guest_list']);
    exit;
}

/* Find matching party */
$matched_party = null;

foreach ($guests_data['parties'] as $party) {
    $party_pass = strtolower(preg_replace('/\s+/', ' ', trim($party['password'] ?? '')));
    if ($party_pass !== $password) {
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
    echo json_encode(['ok' => false, 'error' => 'no_match']);
    exit;
}

/* Auth success — set session */
$_SESSION['pt_authed']   = true;
$_SESSION['pt_first']    = $first;
$_SESSION['pt_last']     = $last;
$_SESSION['pt_party_id'] = $matched_party['id'] ?? '';

session_regenerate_id(true);

echo json_encode(['ok' => true, 'first' => $first, 'last' => $last]);
