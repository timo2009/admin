<?php
$shareFile = "../.htdatabase/.htshares.txt";

if (!isset($_GET['file'])) {
    http_response_code(400);
    exit("Fehlender Dateipfad.");
}

if (!file_exists($shareFile)) {
    http_response_code(500);
    exit("Share-Datei nicht gefunden.");
}

$file = $_GET['file'];

$shares = file($shareFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$file = trim($file);


if (in_array($file, $shares)) {
    $mime = mime_content_type($file);
    header("Content-Type: $mime");
    header("Content-Length: " . filesize($file));
    readfile($file);
} else {
    http_response_code(403);
    exit("Datei ist nicht freigegeben.");
}
