<?php include "../layout/header.php"; ?>
<?php if ($user->checkUserRights("user")): ?>

	<h1 class="mt-3">Neuen User hinzufügen</h1>

	<form method="POST" action="addUser.php" class="m-3">
	  <div class="form-group">
	    <label for="username">Username*</label>
	    <input type="text" class="form-control" id="username" placeholder="Username" name="username">
	  </div>
	  <div class="form-group">
	    <label for="password">Passwort*</label>
	    <input type="password" class="form-control" id="password" placeholder="Passwort" name="passwort">
	  </div>

		<div class="form-check">
		  <input class="form-check-input" type="radio" name="right" id="right1" value="1" checked>
		  <label class="form-check-label" for="right1">
		    Superuser (alle Rechte)
		  </label>
		</div>
		<div class="form-check">
		  <input class="form-check-input" type="radio" name="right" id="right2" value="2">
		  <label class="form-check-label" for="right2">
		    Nur Dateien Ansehen (+Herunterladen, Umbenennen, Code editieren)
		  </label>
		</div>
		<div class="form-check">
		  <input class="form-check-input" type="radio" name="right" id="right3" value="3">
		  <label class="form-check-label" for="right3">
		    Nur Dateien Hochladen (+erstellen)
		  </label>
		</div>
		<div class="form-check">
		  <input class="form-check-input" type="radio" name="right" id="right4" value="4">
		  <label class="form-check-label" for="right4">
		    Nur Dateien Ansehen und Hochladen
		  </label>
		</div>
		<div class="form-check">
		  <input class="form-check-input" type="radio" name="right" id="right5" value="5">
		  <label class="form-check-label" for="right5">
		    Nur Dateien Ansehen, Hochladen und Löschen
		  </label>
		</div>
		<div class="form-check">
		  <input class="form-check-input" type="radio" name="right" id="right6" value="6">
		  <label class="form-check-label" for="right6">
		    Nur Dateien Ansehen, Hochladen, Löschen, User verwalten (erstellen, ändern, löschen, Logs sehen)
		  </label>
		</div>

	  <button type="submit" class="btn btn-primary mt-2">Erstellen</button>
	</form>

<?php 

$username=$_POST['username'];
$passwort=$_POST['passwort'];
$right=$_POST['right'];

if (!empty($username) && !empty($passwort) && !empty($right)) {
	$addUser=$user->addUser($username, $passwort, $right);
}

if ($addUser): ?>

	<div class="alert alert-success">
		<b>User erfolgreich erstellt!</b> Der User wurde mit folgenden Daten erfolgreich erstellt:
		<ul>
			<li>Username: <?php echo $username;?></li>
			<li>Passwort: <?php echo $passwort;?></li>
		</ul>
		<a href="allUser.php">Zurück</a>
	</div>
<?php endif; ?>
</body>
</html>

<?php else: ?>
<div class="alert alert-danger">
	<b>Rechte Fehler!</b> Du hast nicht die Rechte für diesen Teil des Admins
</div>
<?php endif; ?>