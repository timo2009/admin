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

session_start();

if (!$user->checkUserRights("load")) {
    echo json_encode(['error' => 'Keine Rechte']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $admin = new AdminClass($user);
    $uploadedFiles = $admin->loadFileUp($_POST['submit']);
    $uploadedCount = count($uploadedFiles);

    ob_start(); // Output Buffer starten
    ?>

    <div class="alert alert-primary" role="alert">
        Hochgeladene Dateien: <?php echo $uploadedCount; ?>
        Du kannst sie dir <a href='list.php'>hier</a> ansehen.
    </div>

    <?php
    foreach ($uploadedFiles as $upload) {
        $type_info = $upload[5];

        if ($upload[0] != "false") { ?>

            <div class="alert alert-primary" role="alert">
                <b>Filename: </b> <?php echo $upload[0]; ?><br>
                <b>Type: </b> <?php echo $upload[2]; ?><br>
                <b>Size: </b> <?php echo $upload[3] / 1024; ?>kb<br>
                Stored in <?php echo $upload[4]; ?><hr>
                <a href='list.php?type=<?php echo $type_info[0]; ?>'>ansehen</a>
            </div>

        <?php } elseif ($upload) { ?>
            <div class="alert alert-danger" role="alert">
                <b>Achtung</b> Die Datei <?php echo $upload[1]; ?> existiert schon!<br>Du musst diese erste löschen!
            </div>
        <?php }
    }

    $htmlOutput = ob_get_clean();
    echo $htmlOutput;
    exit;
}
?>
