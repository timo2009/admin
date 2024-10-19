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
	<!-- CSS only -->
	<link rel="stylesheet" type="text/css" href="styles.min.css">
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

</body>
</html>