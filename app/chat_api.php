<?php
header('Content-Type: application/json; charset=utf-8');
$baseDir = __DIR__ . '/chatStorage';
if (!is_dir($baseDir)) { mkdir($baseDir, 0777, true); }

function safeRoomName(string $room): string {
  $room = trim($room);
  $room = preg_replace('/[^a-zA-Z0-9_-]/', '_', $room);
  return substr($room, 0, 60);
}

$action = $_GET['action'] ?? 'listRooms';
$roomRaw = $_GET['room'] ?? '';
$room = safeRoomName($roomRaw);
$file = $room !== '' ? ($baseDir . '/' . $room . '.jsonl') : '';

if ($action === 'listRooms') {
  $rooms = [];
  foreach (glob($baseDir . '/*.jsonl') ?: [] as $path) {
    $name = basename($path, '.jsonl');
    if ($name !== '') { $rooms[] = $name; }
  }
  sort($rooms);
  echo json_encode(['rooms' => $rooms], JSON_UNESCAPED_UNICODE);
  exit;
}

if ($room === '') {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'room_required']);
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

if ($action === 'createRoom') {
  echo json_encode(['ok'=>true,'room'=>$room]);
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
