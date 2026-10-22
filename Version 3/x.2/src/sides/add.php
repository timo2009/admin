<?php include "../layout/header.php"; ?>
<?php if ($user->checkUserRights("load")): ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Neue Textdatei erstellen</h1>
            </div>
        </div>

        <?php
        $type = isset($_GET['type']) ? $_GET['type'] : '';
        $currentTab = $_GET['pages'] == "true" ? 'pages_' . $type : 'cloud'; // Default Tab = cloud
        ?>

        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link <?php echo ($currentTab === 'cloud') ? 'active' : ''; ?>"
                   href="?pages=false">Cloud</a>
            </li>
            <?php foreach ($admin->listPagesGroups() as $group): ?>
                <li>
                    <a class="nav-link <?php echo ($currentTab === 'pages_' . $group) ? 'active' : ''; ?>"
                       href="?pages=true&type=<?php echo $group; ?>">Pages <?php echo $group; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="row">
            <div class="col-12">
                <?php if ($_GET['pages'] == "true"): ?>

                <form method="POST" action="add.php?pages=true&type=<?php echo $_GET['type']; ?>" class="form-outline">

                    <?php else: ?>

                    <form method="POST" action="add.php" class="form-outline">

                        <?php endif; ?>
                        <div class="form-group">
                            <label for="name">Name (ohne Dateiendung)*</label>
                            <input type="text" class="form-control" id="name" placeholder="Name(ohne Endung)"
                                   title="Bitte gebe den Namen an (ohne Dateiendung)" name="name">
                        </div>
                        <div class="mt-2">Dateiart:</div>
                        <div class="form-check mt-1">
                            <input class="form-check-input" type="radio" name="art" id="exampleRadios1" value="html-g"
                                   checked>
                            <label class="form-check-label" for="exampleRadios1">
                                HTML Datei(mit HTML Grundgerüst)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="art" id="exampleRadios2" value="html">
                            <label class="form-check-label" for="exampleRadios2">
                                HTML Datei (ohne HTML Grundgerüst)
                            </label>
                        </div>
                        <?php if ($_GET['pages'] == "true"): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="art" id="exampleRadiosphp"
                                       value="php">
                                <label class="form-check-label" for="exampleRadiosphp">
                                    PHP Datei
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="art" id="exampleRadiosc"
                                       value="custom">
                                <label class="form-check-label" for="exampleRadiosc">
                                    Custom Datei
                                </label>
                            </div>
                        <?php endif; ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="art" id="exampleRadios3" value="txt">
                            <label class="form-check-label" for="exampleRadios3">
                                TXT Datei
                            </label>
                        </div>
                        <input type="submit" class="btn btn-outline-info mt-2" value="Erstellen">
                    </form>
            </div>
        </div>
    </div>

    <?php

    $name = $_POST['name'];
    if (preg_match("/\./", $name) && $_POST['art'] != "custom") {
        print("<br>Keine Dateiendung angeben!");
        die();
    }
    $art = $_POST['art'];
    if (!empty($name) && isset($art)) {
        if ($_GET['pages'] == "true") {
            $addFile = $admin->addFile($name, $art, $_GET['type']);
        } else {
            $addFile = $admin->addFile($name, $art);
        }
        if ($addFile): ?>

            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success" role="alert">
                            <b>Die Datei wurde erfolgreich erstellt.</b>
                            <br>
                            <?php if ($_GET['pages'] == "true"): ?>
                                Du kannst es dir <a
                                        href="pages.php?type=<?php echo $_GET['type']; ?>">hier</a> ansehen und bearbeiten.
                            <?php else: ?>
                                Du kannst es dir <a href="list.php">hier</a> ansehen und bearbeiten.
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php
        else:?>

            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-danger" role="alert">
                            <b>Die Datei konnte nicht erstellt werden.</b>
                            <br>
                            Die Datei existiert schon
                        </div>
                    </div>
                </div>
            </div>

        <?php
        endif;
    }

    include "../layout/footer.php";

    ?>
<?php else: ?>
    <div class="alert alert-danger">
        <b>Rechte Fehler!</b> Du hast nicht die Rechte für diesen Teil des Admins
    </div>
<?php endif; ?>