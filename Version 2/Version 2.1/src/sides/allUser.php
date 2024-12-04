<?php include "../layout/header.php"; ?>

<?php if ($user->checkUserRights("user")): ?>
<?php 

$user=new UserClass();

$usersJson=json_encode($user->findAllUsers());
$actions = $user->getAction();
$limitedActions = array_slice(array_reverse($actions), 0, 100);
$logs = json_encode($limitedActions);

if($_POST['clearCache'] == "true") {
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


<div class="center m-3">
	<h1>User Verwalten 	<a href="addUser.php" style="color: green; font-size: 16px; float: right;">+ Add User</a>
</h1>

	<!-- VUE -->
	<div id="app">
	    <ul>
	    	<li>Username - Passwort - Rechte Nummer</li>
	      <li v-for="user in users">
	        {{ user.username }} - ********** - {{ user.right }}
              <a :href="'editUser.php?username='+user.username+'&status='+user.right">Bearbeiten</a>
              <br>
              <a :href="'deleteUser.php?username='+user.username">Löschen</a>
	      </li>
	    </ul>

        <div class="p-3">
            <h2>Info zu Rechte Nummer</h2>
            <ul>
                <li>
                    1: Superuser (alle Rechte)
                </li>
                <li>
                    2: Nur Dateien ansehen (+Herunterladen, Umbenennen, Code editieren)
                </li>
                <li>
                    3: Nur Dateien Hochladen (+erstellen)
                </li>
                <li>
                    4: Nur Dateien Ansehen und Hochladen
                </li>
                <li>
                    5: Nur Dateien Ansehen, Hochladen und Löschen
                </li>
                <li>
                    6: Nur Dateien Ansehen, Hochladen, Löschen, User verwalten (erstellen, ändern, löschen, Logs sehen)
                </li>
            </ul>
        </div>

        <form method="post" action="allUser.php" onsubmit="return doubleConfirm()">
            <input type="hidden" name="clearCache" value="true">
            <button type="submit" class="btn btn-danger">leeren</button>
        </form>
        <a href="download.php?pfad=true&log=true">
            Logs herunterladen (txt)
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
            </svg>
        </a>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Name</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="log in logs">
                <td>{{ log.split(";")[0] }}</td>
                <td>{{ log.split(";")[1] }}</td>
                <td>{{ log.split(";")[2] }}</td>
            </tr>
            </tbody>
        </table>
	</div>


	 
	 </div>
	<script>
        function doubleConfirm() {
            // Erste Bestätigung
            return confirm("Bist du sicher, dass du den Cache leeren möchtest?");
             // Abbrechen, wenn die erste Bestätigung abgelehnt wird
        }
	    var app = new Vue({
	        el: '#app',
	        data: {
	            users: <? echo $usersJson;?>,
                logs: <? echo $logs;?>,
	        },
            method: {

            }
	 
	    })
	</script>
<?php else: ?>
<div class="alert alert-danger">
	<b>Rechte Fehler!</b> Du hast nicht die Rechte für diesen Teil des Admins
</div>
<?php endif; ?>