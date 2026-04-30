<?php
header('Content-Type: application/json; charset=utf-8');
$chatDir = '../mainStorage/chat';
$file = $chatDir . '/chat.jsonl';
if (!is_dir($chatDir)) { mkdir($chatDir, 0777, true); }
if (!file_exists($file)) { touch($file); }
$action = $_GET['action'] ?? 'list';
if ($action === 'send') {
  $raw = file_get_contents('php://input');
  $data = json_decode($raw, true);
  $user = trim($data['user'] ?? '');
  $text = trim($data['text'] ?? '');
  if ($user === '' || $text === '') { http_response_code(400); echo json_encode(['ok'=>false]); exit; }
  $entry = ['user'=>$user,'text'=>$text,'ts'=>time()];
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
echo json_encode(['messages'=>$messages], JSON_UNESCAPED_UNICODE);
