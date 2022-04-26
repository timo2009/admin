<?php 

include "../layout/header.php";
$pfad=$_GET['pfad'];
$content=$_POST['content'];
$back=$_GET['back'];
if (isset($pfad) && !isset($content)) {
	$oldContent=$admin->readTextFile($pfad);

?>

	<div class="container">
		<div class="row">
			<div class="col-12">
				<a href="<?php echo $back; ?>">Zurück</a>
				<form method="POST" action="edit.php?pfad=<?php echo $pfad ?>&&back=<?php echo $back ?>" class="form-outline mt-2">
					 <textarea class="form-control" id="textAreaExample1" rows="15" name="content"><?php echo $oldContent; ?></textarea>
					 <button type="submit" class="btn btn-info m-3">Change</button>
				</form>
			</div>
		</div>
	</div>

<?php

}
elseif (!isset($content)){ ?>

	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="alert alert-danger" role="alert">
					<b>ERROR: </b> Kein Pfad angegeben!
				</div>
			</div>
		</div>
	</div>

<?php 

}  

if (isset($content) && isset($pfad)) {
	$change=$admin->editTextFile($pfad, $content);
	$newContent=$admin->readTextFile($pfad);
	if ($change): ?>

	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="alert alert-success" role="alert">
					Erfolgreich geändert.
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-12">
				<a href="<?php echo $back; ?>">Zurück</a>
				<form method="POST" action="edit.php?pfad=<?php echo $pfad ?>&&back=<?php echo $back ?>" class="form-outline mt-2">
					 <textarea class="form-control" id="textAreaExample1" rows="15" name="content"><?php echo $newContent; ?></textarea>
					 <button type="submit" class="btn btn-info m-3">Change</button>
				</form>
			</div>
		</div>
	</div>

<?php
endif;
}

include "../layout/footer.php"; 

?>