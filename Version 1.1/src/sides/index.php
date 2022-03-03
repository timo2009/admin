<?php include "../layout/header.php"; ?>


	<!-- WELCOME -->
	<div class="container">
		<div class="row">
			<div class="col-12 mt-3 center">
				<h1>
					Du bist angemeldet als 
<?php echo $user->username; ?>
					!
				</h1>
				<h2>
					Herzlich willkommen im Adminbereich deiner Webseite!
				</h2>
			</div>
		</div>				
			<div class="col-12 center">
				<a 
					href="form.php" 
					class="btn-type-2"
				>
					Dateien Hochladen
				</a>
				<a 
					href="add.php" 
					class="btn-type-2"
				>
					Neue Datei erstellen
				</a>
				<br>				
				<a 
					href="list.php" 
					class="btn-type-2"
				>
					Dateien Anzeigen
				</a>
				<a 
					href="/" 
					class="btn-type-2"
				>
					Exit Admin
				</a>
				<br>
				<button 
					onclick="logout()"
					class="btn-type-2"
				>
					Logout
				</button>
				<a 
					href="help.php" 
					class="btn-type-2"
				>
					Hilfe
				</a>
			</div>
		</div>	
	</div>

<?php include "../layout/footer.php"; ?>