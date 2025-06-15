<?php
include "../../class/.htUserClass.php";
include "../../class/.htAdminClass.php";

$user = new UserClass();
$cookie = $user->getAndCheckCookie($_COOKIE['login']);
if (!$cookie) {
    echo "
		<script>
			location.href='login.php'
		</script>
		";
    exit;
} else {
    $user->_setUsernameAndPasswort($cookie[0], $cookie[1], true);
    $admin = new AdminClass($user);
}


if (!isset($_GET['file'])) {
    http_response_code(400);
    exit("Fehlender Dateipfad.");
}

$file = realpath($_GET['file']);
$baseDir = realpath(__DIR__ . "/f/cloud/"); // Nur Zugriff auf Dateien darunter

if (strpos($file, $baseDir) !== 0 || !file_exists($file)) {
    http_response_code(403);
    exit("Zugriff verweigert.");
}

$mime = mime_content_type($file);
header("Content-Type: $mime");
header("Content-Length: " . filesize($file));
readfile($file);