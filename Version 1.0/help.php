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
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<!-- JavaScript Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<script defer>document.addEventListener("touchstart", function(){}, true);</script>
	<script type="text/javascript">
		function setCookie(cname,cvalue,exdays) {
			let d = new Date();
			d.setTime(d.getTime() + (exdays*24*60*60*1000));
			let expires = "expires=" + d.toGMTString();
			document.cookie = cname + "=" + cvalue + ";" + expires +
			";path=/;SameSite=Lax";
		}
		function logout() {
			setCookie('login', false, -1);
			location.reload();
		}
	</script>
	<link rel="stylesheet" type="text/css" href="styles.min.css">
	<title>Hilfe</title>
</head>
<body>
	<!-- NAVBAR --->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	  <div class="container-fluid">
	    <a class="navbar-brand" href="index.php">Startseite</a>
	    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
	      <span class="navbar-toggler-icon"></span>
	    </button>
	    <div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav">
				<li class="nav-item">
	    			<a class="navbar-brand" href="form.php">Hochladen</a>
				</li>					
				<li class="nav-item">
	    			<a class="navbar-brand" href="list.php">Alle Dateien</a>
				</li>				
				<li class="nav-item">
	    			<a class="navbar-brand" href="help.php">Hilfe</a>
				</li>
			</ul>			
			<ul class="navbar-nav ms-auto mt-2">
				<li class="nav-item" style="margin-right: 25%;">
				  <button onclick="logout()" class="btn btn-info">Logout</button>
				</li>
			</ul>
	    </div>
	  </div>
	</nav>

	<section id="ueberblick">
		<h1>Folgendes stellen wir hier vor:</h1>
		<ol>
			<li>Unsere Leistungen mit Erklärung</li>
		</ol>
	</section>

	<!-- BEDINUNGSHILFE -->
	<section id="bedingungshilfe">
      <div class="container">
        <div class="row">
          <div class="col-6">
            <header class="intro-container">
              <h1>#1 Unser Leistungen</h1>
              <p>
                Unser Admin bietet zahlreiche Funktionen, die geschützt von einem sicherem Login, das Programmieren erleichtern, vorallem, dass man Dateien ohne großen Aufwand hochladen kann. Außerdem bieten wir zu jeder hochgeladenen Datei zahlreiche Informationen und Funktionen an z.B eine Datei löschen oder den Pfad der Datei sehen...
              </p>
            </header>
          </div>
        </div>
        <div class="row">
          <div class="col-3">
            <article class="leistungs-teaser-box">
              <h2>Das Hochladen</h2>
              <p>
                Auf der Startseite befindet sich ein Hochlade Formular, einfach nur die Datei auswählen und auf Upload klicken, folgende Dateitypen sind erlaubt: Viedeo, Audio, Bild und Text.
                Nach dem Hochladen erscheint ein Fenster mit Informationen über die Datei (z.B abloluten Pfad, tmp Name, Größe, ...). Eine schon existierende Datei wird überschrieben. Die Dateien werden nach Typ sortiert abgelegt, um zu wissen vo sie sich befinden gibt es einen Absoluten Pfad zu der Datei. PHP Dateien können nicht Hochgeladen werden.
              </p>
            </article>
          </div>
          <div class="col-3">
            <article class="leistungs-teaser-box">
              <h2>Der Überblick</h2>
              <p>
                Um einen Überblick über die hochgeladenen Dateien zu bekommen, gibt es eine Seite die unter Dateien zu finden ist. Hier gibt es die vorhin genannten Kategogien (Video, ...). Ein Auswahlmodul öffnet sich, wenn man auf Datei Art geht(die Voreinstellung ist Text), dort kann man den Dateityp einstellen. In einer Tabelle, sieht man den Datei Namen und den Pfad zu der Datei(für z.B Verlinkungen oder Bilder). PHP Dateien werden nicht angezeigt(diese können nicht Hochgeladen werden).
              </p>
          </article>
          </div>
        </div>
        <div class="row">
          <div class="col-3">
            <article class="leistungs-teaser-box">
              <h2>Einzelne Dateiansicht</h2>
              <p>
                Wenn man, in der gerade beschriebenen Tabelle auf den Verlinkten Pfad geht, gelangt man in einem neuem Tab auf eine Vorschau(bei Audios und Viedeos werden die jeweiligen Dateien direkt abgespielt) wo ganz oben ein blaues Info Feld ist. In diesem gibt es einen Link zurück und zum Herunterladen, sowie zahlreiche Informationen.
              </p>
          </article>
          </div>
          <div class="col-3">
            <article class="leistungs-teaser-box">
              <h2>Das Löschen</h2>
              <p>
                Um eine Datei zu löschen, in der Tabelle auf den Mülleimer drücken und das Löschen bestätigen.
                <h2>
	                !!! Warnung !!!<br>
	                Die gelöschten Dateien sind nicht wieder herstellbar(deswegen die Bestätigung)!
	            </h2>
              </p>
          </article>
          </div>
        </div>
      </div>
    </section>
	</section>
</body>
</html>