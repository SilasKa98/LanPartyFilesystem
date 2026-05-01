<?php

$maxPostSizeBytes = (int) ini_get('post_max_size') * 1024 * 1024;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Methode nicht erlaubt.';
    exit;
}

$currentUrl = $_POST['currentUrl'] ?? '../';
$path = $_POST['path'] ?? null;

if (empty($_POST) && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
    $limitMb = (int) (ini_get('post_max_size'));
    echo 'Upload zu groß. Erlaubt sind maximal '.$limitMb.' MB. Bitte php.ini (post_max_size / upload_max_filesize) erhöhen.';
    exit;
}

if ($path === null || !isset($_FILES['files']) || !is_array($_FILES['files']['name'])) {
    header('Location: '.$currentUrl);
    exit;
}

$targetDir = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
$fileCount = count($_FILES['files']['name']);

for ($i = 0; $i < $fileCount; $i++) {
    $fileName = $_FILES['files']['name'][$i] ?? '';
    $tmpName = $_FILES['files']['tmp_name'][$i] ?? '';
    $uploadError = $_FILES['files']['error'][$i] ?? UPLOAD_ERR_NO_FILE;

    if ($fileName === '' || $uploadError === UPLOAD_ERR_NO_FILE) {
        continue;
    }

    if ($uploadError !== UPLOAD_ERR_OK) {
        echo 'Upload-Fehler bei Datei '.$fileName.' (Code '.$uploadError.').';
        continue;
    }

    $fileInfo = pathinfo($fileName);
    $filename = $fileInfo['filename'] ?? '';
    $extension = $fileInfo['extension'] ?? '';

    $pathFilenameExt = $targetDir . $filename . ($extension !== '' ? '.'.$extension : '');

    if (file_exists($pathFilenameExt)) {
        echo 'Es existiert bereits eine Datei mit diesem Namen: '.$fileName.'.';
        continue;
    }

    move_uploaded_file($tmpName, $pathFilenameExt);
}

header('Location: '.$currentUrl);
exit;

?>
