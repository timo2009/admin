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

/*
 * <?php
// Alle GET-Parameter der aktuellen URL wieder zusammensetzen
$queryString = http_build_query($_GET);

// Ziel-Link mit übergebenen Parametern
$link = "zielseite.php?" . $queryString;
?>
<a href="<?= $link ?>">Link mit allen GET-Parametern</a>
 */

if ($user->checkUserRights("read")): ?>

    <div class="container-fluid" id="filemanager">
        <div class="row">
            <!-- Sidebar Gruppenwahl -->
            <div class="col-md-2">
                <div class="list-group mb-3">
                    <div class="list-group-item list-group-item-action text-xl">
                        <a href="javascript:void(0)" class="">Gruppen</a>
                        <a href="addGroup.php" class="btn btn-success btn-sm">+ Hinzufügen</a>
                    </div>
                    <?php foreach ($groups as $group): ?>
                        <a href="?type=<?php echo $group; ?>"
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php echo $admin->returnActiveIfTypeAreGet($group, $_GET['type']); ?>">
                            <span><?php echo ucfirst($group); ?></span>
                            <button
                                    class="btn btn-sm btn-outline-danger"
                                    @click.prevent="openDeleteWindow('unset.php?group=<?php echo $group; ?>', '<?php echo $group; ?>')"
                            >🗑️
                            </button>
                        </a>
                    <?php endforeach; ?>

                </div>
            </div>

            <!-- Hauptbereich -->
            <div class="col-md-10">
                <div class="row">
                    <!-- Ordnerstruktur -->
                    <div class="col-md-5 border-end pe-4">
                        <h4 class="mb-3">📁 Struktur</h4>
                        <ul class="list-unstyled">
                            <structure-item v-for="item in tree" :key="item.full" :item="item"></structure-item>
                        </ul>
                    </div>

                    <!-- Dateien -->
                    <div class="col-md-7 ps-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>📄 Dateien</h4>
                            <a v-if="'<?php echo $chooseGroup ?>'"
                               :href="'add.php?pages=true&type=<?php echo $chooseGroup; ?>'"
                               class="btn btn-success btn-sm">+ Hinzufügen</a>
                        </div>

                        <input class="form-control mb-3" v-model="search.name" placeholder="Nach Dateiname filtern...">

                        <div v-if="filterByValue.length > 0">
                            <ul class="list-group">
                                <li v-for="file in filterByValue" :key="file.name"
                                    class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        📄 {{ file.name }}
                                    </div>
                                    <div>
                                        <a :href="file.pfad" class="btn btn-sm btn-outline-primary">👁️</a>
                                        <a :href="file.renameLink" class="btn btn-sm btn-outline-secondary">✏️</a>
                                        <a
                                                :href="file.deleteLink"
                                                class="btn btn-sm btn-outline-danger"
                                                @click.prevent="openDeleteWindow(file.deleteLink)"
                                        >🗑️</a>
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
                  <div class="d-flex align-items-center mb-1">
                    <span v-if="item.type === 'folder' || item.type === 'folder-empty'" @click="open = !open" style="cursor: pointer;">
                      <strong>{{ open ? '📂' : '📁' }}</strong> {{ item.name }}
                    </span>
                    <span v-else>📄 {{ item.name }}</span>
                    <div class="ms-auto" v-if="item.type === 'folder-empty'">
                      <a :href="item.renameLink" class="btn btn-sm btn-outline-secondary btn-xs">✏️</a>
                      <a
                        :href="item.deleteLink"
                        class="btn btn-sm btn-outline-danger btn-xs ms-1"
                        @click.prevent="openDeleteWindow(item.deleteLink)"
                      >🗑️</a>
                    </div>
                  </div>
                  <ul v-if="item.children && open" class="ms-3 list-unstyled">
                    <structure-item v-for="child in item.children" :key="child.full" :item="child"></structure-item>
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
