<?php
include "../layout/header.php";
?>

<?php if ($user->checkUserRights("delete")): ?>

    <?php

// Funktion zum Löschen leerer Ordner
    function deleteFolderIfEmpty($folderPath) {
        $basePath = __DIR__ . '/../pages/'; // ggf. anpassen
        $fullPath = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim($folderPath, DIRECTORY_SEPARATOR);

        if (!is_dir($fullPath)) {
            return ['success' => false, 'message' => "Das Verzeichnis '$folderPath' existiert nicht."];
        }

        $files = array_diff(scandir($fullPath), ['.', '..']);

        if (count($files) > 0) {
            return ['success' => false, 'message' => "Das Verzeichnis '$folderPath' ist nicht leer."];
        }

        if (rmdir($fullPath)) {
            return ['success' => true, 'message' => "Das Verzeichnis '$folderPath' war leer und wurde gelöscht."];
        } else {
            return ['success' => false, 'message' => "Das Verzeichnis '$folderPath' konnte nicht gelöscht werden."];
        }
    }

    if (isset($_GET['folder'])) {
        $result = deleteFolderIfEmpty($_GET['folder']);
        if ($result['success']): ?>
            <div class="alert alert-success" role="alert">
                <b><?php echo htmlspecialchars($result['message']); ?></b>
            </div>
        <?php else: ?>
            <div class="alert alert-danger" role="alert">
                <b><?php echo htmlspecialchars($result['message']); ?></b>
            </div>
        <?php endif;

    } elseif (isset($_GET['folder'])) {
        $result = deleteFolderIfEmpty($_GET['folder']);
        if ($result['success']): ?>
            <div class="alert alert-success" role="alert">
                <b><?php echo htmlspecialchars($result['message']); ?></b>
            </div>
        <?php else: ?>
            <div class="alert alert-danger" role="alert">
                <b><?php echo htmlspecialchars($result['message']); ?></b>
            </div>
        <?php endif;

    }if (isset($_GET['group'])) {
        $result = $admin->deleteGroup($_GET['group']);
        if ($result): ?>
            <div class="alert alert-success" role="alert">
                <b>Gruppe <?php echo ucfirst($_GET['group']); ?> wurde erfolgreich gelöscht.</b>
            </div>
        <?php else: ?>
            <div class="alert alert-danger" role="alert">
                <b>Gruppe existiert nicht.</b>
            </div>
        <?php endif;

    } elseif (isset($_GET['file'])) {
        $unset = $admin->unsetFile($_GET['file']);
        if ($unset): ?>
            <div class="alert alert-success" role="alert">
                <b>Erfolgreich gelöscht!</b>
            </div>
        <?php else: ?>
            <div class="alert alert-danger" role="alert">
                <b>Die Datei konnte nicht gelöscht werden!</b>
            </div>
        <?php endif;

    } else { ?>
        <div class="alert alert-danger" role="alert">
            <b>Keine Datei oder Ordner zum Löschen angegeben!</b>
        </div>
    <?php } ?>

    <script type="text/javascript">
        setTimeout(function() {window.close();}, 500);
    </script>

    <?php include "../layout/footer.php"; ?>
<?php else: ?>
    <div class="alert alert-danger">
        <b>Rechte Fehler!</b> Du hast nicht die Rechte für diesen Teil des Admins
    </div>
<?php endif; ?>
