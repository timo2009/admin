<?php include "../layout/header.php"; ?>
<?php if ($user->checkUserRights("user")): ?>

    <?php
    $username = $_POST['username'] ?? $_GET['username'] ?? '';
    $right = $_POST['right'] ?? '';
    $addUser = false;

    if (!empty($username) && !empty($right)) {
        $addUser = $user->editUser($username, $right);
    }
    ?>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="list-group mb-4">
                    <a href="allUser.php" class="list-group-item list-group-item-action">👥 Benutzer verwalten</a>
                    <a href="addUser.php" class="list-group-item list-group-item-action">➕ Benutzer hinzufügen</a>
                    <a href="editUser.php?username=<?php echo htmlspecialchars($username); ?>&status=<?php echo htmlspecialchars($_GET['status'] ?? $right); ?>" class="list-group-item list-group-item-action active">✏️ Benutzer bearbeiten</a>
                    <a href="editPasswort.php?username=<?php echo urlencode($username); ?>" class="list-group-item list-group-item-action">🔒 Passwort ändern</a>
                    <a href="deleteUser.php?username=<?php echo urlencode($username); ?>" class="list-group-item list-group-item-action text-danger">🗑️ Benutzer löschen</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <h1 class="mb-4">Benutzer <code><?php echo htmlspecialchars($username); ?></code> bearbeiten</h1>

                <?php if ($addUser): ?>
                    <div class="alert alert-success">
                        ✅ <strong>Benutzer erfolgreich bearbeitet!</strong>
                        <ul>
                            <li><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></li>
                            <li><strong>Neuer Status:</strong> <?php echo htmlspecialchars($right); ?></li>
                        </ul>
                        <a href="allUser.php">Zurück zur Übersicht</a>
                    </div>
                <?php else: ?>

                    <div class="card shadow-sm p-4">
                        <form method="POST" action="editUser.php?username=<?php echo urlencode($username); ?>&status=<?php echo htmlspecialchars($_GET['status'] ?? $right); ?>">
                            <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">

                            <label class="form-label mb-2">🛡️ Rechte auswählen:</label>
                            <?php
                            $status = $_GET['status'] ?? '1';
                            $rights = [
                                1 => 'Superuser (alle Rechte)',
                                2 => 'Nur Dateien Ansehen (+Herunterladen, Umbenennen, Code editieren)',
                                3 => 'Nur Dateien Hochladen (+erstellen)',
                                4 => 'Nur Dateien Ansehen und Hochladen',
                                5 => 'Nur Dateien Ansehen, Hochladen und Löschen',
                                6 => 'Nur Dateien Ansehen, Hochladen, Löschen, User verwalten (erstellen, ändern, löschen, Logs sehen)'
                            ];

                            foreach ($rights as $value => $label): ?>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="radio" name="right" id="right<?php echo $value; ?>"
                                           value="<?php echo $value; ?>" <?php if ($status == $value) echo 'checked'; ?>>
                                    <label class="form-check-label" for="right<?php echo $value; ?>">
                                        <?php echo $value . ': ' . $label; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>

                            <button type="submit" class="btn btn-primary mt-3">✅ Änderungen speichern</button>
                        </form>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="alert alert-danger m-4">
        <b>Rechte Fehler!</b> Du hast nicht die Rechte für diesen Teil des Admins
    </div>
<?php endif; ?>
