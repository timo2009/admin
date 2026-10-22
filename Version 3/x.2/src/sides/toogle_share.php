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
    $admin=new AdminClass($user);
}

if (isset($_GET['pfad'])) {
    $pfad = $_GET['pfad'];
    $admin->changeShareMode($pfad);

    // Back-URL aus GET oder Standard auf list.php
    $back = isset($_GET['back']) ? $_GET['back'] : 'list.php?type=all';

    header("Location: show.php?file=".$pfad. "&back=" . $back);
    exit;
}
