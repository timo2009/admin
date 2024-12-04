<?php include "../layout/header.php"; ?>
<?php if ($user->checkUserRights("load")): ?>

    <!-- HOCHLADE-FORMULAR-BEREICH -->
    <section id="upload">
        <div class="container">
            <div class="row mt-3">
                <div class="col-12 center">

                    <!-- FORMULAR -->
                    <form
                            method="post"
                            enctype="multipart/form-data"
                    >
                        <label
                                for="formFileSm"
                                class="form-label"
                        >
                            Bitte lade eine
                            Datei hoch(reinziehen oder auf Durchsuchen klicken)
                        </label>
                        <input
                                class="form-control form-control-lg"
                                name="filesToUpload[]"
                                id="file"
                                type="file"
                                multiple
                        />
                        <br>
                        <input
                                type="submit"
                                class="btn
			                    btn-primary
			                "
                                name="submit"
                                value="Upload"
                        />
                    </form>

                </div>
            </div>
            <div class="row">
                <div class="col-12 center">

                    <?php

                    $admin = new AdminClass($user);
                    $data = $_POST['submit'];

                    if (isset($data)) {
                        $uploadedFiles = $admin->loadFileUp($data);
                        $uploadedCount = count($uploadedFiles);
                        ?>

                        <div class="alert alert-primary" role="alert">
                            Hochgeladene Dateien: <?php echo $uploadedCount; ?>
                            Du kannst sie dir <?php echo "<a href='list.php'>hier</a>"; ?> ansehen.
                        </div>

                        <?php
                        foreach ($uploadedFiles as $upload) {
                            $type_info = $upload[5];

                            if ($upload[0] != "false") { ?>

                                <div class="alert alert-primary" role="alert">
                                    <b>Filename: </b> <?php echo $upload[0]; ?>
<!--                                    <br>-->
<!--                                    <b>TMP Name: </b> --><?php //echo $upload[1]; ?>
                                    <br>
                                    <b>Type: </b> <?php echo $upload[2]; ?>
                                    <br>
                                    <b>Size: </b> <?php echo $upload[3] / 1024; ?>kb
                                    <br>
                                    Stored in <?php echo $upload[4]; ?>
                                    <hr>
                                    <?php echo "<a href='list.php?type=" . $type_info[0] . "'>ansehen</a>"; ?>
                                </div>

                            <?php } elseif ($upload) {
                                ?>
                                <div class="alert alert-danger" role="alert">
                                    <b>Achtung</b> Die Datei <?php echo $upload[1]; ?> existiert schon!<br>Du musst diese erste löschen!
                                </div>
                                <?php
                            }
                        }
                    }

                    ?>

                </div>
            </div>
        </div>
    </section>

    <?php include "../layout/footer.php"; ?>
<?php else: ?>
    <div class="alert alert-danger">
        <b>Rechte Fehler!</b> Du hast nicht die Rechte für diesen Teil des Admins
    </div>
<?php endif; ?>