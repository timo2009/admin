<?php include "../layout/header.php";
if ($user->checkUserRights("load")): ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Neue Gruppe erstellen</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-12">


                <form method="POST" action="addGroup.php" class="form-outline">

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Gruppennamen"
                               title="Bitte gebe den Namen an." name="name">
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
    if (!empty($name)) {
        $addFile = $admin->createGroup($name);
        if ($addFile): ?>

            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success" role="alert">
                            <b>Die Gruppe wurde erfolgreich erstellt.</b>
                            <br>
                            Du kannst sie dir <a href="pages.php?type=<?php echo $_GET['name']; ?>">hier</a> ansehen.
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
                            <b>Die Gruppe konnte nicht erstellt werden.</b>
                            <br>
                            Die Gruppe existiert schon
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