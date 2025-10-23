<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
$requestUri = $_SERVER["REQUEST_URI"];

if ($requestUri == "/") {
    header("Location: /index.php");
    exit;
}

$baseFile = basename(__FILE__);
$scriptName = $_SERVER["SCRIPT_NAME"];
$path = substr($requestUri, strlen($scriptName));
$path = ltrim($path, "/");

if ($path == "") {
    $path = "main/index.php";
}

$basePagesDir = ' . var_export(substr($this->pages, 7), true) . ';
$file = $basePagesDir . $path;

if (!file_exists($file)) {
    header("HTTP/1.0 404 Not Found");
    echo "404 - Datei nicht gefunden";
    exit;
}

$mime = mime_content_type($file);

$array = explode(".", $file);
if (end($array) === "php") {
    include $file;
} else {
    $videoTypes = ["video/mp4", "video/webm", "video/ogg"];

    if (in_array($mime, $videoTypes)) {
        header("Content-Type: $mime");
        header("Content-Length: " . filesize($file));
        header("Cache-Control: public, max-age=86400");
        readfile($file);
        exit;
    }

    header("Content-Type: $mime");
    echo file_get_contents($file);
}
