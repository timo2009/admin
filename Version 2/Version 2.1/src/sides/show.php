<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../../class/.htUserClass.php";
include "../../class/.htAdminClass.php";

$user=new UserClass();
$cookie=$user->getAndCheckCookie($_COOKIE['login']);
if (!$cookie) {
	header('Location: ./login.php');
}
else {
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
<script defer>document.addEventListener("touchstart", function(){}, true);</script>
</head>
<body>


<?php 

$admin=new AdminClass($user);
$pfad=$_GET['file'];

if (isset($pfad)) {
	if (isset($_GET['all'])) {
		$html=$admin->showFile($pfad, true);
	}
	else {
		$html=$admin->showFile($pfad);
	}
	$timestamp = filemtime($html[3]);
	$filesize=filesize($html[3]);
	echo "<div class='alert alert-info' role='alert'><h1>Webseiten Informationen:</h1><ol><li><a href='".$html[2]."'>Zurück</a> | <a href='download.php?pfad=".$html[3]."'>Herunterladen</a>";
	if ($html[4]) {
		echo " | <a href='edit.php?pfad=".$html[3]."&&back=".$html[2]."'>Bearbeiten</a></li>\n";
	}
	else {
		echo "</li>";
	}
	echo "<li>Pfad: <a href='".substr($html[3], 8)."'>".substr($html[3], 8)."</a></li>";
	echo "<li>Datei: ".$html[1]."</li>";
	echo "<li>Size: ".$admin->formatSizeUnits($filesize)."</li>";	
	echo '<li>Die Datei wurde zuletzt am '.date("d.m.Y",$timestamp).' um '.date("H:i",$timestamp).' geändert.</li>';
	echo "</ol></div><hr></body>
    </f>";

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