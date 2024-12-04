<?php include "../layout/header.php"; ?>
<?php if ($user->checkUserRights("user")): ?>

    <h1 class="mt-3">Passwort von <?php echo $_GET['username']; ?> ändern <a href="allUser.php">Zurück</a></h1>
    <?php

    $username = $_POST['username'];
    $passwort = $_POST['passwort'];

    if (!empty($username) && !empty($passwort)) {
        $addUser = $user->resetPassword($username, $passwort);

    } else {
        ?>
        <h2>
            Status
        </h2>
        <form method="POST"
              action="editPasswort.php"
              class="m-3">
            </div>

            <div class="form-group">
                <label for="username">
                    Username
                </label>
                <input class="form-control" type="text" readonly name="username" id="username"
                       value="<?php echo $_GET['username']; ?>">
            </div>
            <div class="form-group mt-1">
                <label for="password">
                    Neues Passwort
                </label>
                <input class="form-control" type="password" name="passwort" id="passwort">
            </div>
            <button type="submit" class="btn btn-primary mt-2">Passwort ändern</button>
        </form>

        <?php
    }

    if ($addUser): ?>

        <div class="alert alert-primary" role="alert">
            <b>Passwort</b> erfolgreich geändert!
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