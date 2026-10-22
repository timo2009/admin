<?php

// Hole den vollständigen Request-URI
$requestUri = $_SERVER["REQUEST_URI"];

if ($requestUri == "/") {
    header("Location: /index.php");
    exit;
}

$baseFile = basename(__FILE__);
$pathInfo = str_replace("/" . $baseFile, "", $requestUri);
$path = ltrim($pathInfo, "/");
if ($path == "") {
    $path = "main/index.html";
}


$file =  "./f/pages/".$path;
if (!file_exists($file)) {
    header("HTTP/1.0 404 Not Found");
    echo "404 - Datei nicht gefunden";
    exit;
}

// Bestimme MIME-Type der Datei
$mime = mime_content_type($file);

// Für PHP-Dateien: Skript ausführen und Ausgabe zeigen
if (pathinfo($file, PATHINFO_EXTENSION) === "php") {
    ob_start();
    include $file;
    $output = ob_get_clean();
    header("Content-Type: text/html; charset=utf-8");
    echo $output;
    exit;
}

// Für Videos oder sonstige Dateien den korrekten MIME-Type senden und Datei ausliefern
$videoTypes = ["video/mp4", "video/webm", "video/ogg"];

if (in_array($mime, $videoTypes)) {
    header("Content-Type: $mime");
    header("Content-Length: " . filesize($file));
    header("Cache-Control: public, max-age=86400");
    readfile($file);
    exit;
}

// Für alle anderen Dateien: einfach Inhalt ausgeben (z.B. HTML, CSS, JS)
header("Content-Type: $mime");
echo file_get_contents($file);
