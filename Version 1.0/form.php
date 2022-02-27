<?php 
include "./class/.htUserClass.php";
include "./class/.htAdminClass.php";

$user=new UserClass();
$cookie=$user->getAndCheckCookie($_COOKIE['login']);
if (!$cookie) {
	header('Location: ./login.php');
}
else {
	$user->_setUsernameAndPasswort($cookie[0], $cookie[1], true);
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="styles.min.css">
	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<!-- JavaScript Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<script defer>document.addEventListener("touchstart", function(){}, true);</script>
	<script type="text/javascript">
		function setCookie(cname,cvalue,exdays) {
			let d = new Date();
			d.setTime(d.getTime() + (exdays*24*60*60*1000));
			let expires = "expires=" + d.toGMTString();
			document.cookie = cname + "=" + cvalue + ";" + expires +
			";path=/;SameSite=Lax";
		}
		function logout() {
			setCookie('login', false, -1);
			location.reload();
		}
	</script>
	<title>Hallo</title>
</head>
<body>
	<!-- NAVBAR --->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	  <div class="container-fluid">
	    <a class="navbar-brand" href="index.php">Startseite</a>
	    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
	      <span class="navbar-toggler-icon"></span>
	    </button>
	    <div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav">
				<li class="nav-item">
	    			<a class="navbar-brand" href="form.php">Hochladen</a>
				</li>					
				<li class="nav-item">
	    			<a class="navbar-brand" href="list.php">Alle Dateien</a>
				</li>				
				<li class="nav-item">
	    			<a class="navbar-brand" href="help.php">Hilfe</a>
				</li>
			</ul>			
			<ul class="navbar-nav ms-auto mt-2">
				<li class="nav-item" style="margin-right: 25%;">
				  <button onclick="logout()" class="btn btn-info">Logout</button>
				</li>
			</ul>
	    </div>
	  </div>
	</nav>
	<!-- HOCHLADE-FORMULAR-BEREICH -->
	<section id="upload">
		<div class="container">
			<div class="row mt-3">
				<div class="col-1"></div>
				<div class="col-10">

					<!-- FORMULAR -->
			        <form 
			            method="post" 
			            enctype="multipart/form-data" 
			        >
			            <label 
			                for="formFileSm" 
			                class="form-label"
			            >
			                Bitte lade eine 
			                Datei hoch(reinziehen oder auf Durchsuchen klicken)
			            </label>
			            <input 
			                class="
			                    form-control 
			                    form-control-lg
			                " 
			                name="file" 
			                id="file" 
			                type="file" 
			            />
			            <br>
			            <input 
			                type="submit" 
			                class="btn
			                    btn-primary
			                " 
			                name="submit" 
			                value="Upload" 
			            />
			        </form>

				</div>
				<div class="col-1"></div>
			</div>
			<div class="row">
				<div class="col-2"></div>
				<div class="col-8">		
<?php 
$admin=new AdminClass();
$data=$_POST['submit'];

if (isset($data)) {
	$upload=$admin->loadFileUp($data);
	$type_info=$upload[5];
	if (is_array($upload)): ?>

<div class="alert alert-primary" role="alert">
	<b>Filename: </b><?php echo $upload[0]; ?>
	<br>	
	<b>TMP Name: </b><?php echo $upload[1]; ?>
	<br>	
	<b>Type: </b><?php echo $upload[2]; ?>
	<br>	
	<b>Size: </b><?php echo $upload[3] / 1024; ?>kb
	<br>
	Stored in <?php echo $upload[4]; ?>
	<hr>
	Du kannst es dir <?php
		echo "<a href='list.php?type=".$type_info[0]."'>hier</a>";
	?> ansehen.
</div>


<?php
else: ?>
<div class="alert alert-danger" role="alert">
	<b>Return Code: <?php echo $upload; ?></b>
</div>
<?php
endif;
}
?>
				</div>
				<div class="col-2"></div>
			</div>
		</div>
	</section>
</body>
</html>