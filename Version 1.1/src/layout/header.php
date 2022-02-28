<?php 
include "../../class/.htUserClass.php";
include "../../class/.htAdminClass.php";

$user=new UserClass();
$cookie=$user->getAndCheckCookie($_COOKIE['login']);
if (!$cookie) {
	echo "
		<script>
			location.href='login.php'
		</script>
		";
	exit;
}
else {
	$user->_setUsernameAndPasswort($cookie[0], $cookie[1], true);
	$admin=new AdminClass();
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<!-- JavaScript Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<script defer>document.addEventListener("touchstart", function(){}, true);</script>
	<script src="../systhem/main.js"></script>
	<link rel="stylesheet" type="text/css" href="../systhem/styles.css">
	<!-- Vue.js -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
	<title>Admin</title>
</head>
<body>
	<!-- NAVBAR --->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	  <div class="container-fluid">
	    <a class="navbar-brand" href="index.php">Startseite</a>
	    <button class="nav-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
