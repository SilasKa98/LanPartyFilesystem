<?php
header('Content-Type: application/json; charset=utf-8');

$query = trim($_GET['q'] ?? '');
$rootPath = realpath('../mainStorage');

if ($query === '' || $rootPath === false) {
    echo json_encode(['results' => []]);
    exit;
}

$lower = function($value) {
    return function_exists('mb_strtolower') ? mb_strtolower($value) : strtolower($value);
};
$needle = $lower($query);
$results = [];

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($iterator as $item) {
    $fullPath = $item->getPathname();
    $relativePath = ltrim(str_replace($rootPath, '', $fullPath), DIRECTORY_SEPARATOR);
    if ($relativePath === '') {
        continue;
    }

    if (strpos($lower($relativePath), $needle) !== false) {
        $results[] = [
            'name' => $item->getFilename(),
            'relativePath' => $relativePath,
            'type' => $item->isDir() ? 'folder' : 'file'
        ];
    }

    if (count($results) >= 150) {
        break;
    }
}

echo json_encode(['results' => $results], JSON_UNESCAPED_UNICODE);
