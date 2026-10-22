<?php include "../layout/header.php"; ?>
<?php if ($user->checkUserRights("user")): ?>

    <?php
    $username = $_POST['username'] ?? $_GET['username'] ?? '';
    $passwort = $_POST['passwort'] ?? '';
    $addUser = false;

    if (!empty($username) && !empty($passwort)) {
        $addUser = $user->resetPassword($username, $passwort);
    }
    ?>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="list-group mb-4">
                    <a href="allUser.php" class="list-group-item list-group-item-action">Benutzer verwalten</a>
                    <a href="addUser.php" class="list-group-item list-group-item-action">Benutzer hinzufügen</a>
                    <a href="editUser.php?username=<?php echo urlencode($username); ?>" class="list-group-item list-group-item-action">Benutzer bearbeiten</a>
                    <a href="editPasswort.php?username=<?php echo urlencode($username); ?>" class="list-group-item list-group-item-action active">Passwort ändern</a>
                    <a href="deleteUser.php?username=<?php echo urlencode($username); ?>" class="list-group-item list-group-item-action text-danger">Benutzer löschen</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <h1 class="mb-4">🔐 Passwort von <code><?php echo htmlspecialchars($username); ?></code> ändern</h1>

                <?php if ($addUser): ?>
                    <div class="alert alert-success">
                        <strong>Passwort erfolgreich geändert!</strong>
                        <br>
                        <a href="allUser.php">Zurück zur Übersicht</a>
                    </div>
                <?php else: ?>
                    <div class="card shadow-sm p-4">
                        <form method="POST" action="editPasswort.php">
                            <div class="form-group mb-3">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                       value="<?php echo htmlspecialchars($username); ?>" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label for="passwort">Neues Passwort</label>
                                <input type="password" class="form-control" id="passwort" name="passwort" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Passwort ändern</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="alert alert-danger m-4">
        <b>Rechte Fehler!</b> Du hast nicht die Rechte für diesen Teil des Admins.
    </div>
<?php endif; ?>
