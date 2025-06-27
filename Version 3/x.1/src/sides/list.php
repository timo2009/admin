<?php

include "../layout/header.php";

$files = $admin->listFiles($_GET['type']);
$fileJson = json_encode($files);

if ($_GET['type'] == "") {
    echo "<script>\nlocation.href='list.php?type=all'\n</script>\n";
}

?>
<?php if ($user->checkUserRights("read")): ?>

    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <a href="?type=files" class="nav-link <?php echo $admin->returnActiveIfTypeAreGet("files", $_GET['type']); ?>"
           aria-current="page">
            <span class="ms-2">Dateien</span>
        </a>
        <a href="?type=audio" class="nav-link <?php echo $admin->returnActiveIfTypeAreGet("audio", $_GET['type']); ?>"
           aria-current="page">
            <span class="ms-2">Audios</span>
        </a>
        <a href="?type=image" class="nav-link <?php echo $admin->returnActiveIfTypeAreGet("image", $_GET['type']); ?>"
           aria-current="page">
            <span class="ms-2">Bilder</span>
        </a>
        <a href="?type=video" class="nav-link <?php echo $admin->returnActiveIfTypeAreGet("video", $_GET['type']); ?>"
           aria-current="page">
            <span class="ms-2">Videos</span>
        </a>
        <a href="?type=docs" class="nav-link <?php echo $admin->returnActiveIfTypeAreGet("docs", $_GET['type']); ?>"
           aria-current="page">
            <span class="ms-2">Dokumente (PDF)</span>
        </a>
        <a href="?type=all" class="nav-link <?php echo $admin->returnActiveIfTypeAreGet("all", $_GET['type']); ?>"
           aria-current="page">
            <span class="ms-2">Alle Dateien</span>
        </a>
    </div>


    <button class="openbtn" onclick="openNav()">Datei Art ändern</button>

    <!-- VUE -->
    <div id="app" class="container">
        <table class="table table-bordered" empty-text="asd" empty-filtered-text="asd2">
            <thead>
            <tr>
                <th @click="sortBy='username'; sortAsc = !sortAsc">
                    Name, Pfad und Link
                    <?php if ($user->checkUserRights("load")): ?>
                        <a href="add.php" style="color: green; font-size: 16px; float: right;">+</a>
                    <?php endif; ?>
                </th>
            </tr>
            <tr>
                <th>
                    <input class="form-control" v-model="search.name" placeholder="Name">
                </th>
            </tr>
            <tbody>
            <tr v-for="item in filterByValue">
                <td>
                    {{ item.name }}
                    <?php if ($user->checkUserRights("delete")): ?>
                        <a :href="item.deleteLink" onclick="FensterOeffnen(this.href, true); return false"
                          >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd"
                                      d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                    <a :href="item.renameLink">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                             class="bi bi-pen" viewBox="0 0 16 16">
                            <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
                        </svg>
                    </a>
                    <br>
                    <a :href="item.showLink">
                        {{ item.pfad }}
                    </a>
                </td>

            </tr>

            <?php

            if (empty($files)) {
                echo "Keine Dateien vorhanden!";
            }

            ?>
            </tbody>
        </table>

    </div>

    <script>
        var app = new Vue({
            el: '#app',
            data: {
                search: {
                    name: '',
                },
                sortBy: 'name',
                sortAsc: false,
                files: <? echo $fileJson;?>
            },
            computed: {
                filterByValue: function () {
                    return this.files.filter(function (item) {
                        for (const key in this.search) {
                            const query = this.search[key].trim();
                            if (query.length > 0) {
                                if (!item[key].includes(query)) {
                                    return false;
                                }
                            }
                        }
                        return true;
                    }.bind(this))
                        .slice().sort(function (a, b) {
                                return (this.sortAsc ? 1 : -1) * a[this.sortBy].localeCompare(b[this.sortBy])
                            }.bind(this)
                        );
                }
            }

        })
    </script>

    <?php include "../layout/footer.php"; ?>
<?php else: ?>
    <div class="alert alert-danger">
        <b>Rechte Fehler!</b> Du hast nicht die Rechte für diesen Teil des Admins
    </div>
<?php endif; ?>