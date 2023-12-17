<?php include "../layout/header.php"; ?>


	<!-- WELCOME -->
	<div class="container">
		<div class="row">
			<div class="col-12 mt-3 center">
				<h1>
					Du bist angemeldet als <?php echo $user->username; ?>!
				</h1>
				<h2>
					Herzlich willkommen im Adminbereich von <?php echo $_SERVER["SERVER_NAME"]; ?>!
				</h2>
			</div>
		</div>				
			<div class="col-12 center">
			<?php if ($user->checkUserRights("load")): ?>
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
			<?php endif; ?>
				<br>
			<?php if ($user->checkUserRights("read")): ?>		
				<a 
					href="list.php" 
					class="btn-type-2"
				>
					Dateien Anzeigen
				</a>
			<?php endif; ?>
				<a 
					href="/" 
					class="btn-type-2"
				>
					<?php echo $_SERVER["SERVER_NAME"]; ?>
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
				<br>
				<?php if ($user->checkUserRights("user")): ?>
				<a 
					href="allUser.php" 
					class="btn-type-2"
				>
					User verwalten
				</a>
				<?php endif; ?>
			</div>
		</div>	
	</div>

<?php include "../layout/footer.php"; ?>