<?php 

include "../../class/.htUserClass.php";

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
}
$username=$_POST['username'];
$passwort=$_POST['passwort'];
$anzeige=false;
if (isset($username)&&isset($passwort)) {
	$addUser=$user->addFirstUser($username, $passwort);
	if ($addUser) {
		echo "
		<script src='../systhem/main.js'></script>
			<script>\n
				alert('Du musst dich nochmal einloggen')\n
				setCookie('login', false, -1)\n
				location.href='login.php'\n
			</script>\n
			";
		exit;
	}
	else {
		$anzeige=true;
	}
}

if ($user->username=="inizialUser" || $anzeige): ?>
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
	<title>Admin von <?php echo $_SERVER["SERVER_NAME"]; ?></title>
</head>
<body>
	<h1 class="mt-3">Bitte erstelle deinen eigenen User</h1>
<form method="POST" action="addNewUser.php" class="form-horizontal">
  <div class="form-group">
    <label for="username">Username*</label>
    <input type="form-text" class="form-control" id="username" placeholder="Username"
    name="username">
  </div>			  
  <div class="form-group">
    <label for="exampleInputPassword1">Passwort*</label>
    <input type="password" class="form-control" id="passwort" placeholder="Passwort" aria-describedby="passwordHelp"
    name="passwort">
    <small id="passwordHelp" class="form-text text-muted">Bitte nutze ein sicheres Passwort!</small>
  </div>
  <hr>
  <button type="submit" class="btn btn-outline-info">Erstellen</button>
</form>
</body>
</html>
<?php 
else: ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ERROR</title>
</head>
<body>
<b>ERROR: </b>Seite nicht mehr für dich verfügbar!
</body>
</html>

<?php
endif; 
?>