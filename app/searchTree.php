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
        $pathSegments = array_values(array_filter(explode(DIRECTORY_SEPARATOR, $relativePath), 'strlen'));
        if (!$item->isDir() && count($pathSegments) > 0) {
            array_pop($pathSegments);
        }
        $results[] = [
            'name' => $item->getFilename(),
            'relativePath' => $relativePath,
            'type' => $item->isDir() ? 'folder' : 'file',
            'pathSegments' => $pathSegments
        ];
    }

    if (count($results) >= 150) {
        break;
    }
}

echo json_encode(['results' => $results], JSON_UNESCAPED_UNICODE);
