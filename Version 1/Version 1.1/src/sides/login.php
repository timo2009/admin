<?php include "../../class/.htUserClass.php" ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<!-- JavaScript Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
		<link rel="stylesheet" type="text/css" href="styles.min.css">

	<script>
		function setCookie(cname,cvalue,exdays) {
			let d = new Date();
			d.setTime(d.getTime() + (exdays*24*60*60*1000));
			let expires = "expires=" + d.toGMTString();
			document.cookie = cname + "=" + cvalue + ";" + expires +
			";path=/;SameSite=Lax";
		}
	</script>
	<script defer>document.addEventListener("touchstart", function(){}, true);</script>
	<title>Login</title>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-3"></div>
		<div class="col-6 mt-3 center">
			<h1>
				Bitte logge dich ein:
			</h1>
		</div>
		<div class="col-3"></div>
	</div>
	<div class="row">
		<div class="col-2"></div>
		<div class="col-8 center">
			<form action="login.php" method="post">
				<div class="form-group">
					<label for="exampleInputEmail1">
						Username*
					</label>
					<input 
						type="text"
						class="form-control" 
						id="exampleInputEmail1" 
						placeholder="Username" 
						name="username"
						required
					>
				</div>  
				<div class="form-group">
					<label for="exampleInputEmail1">
						Passwort*
					</label>
					<input 
						type="password" 
						class="form-control" 
						id="exampleInputEmail1" 
						name="passwort" 
						placeholder="Passwort"
						required
					>
				</div>
				<button 
					type="submit" 
					class="mt-3 btn btn-outline-primary"
				>
					Login
				</button>
			</form>
		</div>
		<div class="col-2"></div>
	</div>
	<div class="row">
		<div class="col-3"></div>
		<div class="col-6 center">
			

<?php
// defind the class
$user=new UserClass();

// get the username and Passwort
$username=$_POST['username'];
$passwort=$_POST['passwort'];

// check the cookie
$cookie=$user->getAndCheckCookie($_COOKIE['login']);

// if the userername and the passwort are full set them in the class
if ($username!="" && $passwort!="") {
	$user->_setUsernameAndPasswort($username, $passwort);
	// safe the login the the login variable
	$login=$user->login();
	if ($login) {
		// if the login variable are true set the cookie
		$setCookieValue=$user->addCookie($username);
		echo "
		<script>
			setCookie('login', '";
		echo $setCookieValue;
		echo "', 2)
		location.href='index.php'
		</script>
		";
		exit;
	}
	else {
		echo "Deine Logindaten sind falsch";
	}
}

elseif ($cookie) {
	$username=$cookie[0];
	$passwort=$cookie[1];
	$user->_setUsernameAndPasswort($username, $passwort, true);
	echo "
		<script>
			location.href='index.php'
		</script>
	";
}

?>

		</div>
		<div class="col-3"></div>
	</div>
</div>
</body>
</html>
