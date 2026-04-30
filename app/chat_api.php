<?php
header('Content-Type: application/json; charset=utf-8');
$baseDir = __DIR__ . '/chatStorage';
if (!is_dir($baseDir)) { mkdir($baseDir, 0777, true); }
$roomsMetaFile = $baseDir . '/rooms.json';

function safeRoomName(string $room): string {
  $room = trim($room);
  $room = preg_replace('/[^a-zA-Z0-9_-]/', '_', $room);
  return substr($room, 0, 60);
}

function loadRoomsMeta(string $path): array {
  if (!file_exists($path)) { return []; }
  $raw = file_get_contents($path);
  $decoded = json_decode($raw, true);
  return is_array($decoded) ? $decoded : [];
}

function saveRoomsMeta(string $path, array $meta): void {
  file_put_contents($path, json_encode($meta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);
}

function roomRequiresPassword(array $meta, string $room): bool {
  return isset($meta[$room]['password_hash']) && $meta[$room]['password_hash'] !== '';
}

function roomPasswordValid(array $meta, string $room, string $password): bool {
  if (!roomRequiresPassword($meta, $room)) { return true; }
  return password_verify($password, $meta[$room]['password_hash']);
}

$action = $_GET['action'] ?? 'listRooms';
$roomRaw = $_GET['room'] ?? '';
$room = safeRoomName($roomRaw);
$passwordRaw = trim($_GET['password'] ?? '');
$file = $room !== '' ? ($baseDir . '/' . $room . '.jsonl') : '';
$roomsMeta = loadRoomsMeta($roomsMetaFile);

if ($action === 'listRooms') {
  $rooms = [];
  foreach (glob($baseDir . '/*.jsonl') ?: [] as $path) {
    $name = basename($path, '.jsonl');
    if ($name !== '') {
      $rooms[] = ['name' => $name, 'protected' => roomRequiresPassword($roomsMeta, $name), 'owner' => ($roomsMeta[$name]['owner'] ?? '')];
    }
  }
  usort($rooms, fn($a, $b) => strcmp($a['name'], $b['name']));
  echo json_encode(['rooms' => $rooms], JSON_UNESCAPED_UNICODE);
  exit;
}

if ($room === '') {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'room_required']);
  exit;
}

if ($action === 'createRoom') {
  $protect = ($_GET['protect'] ?? '0') === '1';
  $password = trim($_GET['newPassword'] ?? '');
  if ($protect && $password === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'password_required']);
    exit;
  }

  $creator = trim($_GET['creator'] ?? '');
  if ($protect) {
    $roomsMeta[$room] = ['password_hash' => password_hash($password, PASSWORD_DEFAULT), 'owner' => $creator];
  } elseif (!isset($roomsMeta[$room])) {
    $roomsMeta[$room] = ['password_hash' => '', 'owner' => $creator];
  } elseif ($creator !== '' && empty($roomsMeta[$room]['owner'])) {
    $roomsMeta[$room]['owner'] = $creator;
  }
  saveRoomsMeta($roomsMetaFile, $roomsMeta);
  if (!file_exists($file)) { touch($file); }
  echo json_encode(['ok' => true, 'room' => $room, 'protected' => $protect]);
  exit;
}


if ($action === 'deleteRoom') {
  $requester = trim($_GET['requester'] ?? '');
  $owner = trim($roomsMeta[$room]['owner'] ?? '');
  if ($owner === '' || $requester !== $owner) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'forbidden']);
    exit;
  }
  if (file_exists($file)) { unlink($file); }
  unset($roomsMeta[$room]);
  saveRoomsMeta($roomsMetaFile, $roomsMeta);
  echo json_encode(['ok' => true]);
  exit;
}

if ($action === 'checkRoomAccess') {
  $ok = roomPasswordValid($roomsMeta, $room, $passwordRaw);
  if (!$ok) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'invalid_password']);
    exit;
  }
  echo json_encode(['ok' => true]);
  exit;
}

if (roomRequiresPassword($roomsMeta, $room) && !roomPasswordValid($roomsMeta, $room, $passwordRaw)) {
  http_response_code(403);
  echo json_encode(['ok' => false, 'error' => 'invalid_password']);
  exit;
}

if (!file_exists($file)) { touch($file); }

if ($action === 'send') {
  $raw = file_get_contents('php://input');
  $data = json_decode($raw, true);
  $user = trim($data['user'] ?? '');
  $text = trim($data['text'] ?? '');
  if ($user === '' || $text === '') { http_response_code(400); echo json_encode(['ok'=>false]); exit; }
  $entry = ['room'=>$room,'user'=>$user,'text'=>$text,'ts'=>time()];
  file_put_contents($file, json_encode($entry, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND | LOCK_EX);
  echo json_encode(['ok'=>true]);
  exit;
}

$lines = @file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
$messages = [];
foreach ($lines as $line) {
  $decoded = json_decode($line, true);
  if (is_array($decoded)) { $messages[] = $decoded; }
}
$messages = array_slice($messages, -200);
echo json_encode(['room'=>$room, 'messages'=>$messages], JSON_UNESCAPED_UNICODE);
