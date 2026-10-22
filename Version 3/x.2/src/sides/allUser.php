<?php include "../layout/header.php"; ?>

<?php if ($user->checkUserRights("user")): ?>
    <?php

    $user = new UserClass();

    $usersJson = json_encode($user->findAllUsers());
    $actions = $user->getAction();
    $limitedActions = array_slice(array_reverse($actions), 0, 100);
    $logs = json_encode($limitedActions);

    if ($_POST['clearCache'] === "true") {
        $user->clearAction();
        ?>
        <div class="alert alert-success">
            <b>Cache erfolgreich geleert!</b> Der Cache wurde erfolgreich zurückgesetzt!
            <br>
            <a href="allUser.php">zurück</a>
        </div>
        <?php
        exit;
    }
    ?>

    <div class="container-fluid mt-4" id="app">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="list-group mb-4">
                    <a href="allUser.php" class="list-group-item list-group-item-action active">Benutzer verwalten</a>
                    <a href="addUser.php" class="list-group-item list-group-item-action">Benutzer hinzufügen</a>
                    <a href="download.php?pfad=true&log=true" class="list-group-item list-group-item-action">Logs herunterladen</a>
                </div>

                <form method="post" action="allUser.php" onsubmit="return doubleConfirm()">
                    <input type="hidden" name="clearCache" value="true">
                    <button type="submit" class="btn btn-danger w-100">Cache leeren</button>
                </form>
            </div>

            <!-- Content -->
            <div class="col-md-9">
                <h1 class="mb-4">Benutzerliste</h1>
                <ul class="list-group mb-4">
                    <li class="list-group-item active">Benutzername – Passwort – Rechte</li>
                    <li v-for="user in users" class="list-group-item d-flex justify-content-between align-items-center">
                        {{ user.username }} – ********** – {{ user.right }}
                        <div>
                            <a :href="'editUser.php?username='+user.username+'&status='+user.right" class="btn btn-sm btn-primary me-2">Bearbeiten</a>
                            <a :href="'deleteUser.php?username='+user.username" class="btn btn-sm btn-danger">Löschen</a>
                        </div>
                    </li>
                </ul>

                <div class="mb-5">
                    <h2>📋 Rechte Übersicht</h2>
                    <ul class="list-group">
                        <li class="list-group-item">1: Superuser (alle Rechte)</li>
                        <li class="list-group-item">2: Nur Dateien ansehen</li>
                        <li class="list-group-item">3: Nur Dateien hochladen</li>
                        <li class="list-group-item">4: Ansehen & Hochladen</li>
                        <li class="list-group-item">5: Ansehen, Hochladen & Löschen</li>
                        <li class="list-group-item">6: Vollzugriff inkl. User-Verwaltung</li>
                    </ul>
                </div>

                <h2 class="mb-3">Letzte Aktionen</h2>
                <table class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th @click="sortBy('date')" style="cursor:pointer">Datum</th>
                        <th @click="sortBy('name')" style="cursor:pointer">Benutzer</th>
                        <th @click="sortBy('action')" style="cursor:pointer">Aktion</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="log in sortedLogs">
                        <td>{{ log.split(";")[0] }}</td>
                        <td>{{ log.split(";")[1] }}</td>
                        <td>{{ log.split(";")[2] }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function doubleConfirm() {
            return confirm("Bist du sicher, dass du den Cache leeren möchtest?");
        }

        var app = new Vue({
            el: '#app',
            data: {
                users: <?php echo $usersJson; ?>,
                logs: <?php echo $logs; ?>,
                currentSort: 'date',
                currentSortDir: 'desc'
            },
            computed: {
                sortedLogs() {
                    return this.logs.slice().sort((a, b) => {
                        let aVal = a.split(";")[this.getSortIndex()];
                        let bVal = b.split(";")[this.getSortIndex()];
                        if (this.currentSortDir === 'asc') {
                            return aVal.localeCompare(bVal);
                        } else {
                            return bVal.localeCompare(aVal);
                        }
                    });
                }
            },
            methods: {
                getSortIndex() {
                    switch (this.currentSort) {
                        case 'date': return 0;
                        case 'name': return 1;
                        case 'action': return 2;
                    }
                },
                sortBy(column) {
                    if (this.currentSort === column) {
                        this.currentSortDir = this.currentSortDir === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.currentSort = column;
                        this.currentSortDir = 'asc';
                    }
                }
            }
        });
    </script>

<?php else: ?>
    <div class="alert alert-danger">
        <b>Rechte Fehler!</b> Du hast nicht die Rechte für diesen Teil des Admins
    </div>
<?php endif; ?>
