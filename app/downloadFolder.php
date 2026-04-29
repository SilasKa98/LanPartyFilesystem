<?php
if (!isset($_GET['path']) || !isset($_GET['folder'])) {
    http_response_code(400);
    exit('Missing parameters');
}

$basePath = realpath('../mainStorage');
$requestedPath = realpath($_GET['path']);
$folderName = basename($_GET['folder']);
$folderPath = realpath($requestedPath . DIRECTORY_SEPARATOR . $folderName);

if ($basePath === false || $requestedPath === false || $folderPath === false) {
    http_response_code(404);
    exit('Folder not found');
}

if (strpos($requestedPath, $basePath) !== 0 || strpos($folderPath, $basePath) !== 0 || !is_dir($folderPath)) {
    http_response_code(403);
    exit('Access denied');
}

$tmpZip = tempnam(sys_get_temp_dir(), 'lancloud_zip_');
$zip = new ZipArchive();
if ($zip->open($tmpZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    http_response_code(500);
    exit('Could not create ZIP');
}

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($folderPath, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($iterator as $file) {
    $filePath = $file->getRealPath();
    $relativePath = $folderName . '/' . substr($filePath, strlen($folderPath) + 1);

    if ($file->isDir()) {
        $zip->addEmptyDir(str_replace('\\', '/', $relativePath));
    } else {
        $zip->addFile($filePath, str_replace('\\', '/', $relativePath));
    }
}

$zip->close();

header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $folderName . '.zip"');
header('Content-Length: ' . filesize($tmpZip));
readfile($tmpZip);
unlink($tmpZip);
exit;
?>
