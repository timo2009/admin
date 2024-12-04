<?php 
include "./class/.htUserClass.php";
include "./class/.htAdminClass.php";

$user=new UserClass();
$cookie=$user->getAndCheckCookie($_COOKIE['login']);
if (!$cookie) {
	header('Location: ./login.php');
}
else {
	$user->_setUsernameAndPasswort($cookie[0], $cookie[1], true);
}

?>
<style type="text/css">
	a, a:visited, a:link{
		text-decoration: none;
		color: blue;
	}
	a:hover {
		color: #000;
	}
</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script defer>document.addEventListener("touchstart", function(){}, true);</script>
<?php 
$admin=new AdminClass();

$pfad=$_GET['file'];
if (isset($pfad)) {
	$html=$admin->showFile($pfad);
	$timestamp = filemtime($html[3]);
	$filesize=filesize($html[3]);
	echo "<div class='alert alert-info' role='alert'><h1>Webseiten Informationen:</h1><ol><li><a href='".$html[2]."'>Zurück</a> | <a href='download.php?pfad=".$html[3]."'>Herunterladen</a></li>\n";
	echo "<li>Pfad: ".substr($html[3], 2)."</li>";	
	echo "<li>Datei: ".$html[1]."</li>";
	echo "<li>Size: ".$admin->formatSizeUnits($filesize)."</li>";	
	echo '<li>Die Datei wurde zuletzt am '.date("d.m.Y",$timestamp).' um '.date("H:i",$timestamp).' geändert.</li>';
	echo "</ol></div><hr>";
	echo $html[0];
}
?>