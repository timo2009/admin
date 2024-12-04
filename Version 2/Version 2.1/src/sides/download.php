<?php

include "../../class/.htUserClass.php";

$user = new UserClass();
$cookie = $user->getAndCheckCookie($_COOKIE['login']);

$pfad = $_GET['pfad'];
$log = $_GET['log'];

if (file_exists($pfad) && $log == "") {
    header("Content-Disposition: attachment; filename=" . basename($pfad));
    header('Content-Length: ' . filesize($pfad));
    header('Cache-Control: no-cache');
    header('Content-Transfer-Encoding: chunked');
    readfile($pfad);
    exit;
} elseif ($log != "" && $cookie == true) {
    $logFilePath = '../.htdatabase/.htlog.txt';
    header("Content-Disposition: attachment; filename=" . basename($logFilePath));
    header('Content-Length: ' . filesize($logFilePath));
    header('Cache-Control: no-cache');
    header('Content-Transfer-Encoding: chunked');
    readfile($logFilePath);
    exit;

} else {
    var_dump($cookie);
    var_dump($log);
    // Zugriff verweigern, wenn Cookie ungültig ist
    http_response_code(403);
    echo "Ein unerwarteter Fehler ist aufgetreten.";
    exit;
}
?>
