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
$admin=new AdminClass();

?>
<!DOCTYPE html>
<html>
<head>
	<script defer>document.addEventListener("touchstart", function(){}, true);</script>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<!-- JavaScript Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

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
		function openNav() {
		  document.getElementById("mySidebar").style.width = "250px";
		  document.getElementById("main").style.marginLeft = "250px";
		}

		function closeNav() {
		  document.getElementById("mySidebar").style.width = "0";
		  document.getElementById("main").style.marginLeft= "0";
		}
	</script>
	<!-- Vue.js -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <link rel="stylesheet" type="text/css" href="styles.css">
	<title>Löschen</title>
    <!-- Our Custom CSS -->
	<link rel="stylesheet" type="text/css" href="styles.min.css">

</head>

<body>

<?php 
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
setTimeout(function() {window.close();}, 2500);</script>
</body>
</html>