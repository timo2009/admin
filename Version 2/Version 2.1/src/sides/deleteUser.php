<?php include "../layout/header.php"; ?>
<?php if ($user->checkUserRights("user")): ?>

	<h1 class="mt-3">User löschen</h1>
    <?php

    $username=$_POST['username'];

    if (!empty($username)) {
        $addUser=$user->deleteUser($username);
    } else {
        ?>

        <form method="POST" action="deleteUser.php" class="m-3">
	  <div class="form-group">
	    <label for="username">Username</label>
	    <input readonly type="text" class="form-control" id="username" value="<?php echo $_GET["username"]?>" name="username">
	  </div>

	  <button type="submit" class="btn btn-primary mt-2">Bestätigen</button>
	</form>


    <?php
}

if ($addUser): ?>

	<div class="alert alert-success">
		<b>User erfolgreich gelöscht!</b>
        <br>
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