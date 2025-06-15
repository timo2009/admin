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

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap Font Icon CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<!-- JavaScript Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	
	<script defer>document.addEventListener("touchstart", function(){}, true);</script>
	<script src="../systhem/main.js"></script>
	<link rel="stylesheet" type="text/css" href="../systhem/styles.css">
	<!-- Vue.js -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <!-- CodeMirror 6 via CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/codemirror@6/theme/material-darker.css" />
    <script src="https://cdn.jsdelivr.net/npm/@codemirror/basic-setup@0.19.2/dist/index.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@codemirror/view@0.19.45/dist/index.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@codemirror/state@0.19.15/dist/index.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@codemirror/lang-html@0.19.8/dist/index.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@codemirror/commands@0.19.9/dist/index.min.js"></script>

    <title>MAI von <?php echo $_SERVER["SERVER_NAME"]; ?></title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="index.php">🌐 MAI</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if ($user->checkUserRights("load")): ?>
                    <li class="nav-item"><a class="nav-link" href="form.php">📤 Upload</a></li>
                    <li class="nav-item"><a class="nav-link" href="add.php">📝 Create</a></li>
                <?php endif; ?>

                <?php if ($user->checkUserRights("read")): ?>
                    <li class="nav-item"><a class="nav-link" href="list.php">☁️ Cloud</a></li>
                    <li class="nav-item"><a class="nav-link" href="pages.php">📄 Pages</a></li>
                <?php endif; ?>

                <?php if ($user->checkUserRights("user")): ?>
                    <li class="nav-item"><a class="nav-link" href="allUser.php">👤 Users</a></li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="systemDropdown" data-bs-toggle="dropdown">
                        ⚙️ System
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="help.php">Help</a></li>
                        <li><a class="dropdown-item" href="serverInformations.php">Server Info</a></li>
                        <li><a class="dropdown-item" href="/"><?php echo $_SERVER["SERVER_NAME"]; ?></a></li>
                    </ul>
                </li>
                <li class="nav-item ms-2">
                    <button onclick="logout()" class="btn btn-outline-light">Logout</button>
                </li>
            </ul>
        </div>
    </div>
</nav>

