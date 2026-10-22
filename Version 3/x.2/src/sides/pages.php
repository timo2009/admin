<?php
include "../layout/header.php";

$groups = $admin->listPagesGroups();
$chooseGroup = $_GET["type"] ?? null;

// Dateien laden
$files = [];
if ($chooseGroup) {
    $files = $admin->listFilesInGroups($chooseGroup);
    $files = array_values($files);
}
$fileJson = json_encode(array_map(function ($file) use ($chooseGroup, $admin) {
    return [
        'name' => $file,
        'pfad' => 'show.php?file=' . $admin->returnGroupPath($chooseGroup) . $file . '&type=' . $chooseGroup . '&pages=True',
        'deleteLink' => 'unset.php?file=' . $admin->returnGroupPath($chooseGroup) . $file,
        'renameLink' => 'rename.php?name=' . $admin->returnGroupPath($chooseGroup) . $file . '&type=' . $chooseGroup . '&pages=True'
    ];
}, $files));

// Struktur bauen
$structure = $chooseGroup ? $admin->groupStructure($chooseGroup) : [];

function buildTree($structure, $path = '')
{
    $result = [];
    foreach ($structure as $key => $value) {
        $fullPath = ltrim($path . '/' . $key, '/');
        if (is_array($value)) {
            if (empty($value)) {
                $result[] = [
                    'name' => $key,
                    'full' => $fullPath,
                    'type' => 'folder-empty',
                    'deleteLink' => 'unset.php?folder=' . urlencode($fullPath) . '&type=' . urlencode($_GET['type']),
                    'renameLink' => 'rename.php?folder=' . urlencode($fullPath) . '&type=' . urlencode($_GET['type'])
                ];
            } else {
                $result[] = [
                    'name' => $key,
                    'full' => $fullPath,
                    'type' => 'folder',
                    'children' => buildTree($value, $fullPath)
                ];
            }
        } else {
            $result[] = [
                'name' => $value,
                'full' => $fullPath,
                'type' => 'file'
            ];
        }
    }
    return $result;
}

$structureTree = buildTree($structure);

$message = null;

if ($_POST['action']) {
    $action = $_POST['action'];
    if ($action == "addFolder") {
        $name = $_POST['name'];
        $group = $_POST['group'];
        if ($admin->createFolderInGroup($group, $name)) {
            $message = "Ordner " . $name . " wurde erstellt.";
        } else {
            $message = "Ordner " . $name . " wurde nicht erstellt.";
        }
    } else if ($action == "rmFolder") {
        $name = $_POST['name'];
        $group = $_POST['group'];
        if ($admin->deleteFolderInGroup($group, $name)) {
            $message = "Ordner " . $name . " wurde entfernt.";
        } else {
            $message = "Ordner " . $name . " wurde nicht entfernt, prüfe, ob er leer ist.";
        }
    }
}

if ($message !== null) {
    $_SESSION['message'] = $message;
    echo '<script>
        setTimeout(() => {
            window.location.href = ' . json_encode($_SERVER['REQUEST_URI']) . ';
        }, 1000);
    </script>';
}

if ($user->checkUserRights("read")): ?>

    <div class="container-fluid" id="filemanager">
        <div class="row">
            <!-- Sidebar Gruppenwahl -->
            <div class="col-md-2">
                <div class="list-group mb-3">
                    <div class="list-group-item list-group-item-action text-xl">
                        <a href="javascript:void(0)" class="">Gruppen</a>
                        <?php if ($user->checkUserRights("load")): ?>
                            <a href="addGroup.php" class="btn btn-success btn-sm">+ Hinzufügen</a>
                        <?php endif; ?>
                    </div>
                    <?php foreach ($groups as $group): ?>
                        <a href="?type=<?php echo $group; ?>"
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php echo $admin->returnActiveIfTypeAreGet($group, $_GET['type']); ?>">
                            <span><?php echo ucfirst($group); ?></span>
                            <?php if ($user->checkUserRights("delete") && $group !== "main"): ?>
                                <button
                                        class="btn btn-sm btn-outline-danger"
                                        @click.prevent="openDeleteWindow('unset.php?group=<?php echo $group; ?>', '<?php echo $group; ?>')"
                                >rm
                                </button>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>

                </div>
            </div>

            <!-- Hauptbereich -->

            <!-- Hauptbereich -->
            <div class="col-md-10">
                <div class="row g-4">

                    <!-- Ordnerstruktur -->
                    <div class="col-12 col-lg-5 border-end-lg pe-lg-4">
                        <h4 class="mb-3">Struktur</h4>

                        <?php if ($message !== null): ?>
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
                                <button type="button" class="btn-close"
                                        data-bs-dismiss="alert" aria-label="Schließen"></button>
                            </div>
                        <?php endif; ?>

                        <ul class="list-unstyled">
                            <structure-item
                                    v-for="item in tree"
                                    :key="item.full"
                                    :item="item">
                            </structure-item>
                        </ul>

                        <!-- Ordner erstellen -->
                        <form method="post" class="mt-3">
                            <input type="hidden" name="action" value="addFolder">
                            <input type="hidden" name="group"
                                   value="<?= htmlspecialchars($chooseGroup ?? '', ENT_QUOTES, 'UTF-8') ?>">

                            <input type="text" name="name"
                                   class="form-control mb-2"
                                   placeholder="foldername/foldername/"
                                   required>

                            <button type="submit" class="btn btn-primary">
                                Ordner erstellen
                            </button>
                        </form>
                    </div>

                    <!-- Dateien -->
                    <div class="col-12 col-lg-7">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>Dateien</h4>

                            <?php if ($chooseGroup): ?>
                                <a href="add.php?pages=true&amp;type=<?= urlencode($chooseGroup) ?>"
                                   class="btn btn-success btn-sm">
                                    + Hinzufügen
                                </a>
                            <?php endif; ?>
                        </div>

                        <input
                                class="form-control mb-3"
                                v-model="search.name"
                                placeholder="Nach Dateiname filtern..."
                        >

                        <div v-if="filterByValue.length > 0">
                            <ul class="list-group">
                                <li
                                        v-for="file in filterByValue"
                                        :key="file.name"
                                        class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2"
                                >
                                    <div>File: {{ file.name }}</div>

                                    <div class="d-flex gap-1 flex-wrap">
                                        <a :href="file.pfad"
                                           class="btn btn-sm btn-outline-primary">view</a>

                                        <a :href="file.renameLink"
                                           class="btn btn-sm btn-outline-secondary">rename</a>

                                        <?php if ($user->checkUserRights("delete")): ?>
                                            <a
                                                    :href="file.deleteLink"
                                                    class="btn btn-sm btn-outline-danger"
                                                    @click.prevent="openDeleteWindow(file.deleteLink)"
                                            >löschen</a>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <p v-else class="text-muted">Keine Dateien gefunden.</p>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script>
        const structureData = <?php echo json_encode($structureTree, JSON_UNESCAPED_SLASHES); ?>;
        const fileData = <?php echo $fileJson; ?>;

        Vue.component('structure-item', {
            props: ['item'],
            data() {
                return {
                    open: true
                };
            },
            methods: {
                openDeleteWindow(url, group = false) {
                    if (confirm('Möchtest du ' + (group ? 'die Gruppe ' + group.charAt(0).toUpperCase() + group.slice(1) : 'diesen leeren Ordner') + ' wirklich löschen?')) {
                        window.open(url, 'DeleteWindow', 'width=600,height=400,resizable=yes,scrollbars=yes');
                    }

                }
            },
            template: `
    <li>
        <div class="d-flex align-items-center gap-2 mb-2">

            <!-- Ordnername -->
            <span
                v-if="item.type === 'folder' || item.type === 'folder-empty'"
                @click="open = !open"
                class="d-flex align-items-center"
                style="cursor: pointer; min-width: 0;"
            >
                <strong class="me-2">{{ open ? '📂' : '📁' }}</strong>
                <span>{{ item.name }}</span>
            </span>

            <!-- Datei -->
            <span v-else>
                📄 {{ item.name }}
            </span>

            <!-- Aktionen rechts -->
            <div
                v-if="item.type === 'folder-empty'"
                class="ms-auto d-flex align-items-center gap-1 flex-shrink-0"
            >
                <a
                    :href="item.renameLink"
                    class="btn btn-sm btn-outline-secondary"
                >
                    rename
                </a>

                <a
                    :href="item.deleteLink"
                    class="btn btn-sm btn-outline-danger"
                    @click.prevent="openDeleteWindow(item.deleteLink)"
                >
                    delete
                </a>
            </div>

            <!-- Leeren Ordner entfernen -->
            <form
                v-if="item.type === 'folder' || item.type === 'folder-empty'"
                method="post"
                class="ms-auto d-flex align-items-center flex-shrink-0"
                @click.stop
            >
                <input type="hidden" name="action" value="rmFolder">
                <input
                    type="hidden"
                    name="group"
                    value="<?php echo htmlspecialchars($chooseGroup ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                >
                <input type="hidden" name="name" :value="item.full">

                <button
                    type="submit"
                    class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('Diesen Ordner entfernen, sofern er leer ist?')"
                >
                    Ordner entfernen
                </button>
            </form>

        </div>

        <!-- Unterordner -->
        <ul
            v-if="item.children && item.children.length && open"
            class="ms-4 list-unstyled"
        >
            <structure-item
                v-for="child in item.children"
                :key="child.full"
                :item="child"
            ></structure-item>
        </ul>
    </li>
`
        });

        new Vue({
            el: '#filemanager',
            data: {
                tree: structureData,
                files: fileData,
                search: {name: ''}
            },
            methods: {
                openDeleteWindow(url) {
                    if (confirm('Möchtest du diese Datei wirklich löschen, wenn dadurch ein Ordner leer werden, werden diese ebenfalls gelöscht.')) {
                        window.open(url, 'DeleteWindow', 'width=600,height=400,resizable=yes,scrollbars=yes');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                }
            },
            computed: {
                filterByValue() {
                    return this.files
                        .filter(item => item.name.toLowerCase().includes(this.search.name.trim().toLowerCase()))
                        .sort((a, b) => a.name.localeCompare(b.name));
                }
            }
        });
    </script>

    <?php include "../layout/footer.php"; ?>
<?php else: ?>
    <div class="alert alert-danger">
        <b>Rechte Fehler!</b> Du hast nicht die Rechte für diesen Teil des Admins
    </div>
<?php endif; ?>
