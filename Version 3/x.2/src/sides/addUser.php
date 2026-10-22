<?php include "../layout/header.php"; ?>
<?php if ($user->checkUserRights("user")): ?>

    <?php
    $username = $_POST['username'] ?? '';
    $passwort = $_POST['passwort'] ?? '';
    $right = $_POST['right'] ?? '';
    $addUser = false;

    if (!empty($username) && !empty($passwort) && !empty($right)) {
        $addUser = $user->addUser($username, $passwort, $right);
    }
    ?>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="list-group mb-4">
                    <a href="allUser.php" class="list-group-item list-group-item-action">👥 Benutzer verwalten</a>
                    <a href="addUser.php" class="list-group-item list-group-item-action active">➕ Benutzer hinzufügen</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <h1 class="mb-4">Neuen Benutzer hinzufügen</h1>

                <?php if ($addUser): ?>
                    <div class="alert alert-success">
                        <b>User erfolgreich erstellt!</b> Der User wurde mit folgenden Daten erstellt:
                        <ul>
                            <li><b>Username:</b> <?php echo htmlspecialchars($username); ?></li>
                            <li><b>Passwort:</b> <?php echo htmlspecialchars($passwort); ?></li>
                        </ul>
                        <a href="allUser.php">Zurück zur Übersicht</a>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm p-4">
                    <form method="POST" action="addUser.php">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username*</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Passwort*</label>
                            <input type="password" class="form-control" id="password" name="passwort" required>
                        </div>

                        <label class="form-label mb-2">Rechte auswählen:</label>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="radio" name="right" id="right1" value="1" checked>
                            <label class="form-check-label" for="right1">1: Superuser (alle Rechte)</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="radio" name="right" id="right2" value="2">
                            <label class="form-check-label" for="right2">2: Nur Dateien ansehen</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="radio" name="right" id="right3" value="3">
                            <label class="form-check-label" for="right3">3: Nur Dateien hochladen</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="radio" name="right" id="right4" value="4">
                            <label class="form-check-label" for="right4">4: Ansehen und Hochladen</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="radio" name="right" id="right5" value="5">
                            <label class="form-check-label" for="right5">5: Ansehen, Hochladen und Löschen</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="right" id="right6" value="6">
                            <label class="form-check-label" for="right6">6: Vollzugriff inkl. User-Verwaltung</label>
                        </div>

                        <button type="submit" class="btn btn-success">Benutzer erstellen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="alert alert-danger m-4">
        <b>Rechte Fehler!</b> Du hast nicht die Rechte für diesen Teil des Admin-Bereichs.
    </div>
<?php endif; ?>
