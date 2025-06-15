<?php include "../layout/header.php"; ?>
<?php if ($user->checkUserRights("user")): ?>

    <?php
    $username = $_POST['username'] ?? $_GET['username'] ?? '';
    $deleteUser = false;

    if (!empty($_POST['username'])) {
        $deleteUser = $user->deleteUser($_POST['username']);
    }
    ?>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="list-group mb-4">
                    <a href="allUser.php" class="list-group-item list-group-item-action">👥 Benutzer verwalten</a>
                    <a href="addUser.php" class="list-group-item list-group-item-action">➕ Benutzer hinzufügen</a>
                    <a href="editUser.php?username=<?php echo urlencode($username); ?>" class="list-group-item list-group-item-action">✏️ Benutzer bearbeiten</a>
                    <a href="editPasswort.php?username=<?php echo urlencode($username); ?>" class="list-group-item list-group-item-action">🔒 Passwort ändern</a>
                    <a href="deleteUser.php?username=<?php echo urlencode($username); ?>" class="list-group-item list-group-item-action active">🗑️ Benutzer löschen</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <h1 class="mb-4">🗑️ Benutzer <code><?php echo htmlspecialchars($username); ?></code> löschen</h1>

                <?php if ($deleteUser): ?>
                    <div class="alert alert-success">
                        ✅ <strong>Benutzer erfolgreich gelöscht!</strong>
                        <br>
                        <a href="allUser.php">Zurück zur Übersicht</a>
                    </div>
                <?php else: ?>
                    <div class="card shadow-sm p-4">
                        <form method="POST" action="deleteUser.php" onsubmit="return confirm('Bist du sicher, dass du diesen Benutzer löschen möchtest?')">
                            <div class="form-group mb-3">
                                <label for="username">👤 Benutzername</label>
                                <input type="text" class="form-control" id="username" name="username"
                                       value="<?php echo htmlspecialchars($username); ?>" readonly>
                            </div>
                            <button type="submit" class="btn btn-danger">🗑️ Löschen bestätigen</button>
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
