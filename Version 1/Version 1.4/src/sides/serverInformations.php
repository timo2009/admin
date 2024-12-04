<?php include "../layout/header.php"; ?>


	<!-- INFORMATIONS -->
	<div class="container">
		<div class="row">
			<div class="col-12 mt-3">
				<h2 class="center">
					Serverinformationen von <?php echo $_SERVER["SERVER_NAME"]; ?>:
				</h2>
				<ul>
					<li>Servername: <?php echo $_SERVER["SERVER_NAME"]; ?></li>
					<li>Serversoftware: <?php echo $_SERVER["SERVER_SOFTWARE"]; ?></li>
					<li>Serverprotokollart: <?php echo $_SERVER["SERVER_PROTOCOL"]; ?></li>
					<li>Serverport: <?php echo $_SERVER["SERVER_PORT"]; ?></li>
					<li>Sicherheit: <?php if ($_SERVER["HTTPS"]!=Null){echo "HTTPS";} else{echo "HTTP";} ?></li>
				</ul>
			</div>
		</div>	
	</div>

<?php include "../layout/footer.php"; ?>