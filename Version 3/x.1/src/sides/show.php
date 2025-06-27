<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
include "../../class/.htUserClass.php";
include "../../class/.htAdminClass.php";

$user = new UserClass();
$cookie = $user->getAndCheckCookie($_COOKIE['login']);
if (!$cookie) {
    header('Location: ./login.php');
} else {
    $user->_setUsernameAndPasswort($cookie[0], $cookie[1], true);
}

?>
<?php if ($user->checkUserRights("read")): ?>

    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../systhem/styles.css">
        <script defer>document.addEventListener("touchstart", function () {
            }, true);</script>
    </head>
    <body>


    <?php

    $admin = new AdminClass($user);
    $pfad = $_GET['file'];

    if (isset($pfad)) {
        if (isset($_GET['all'])) {
            $html = $admin->showFile($pfad, true);
        } else {
            $html = $admin->showFile($pfad);
        }

        $timestamp = filemtime($html[3]);
        $filesize = filesize($html[3]);
        $isShared = $admin->isInShareMode($html[3]);

        $back_link = $_GET["pages"] ? "pages.php?type=" . $_GET['type'] : "list.php?type=" . $_GET['type'];

        echo "<div class='alert alert-info' role='alert'>
    <h1>Webseiten Informationen:</h1>
    <ol>
        <li>
            <a href='" . $back_link . "'>Zurück</a> |
            <a href='download.php?pfad=" . $html[3] . "'>Herunterladen*</a>";

        if ($html[4]) {
            echo " | <a href='edit.php?pfad=" . $html[3] .
                "&back=" . $back_link .
                (isset($_GET['pages']) ? "&pages=True" : "") .
                "'>Bearbeiten</a>";
        }

        echo "<br><small>* (wenn freigegeben auch ohne login)</small></li>";

        // Share Mode Anzeige
        echo "<li>";
        $displayPath = $html[3];
        $pos = strpos($displayPath, $admin->pages);

        if ($pos === 0) {
            echo "Öffentlicher Pfad (freigegeben, da Page): <a href='" . substr($html[3], 8) . "'>" . substr($html[3], 8) . "</a>";

        } else {
            if ($isShared) {
                echo "Öffentlicher Pfad: <a href='share.php?file=" . $html[3] . "'>" . substr($html[3], 8) . "</a>";
            } else {
                echo "Öffentlicher Pfad: <i>nicht freigegeben</i>";
            }
            echo " | <a class='btn btn-sm btn-secondary' href='toggle_share.php?pfad=" . urlencode($html[3]) . "&back=" . urlencode($html[2]) . "'>";
            echo $isShared ? "Freigabe entfernen" : "Freigeben";
            echo "</a></li>";
        }

        // Toggle-Button


        echo "<li>Datei: " . $html[1] . (isset($_GET['type']) ? " (" . ucfirst($_GET['type']) . ")" : "") . "</li>";
        echo "<li>Size: " . $admin->formatSizeUnits($filesize) . "</li>";
        echo "<li>Die Datei wurde zuletzt am " . date("d.m.Y", $timestamp) . " um " . date("H:i", $timestamp) . " geändert.</li>";
        echo "</ol></div><hr></body></f>";

        echo $html[0];
    }
    ?>

<?php else: ?>
    <div class="alert alert-danger">
        <b>Rechte Fehler!</b> Du hast nicht die Rechte für diesen Teil des Admins
    </div>
    </body>
    </html>
<?php endif; ?>