<?php 
include "../layout/header.php";

$unset=$admin->unsetFile($_GET['file']);
if ($unset):?>

<div class="alert alert-success" role="alert">
	<b>Erfolgreich gelöscht!</b>
</div>

<?php
else:?>

<div class="alert alert-danger" role="alert">
	<b>Die Datei konnte nicht gelöscht werden!</b>
</div>

<?php endif; ?>

<script type="text/javascript">
setTimeout(function() {window.close();}, 500);</script>

<?php include "../layout/footer.php"; ?>