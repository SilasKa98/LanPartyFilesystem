<?php
$path = $_POST['path'] ?? '';
$fileName = $_POST['fileName'] ?? '';
$targetFolder = $_POST['targetFolder'] ?? '';
$currentUrl = $_POST['currentUrl'] ?? 'index.php';

if ($path === '' || $fileName === '' || $targetFolder === '') {
    header('LOCATION:' . $currentUrl);
    exit;
}

$source = $path . '/' . $fileName;
$destinationDir = $path . '/' . $targetFolder;
$destination = $destinationDir . '/' . $fileName;

if (is_file($source) && is_dir($destinationDir) && !file_exists($destination)) {
    rename($source, $destination);
}

header('LOCATION:' . $currentUrl);
exit;
