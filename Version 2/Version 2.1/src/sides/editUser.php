<?php include "../layout/header.php"; ?>
<?php if ($user->checkUserRights("user")): ?>

    <h1 class="mt-3">User bearbeiten <a href="allUser.php">Zurück</a></h1>
    <?php

    $username = $_POST['username'];
    $right = $_POST['right'];

    if (!empty($username) && !empty($right)) {
        $addUser = $user->editUser($username, $right);
    } else {
        ?>
        <h2>
            Status
        </h2>
        <form method="POST"
              action="editUser.php?username=<?php echo $_GET['username']; ?>&status=<?php if ($_POST["right"]) echo $_POST["right"]; else echo $_GET["status"]; ?>"
              class="m-3">
            </div>

            <div class="form-check">
                <input class="form-check-input" type="radio" name="right" id="right1"
                       value="1" <?php if ($_GET['status'] == "1") echo "checked"; ?>>
                <label class="form-check-label" for="right1">
                    Superuser (alle Rechte)
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="right" id="right2"
                       value="2" <?php if ($_GET['status'] == "2") echo "checked"; ?>>
                <label class="form-check-label" for="right2">
                    Nur Dateien Ansehen (+Herunterladen, Umbenennen, Code editieren)
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="right" id="right3"
                       value="3" <?php if ($_GET['status'] == "3") echo "checked"; ?>>
                <label class="form-check-label" for="right3">
                    Nur Dateien Hochladen (+erstellen)
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="right" id="right4"
                       value="4" <?php if ($_GET['status'] == "4") echo "checked"; ?>>
                <label class="form-check-label" for="right4">
                    Nur Dateien Ansehen und Hochladen
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="right" id="right5"
                       value="5" <?php if ($_GET['status'] == "5") echo "checked"; ?>>
                <label class="form-check-label" for="right5">
                    Nur Dateien Ansehen, Hochladen und Löschen
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="right" id="right6"
                       value="6" <?php if ($_GET['status'] == "6") echo "checked"; ?>>
                <label class="form-check-label" for="right6">
                    Nur Dateien Ansehen, Hochladen, Löschen, User verwalten (erstellen, ändern, löschen, Logs sehen)
                </label>
            </div>

            <button type="submit" class="btn btn-primary mt-2">Edit</button>
        </form>
        <h2>
            <a href="editPasswort.php?username=<?php echo $_GET['username']; ?>">Passwort ändern</a>
        </h2>


        <?php
    }

    if ($addUser): ?>

        <div class="alert alert-success">
            <b>User erfolgreich bearbeitet!</b> Der User wurde mit folgenden Daten erfolgreich erstellt:
            <ul>
                <li>Username: <?php echo $username; ?></li>
                <li>Status: <?php echo $right; ?></li>
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