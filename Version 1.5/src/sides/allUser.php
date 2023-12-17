<?php include "../layout/header.php"; ?>

<?php if ($user->checkUserRights("user")): ?>
<?php 

$user=new UserClass();

$usersJson=json_encode($user->findAllUsers()); 

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
	</div>
	 
	 </div>
	<script>
	    var app = new Vue({
	        el: '#app',
	        data: {
	            users: <? echo $usersJson;?>
	        },
	 
	    })
	</script>
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
			6: Nur Dateien Ansehen, Hochladen, Löschen, User verwalten (erstellen, ändern, löschen)
		</li>
	</ul>
</div>
<?php else: ?>
<div class="alert alert-danger">
	<b>Rechte Fehler!</b> Du hast nicht die Rechte für diesen Teil des Admins
</div>
<?php endif; ?>