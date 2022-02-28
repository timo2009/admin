<?php include "../layout/header.php"; ?>


	<!-- WELCOME -->
	<div class="container">
		<div class="row">
			<div class="col-1"></div>
			<div class="col-10 mt-3 center">
				<h1>
					Du bist angemeldet als <?php echo $user->username; ?>!
				</h1>
				<h2>
					Herzlich willkommen im Adminbereich deiner Webseite!
				</h2>
			</div>
			<div class="col-1"></div>
		</div>				
		<div class="col-3"></div>
			<div class="col-6 center">
				<a href="help.php" class="btn-type-2">Hilfe</a>
			</div>
			<div class="col-3"></div>
		</div>	
	</div>
<?php include "../layout/footer.php"; ?>