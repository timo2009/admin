<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../../class/.htUserClass.php";
include "../../class/.htAdminClass.php";

$user = new UserClass();
$cookie = $user->getAndCheckCookie($_COOKIE['login']);
if (!$cookie) {
    echo "<script>location.href='login.php'</script>";
    exit;
}
$user->_setUsernameAndPasswort($cookie[0], $cookie[1], true);
$admin = new AdminClass($user);

if ($user->checkUserRights("read")):?>
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
<?php

    $pfad = $_GET['pfad'] ?? null;
    $back = $_GET['back'] ?? null;

    // Serverseitiges Speichern (AJAX)
    if (isset($_POST['content']) && $pfad) {
        $change = $admin->editTextFile($pfad, $_POST['content']);
        echo $change ? "success" : "error";
        exit;
    }


    if (!$pfad): ?>
        <div class="container mt-3">
            <div class="alert alert-danger"><b>ERROR:</b> Kein Pfad angegeben!</div>
        </div>
    <?php
    else:
        $currentContent = $admin->readTextFile($pfad);
        $filename = basename($pfad);
        ?>
        <div id="editorContainer" style="position: fixed; top:0; left:0; width:100%; height:100%; z-index:9999;">
            <div id="editor" style="height: calc(100vh - 60px); width:100%;"><?php echo htmlspecialchars($currentContent); ?></div>
        </div>

        <!-- Toolbar unten -->
        <div id="toolbar" style="
            position: fixed; bottom: 0; left: 0; width: 100%; height: 60px;
            background: #222; color: #fff; display: flex; align-items: center;
            justify-content: space-between; padding: 0 20px; font-size: 14px; z-index:10000;">
            <span>Datei: <?php echo $filename; ?></span>
            <div>
                <button id="saveBtn" class="btn btn-info btn-sm">💾 Speichern</button>
                <?php if ($back): ?>
                    <button id="backBtn" class="btn btn-light btn-sm">⬅ Zurück</button>
                <?php endif; ?>
            </div>
            <span id="saveMsg" style="margin-left:20px;color:#0f0;"></span>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.3/ace.js"></script>
        <script>
            const editor = ace.edit("editor");
            editor.session.setMode("ace/mode/php");
            editor.setTheme("ace/theme/monokai");
            editor.setOptions({ fontSize: "14px", tabSize: 4, useSoftTabs: true });

            const saveBtn = document.getElementById("saveBtn");
            const saveMsg = document.getElementById("saveMsg");
            const backBtn = document.getElementById("backBtn");

            function saveContent() {
                const content = editor.getValue();
                fetch("edit.php?pfad=<?php echo $pfad ?>&back=<?php echo $back ?>", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "content=" + encodeURIComponent(content)
                })
                    .then(res => {
                        saveMsg.textContent = "💾 Gespeichert!";
                        setTimeout(() => saveMsg.textContent = "", 2000);
                    })
                    .catch(err => {
                        saveMsg.textContent = "Fehler beim Speichern!";
                        setTimeout(() => saveMsg.textContent = "", 3000);
                    });
            }

            saveBtn.addEventListener("click", saveContent);
            if (backBtn) backBtn.addEventListener("click", () => window.location.href = "<?php echo $back ?>");

            // Strg+S / Cmd+S Shortcut
            document.addEventListener("keydown", e => {
                if ((e.ctrlKey || e.metaKey) && e.key === "s") {
                    e.preventDefault();
                    saveContent();
                }
            });
        </script>

    <?php endif; ?>
    <?php include "../layout/footer.php"; ?>

<?php else: ?>
    <div class="alert alert-danger mt-3">
        <b>Rechte Fehler!</b> Du hast nicht die Rechte für diesen Teil des Admins.
    </div>
<?php endif; ?>
