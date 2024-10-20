<?php include "../layout/header.php"; ?>
<?php 
$oldFilename=$_GET['name'];
$newFilename=$_POST['new'];

if ($oldFilename!="" && $newFilename!="") {
	$nF=explode("/", $oldFilename);
	array_pop($nF);
	$nF= implode('/', $nF) . "/" .$newFilename;
	$rename=$admin->renameFile($oldFilename, $nF);
	if (!$rename) {
		?>
		Die Datei konnte nicht umbenannt werden, bitte wähle einen anderen neuen Namen.
		<?php
	} else {
		?>
		<script type="text/javascript">
			alert("Erfolgreich umbenannt.");
			location.href="list.php?type=<?php echo $_GET['type'];?>";
		</script>
		<?php
	}
}

?>
	<div class="container">
		<div class="row">
			<div class="col-12">
				<h1>Datei "<?php echo $admin->returnFilename($_GET['name']);?>" Umbenennen</h1>
			</div>
		</div>	
		<div class="row">
			<div class="col-12">
				<form method="POST" action="rename.php?name=<?php echo $_GET['name'];?>&&type=<?php echo $_GET['type'];?>" class="form-outline">
					<div class="form-group">
						<label for="old">Alter Name</label>
						<input type="text" class="form-control" id="old" value="<?php echo $admin->returnFilename($_GET['name']);?>" readonly name="old">
					</div>

					<div class="form-group">
						<label for="new">Neuer Name</label>
						<input type="text" class="form-control" id="new" required value="<?php echo $admin->returnFilename($_GET['name']);?>" name="new">
					</div>

					<input type="submit" class="btn btn-outline-info mt-2" value="Umbenennen">
				</form>
			</div>
		</div>
	</div>
 <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>