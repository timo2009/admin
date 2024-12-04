<?php include "../layout/header.php"; ?>

	<div class="container">
		<div class="row">
			<div class="col-12">
				<h1>Neue Textdatei erstellen</h1>
			</div>
		</div>	
		<div class="row">
			<div class="col-12">
				<form method="POST" action="add.php" class="form-outline">
					<div class="form-group">
						<label for="name">Name (ohne Dateiendung)*</label>
						<input type="text" class="form-control" id="name" placeholder="Name(ohne Endung)" pattern="[a-zA-Z0-9-]+" title="Bitte gebe den Namen an (ohne Dateiendung)" name="name">
					</div>
					<div class="mt-2">Dateiart:</div>
					<div class="form-check mt-1">
					  <input class="form-check-input" type="radio" name="art" id="exampleRadios1" value="html-g" checked>
					  <label class="form-check-label" for="exampleRadios1">
					    HTML Datei(mit HTML Grundgerüst)
					  </label>
					</div>
					<div class="form-check">
					  <input class="form-check-input" type="radio" name="art" id="exampleRadios2" value="html">
					  <label class="form-check-label" for="exampleRadios2">
					    HTML Datei (ohne HTML Grundgerüst)
					  </label>
					</div>				
					<div class="form-check">
					  <input class="form-check-input" type="radio" name="art" id="exampleRadios3" value="txt">
					  <label class="form-check-label" for="exampleRadios3">
					    TXT Datei
					  </label>
					</div>
					<input type="submit" class="btn btn-outline-info mt-2" value="Erstellen">
				</form>
			</div>
		</div>
	</div>

<?php 

$name=$_POST['name'];
$art=$_POST['art'];
if (!empty($name) && isset($art)) {
	$addFile=$admin->addFile($name, $art);
	if ($addFile): ?>

	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="alert alert-success" role="alert">
					<b>Die Datei wurde erfolgreich erstellt.</b>
					<br>
					Du kannst es dir <a href="list.php">hier</a> ansehen und bearbeiten.
				</div>
			</div>
		</div>
	</div>

<?php
else:?>

	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="alert alert-danger" role="alert">
					<b>Die Datei konnte nicht erstellt werden.</b>
					<br>
					Die Datei existiert schon
				</div>
			</div>
		</div>
	</div>

<?php
endif;
}

include "../layout/footer.php";

?>