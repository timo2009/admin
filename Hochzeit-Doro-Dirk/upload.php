<?php
include "./class/UploadClass.php";

$upload=new UploadClass(); 
$data=$_POST['submit'];

if (isset($data)) {
	$result=$upload->loadFileUp($data);
}
?>




<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="./styles.css">
	<title>Hochladen</title>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-6">
			<div class="intro-container">
				<h1>Lade hier dein Foto hoch:</h1>
				<p id="info" style="display: none;">Die Datei wird derzeit übertragen.<br> Das kann etwas dauern.<br> Bitte lade die Seite nicht neu!</p>
				<form method="POST" action="upload.php" enctype="multipart/form-data" onsubmit="return submit();" id="form_js">
					<label for="file">
						Bitte wähle eine Datei aus
					</label>
					<input type="file" name="file" id="file">
					<input type="submit" name="submit" value="Hochladen">
				</form>
<?php

if (is_array($result)) {
	echo "Das Fote wurde erfolgreich hochgeladen!";
}

elseif($result) {
	echo "Du musst ein Bild hochladen!";
}

?>
			</div>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
	function submit() {
		document.getElementById('info').style="display: block;";
	}
	window.onload=function() {
 
   document.getElementById('form_js').onsubmit=function() { 
      if (document.getElementById('files').value == '') {
         alert('Bitte füllen Sie das Feld aus.');
         return false;  
      } else {
      	submit();
        return true;     
      }  
   } 
 
}
</script>
</html>