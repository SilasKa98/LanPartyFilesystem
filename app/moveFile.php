<?php
$sourcePathInput = $_POST['sourcePath'] ?? '';
$fileName = $_POST['fileName'] ?? '';
$targetPathInput = $_POST['targetPath'] ?? '';
$currentUrl = $_POST['currentUrl'] ?? 'index.php';

if ($sourcePathInput === '' || $fileName === '' || $targetPathInput === '') {
    header('LOCATION:' . $currentUrl);
    exit;
}

$rootPath = realpath('../mainStorage');
$sourcePath = realpath($sourcePathInput);
$targetPath = realpath($targetPathInput);
$safeFileName = basename($fileName);

$isInsideRoot = function ($candidatePath, $root) {
    return $candidatePath !== false && $root !== false && strpos($candidatePath, $root) === 0;
};

$source = $sourcePath . '/' . $safeFileName;
$destination = $targetPath . '/' . $safeFileName;

if (
    $isInsideRoot($sourcePath, $rootPath) &&
    $isInsideRoot($targetPath, $rootPath) &&
    is_file($source) &&
    is_dir($targetPath) &&
    !file_exists($destination)
) {
    rename($source, $destination);
}

header('LOCATION:' . $currentUrl);
exit;
