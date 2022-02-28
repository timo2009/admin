<?php include "../layout/header.php"; ?>


	<section id="ueberblick">
		<h1>Folgendes stellen wir hier vor:</h1>
		<ol>
			<li>Unsere Leistungen mit Erklärung</li>
			<li>System Informationen</li>
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


  <!-- SYSTHEM INFORMATIONEN -->
	<section id="bedingungshilfe">
      <div class="container">
        <div class="row">
          <div class="col-6">
            <header class="intro-container">
              <h1>#2 Stysthem Informationen</h1>
              <p>
                <ul>
                	<li>Version 1.1</li>                	
                	<li>Benötigter Speicherplatz: </li>
                	<li>Programmiert von Timo Streich</li>
                </ul>
              </p>
            </header>
          </div>
        </div>
      </div>
    </section>
<?php include "../layout/footer.php"; ?>