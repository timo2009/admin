<?php

class AdminClass
{
    public $settings;
    private $url;
    public $pages;
    private $userclass;
    private $shares;

    public function __construct($user)
    {
        $this->settings = file("../.htdatabase/.htsettings.txt");
        $this->url = "../../.." . trim($this->settings[0]);
        $this->pages = "../../.." . trim($this->settings[1]);
        $this->userclass = $user;
        $this->shares = file("../.htdatabase/.htshares.txt");
    }

    public function loadFileUp($data)
    {
        if (!isset($data)) return;

        $filesCount = count($_FILES['filesToUpload']['name']);
        $uploadData = [];

        for ($i = 0; $i < $filesCount; $i++) {
            $fileName = $_FILES['filesToUpload']['name'][$i];
            $tmpName = $_FILES['filesToUpload']['tmp_name'][$i];

            if ($fileName === "" || $tmpName === "") continue;

            $type = $_FILES["filesToUpload"]["type"][$i];
            $size = $_FILES["filesToUpload"]["size"][$i];
            $type_info = explode("/", $type);

            $directoryMap = [
                "audio" => "audio/",
                "image" => "image/",
                "video" => "video/",
                "text" => "files/",
                "scripts" => "files/",
                "application" => "docs/"
            ];

            $extension = strtolower($type_info[0]);
            $basePath = $directoryMap[$extension] ?? "files/";
            $pfad = $this->url . $basePath . $fileName;
            $userpfad = substr($pfad, 8);

            if (file_exists($pfad)) {
                $uploadData[] = ["false", $fileName];
                continue;
            }

            if (move_uploaded_file($tmpName, $pfad)) {
                $this->userclass->safeAction("Load '" . $fileName . "' up");
                $uploadData[] = [$fileName, $tmpName, $type, $size, $userpfad, $type_info, rtrim($directoryMap[$extension], "/")];
            }
        }

        return $uploadData;
    }

    private function listFilesFromFolder($pfad, $type, $allFiles = [], $all = false)
    {
        if (!is_dir($pfad)) return $allFiles;

        if ($handle = opendir($pfad)) {
            while (($file = readdir($handle)) !== false) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);

                if (is_dir($pfad . '/' . $file) || $extension == "") continue;

                $relativePath = substr($pfad, 8) . $file;
                $fileInfo = [
                    'name' => $file,
                    'pfad' => $relativePath,
                    'deleteLink' => "unset.php?file=" . $pfad . $file,
                    'showLink' => "show.php?file=" . $pfad . $file . ($all ? "&all=ja" : "") . "&type=" . $type,
                    'renameLink' => "rename.php?name=" . $pfad . $file . "&type=" . $type
                ];

                $allFiles[] = $fileInfo;
            }
            closedir($handle);
        }

        return $allFiles;
    }

    public function listFiles($type)
    {
        $files = [];

        $directoryMap = [
            "audio" => "audio/",
            "image" => "image/",
            "video" => "video/",
            "files" => "files/",
            "docs" => "docs/"
        ];

        if ($type != "all") {
            $path = $this->url . $type . "/";
            $files = $this->listFilesFromFolder($path, $type, $files);
        } else {
            foreach ($directoryMap as $subDir) {
                $path = $this->url . $subDir;
                $files = $this->listFilesFromFolder($path, $type, $files, true);
            }
        }

        return $files;
    }

    public function returnActiveIfTypeAreGet($type, $get)
    {
        return ($type == $get) ? "active" : null;
    }

    public function unsetFile($pfad)
    {
        if (!isset($pfad)) return;

        if (unlink($pfad)) {
            $this->userclass->safeAction("Unlink '" . $pfad . "'");
            return true;
        }

        return false;
    }

    public function renameFile($oldFilename, $newFilename)
    {
        if (!file_exists($newFilename)) {
            $content = file_get_contents($oldFilename);
            unlink($oldFilename);
            file_put_contents($newFilename, $content);
            $this->userclass->safeAction("Renamame '" . $oldFilename . "' to '" . $newFilename . "'");
            return true;
        }

        return false;
    }

    public function returnFilename($pfad)
    {
        $arr = explode("/", $pfad);
        return end($arr);
    }

    public function showFile($pfad, $all = false)
    {
        $type = mime_content_type($pfad);
        $type_info = explode("/", $type);
        $extension = strtolower(pathinfo($pfad, PATHINFO_EXTENSION));
        $proxyPath = "fileproxy.php?file=" . urlencode($pfad);
        $text = false;

        $typeMap = [
            "audio" => "audio",
            "image" => "image",
            "video" => "video",
            "text" => "text",
            "pdf" => "pdf"
        ];

        $mappedType = $typeMap[$type_info[0]] ?? (($extension === "pdf") ? "pdf" : "all");

        switch (true) {
            case $type_info[0] === "audio":
                $html = "<f><head><title>Audio</title></head><body><audio autoplay controls src='$proxyPath'></audio></body></f>";
                break;

            case $type_info[0] === "image":
                $html = "<f><head><title>Bild</title><style>img{width:100%;height:auto;}</style></head><body><img src='$proxyPath'></body></f>";
                break;

            case $type_info[0] === "video":
                $html = "<f><head><title>Video</title><style>video{width:100%;height:auto;}</style></head><body><video src='$proxyPath' autoplay preload='none' controls></video></body></f>";
                break;

            case $type_info[1] === "plain" || $extension === "php":
                $html = "<f><head><title>Text</title></head><body><pre>" . htmlspecialchars(file_get_contents($pfad)) . "</pre></body></f>";
                $text = true;
                $mappedType = "text";
                break;

            case $extension === "pdf" || $type_info[1] === "pdf":
                $html = "<f><head><title>PDF</title></head><body><object data='$proxyPath' type='application/pdf' width='100%' height='10000px'></object></body></f>";
                break;

            case $type_info[0] === "text" || $type_info[1] === "x-empty":
                $html = file_get_contents($pfad);
                $text = true;
                $mappedType = "text";
                break;

            default:
                $html = "<f><head><title>Dateityp nicht unterstützt</title></head><body><b>Für diesen Dateityp gibt es keine Vorschau.</b></body></f>";
                $mappedType = "all";
                $text = true;
                break;
        }

        $back = "list.php?type=" . ($all ? "all" : $mappedType) . "&type=" . $type;
        return [$html, basename($pfad), $back, $pfad, $text];
    }

    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
        if ($bytes > 1) return $bytes . ' bytes';
        if ($bytes == 1) return $bytes . ' byte';

        return '0 bytes';
    }

    public function readTextFile($pfad)
    {
        return file_get_contents($pfad);
    }

    public function isInShareMode($pfad)
    {
        $shareFile = "../.htdatabase/.htshares.txt";

        if (!file_exists($shareFile)) return false;

        $shares = file($shareFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return in_array(trim($pfad), $shares);
    }

    public function changeShareMode($pfad)
    {
        $shareFile = realpath("../.htdatabase/.htshares.txt") ?: "../.htdatabase/.htshares.txt";
        $this->shares = file_exists($shareFile) ? file($shareFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

        $absolutePath = $pfad;
        $updatedShares = [];
        $found = false;

        foreach ($this->shares as $share) {
            $shareTrimmed = trim($share);
            if ($shareTrimmed === $absolutePath) {
                $found = true;
                continue;
            }
            $updatedShares[] = $shareTrimmed;
        }

        if ($found) {
            file_put_contents($shareFile, implode("\n", $updatedShares) . "\n");
            $this->userclass->safeAction("Share mode deactivated for '$absolutePath'");
            return false;
        } else {
            $updatedShares[] = $absolutePath;
            file_put_contents($shareFile, implode("\n", $updatedShares) . "\n");
            $this->userclass->safeAction("Share mode activated for '$absolutePath'");
            return true;
        }
    }

    public function editTextFile($pfad, $content)
    {
        if (!file_exists($pfad)) return false;

        unlink($pfad);
        if (file_put_contents($pfad, $content)) {
            $this->userclass->safeAction("Edit '" . $pfad . "'");
            return true;
        }

        return false;
    }

    public function addFile($name, $art, $type = false)
    {
        $this->userclass->safeAction("Add '$name' ($art)");
        $baseDir = $type ? $this->pages . $type : $this->url . "files";
        $pfad = $baseDir . '/' . $name;
        $extensionMap = [
            "html-g" => ".html",
            "html" => ".html",
            "php" => ".php",
            "custom" => "",
            "default" => ".txt"
        ];

        $extension = $extensionMap[$art] ?? $extensionMap["default"];
        $pfad .= $extension;

        if (file_exists($pfad)) return false;

        if (!is_dir(dirname($pfad))) {
            mkdir(dirname($pfad), 0777, true);
        }

        $contentMap = [
            "html-g" => "<!DOCTYPE html>\n<html lang='en'>\n<head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'><title>HTML Datei</title></head><body>\n\n</body></html>",
            "php" => "<?php\n\n",
            "default" => ""
        ];

        $content = $contentMap[$art] ?? $contentMap["default"];
        file_put_contents($pfad, $content);
        return true;
    }

    public function listPagesGroups()
    {
        return array_filter(scandir($this->pages), function ($file) {
            return $file !== '.' && $file !== '..' && is_dir($this->pages . DIRECTORY_SEPARATOR . $file);
        });
    }

    public function groupStructure($group)
    {
        $baseDir = $this->pages . $group . '/';
        if (!is_dir($baseDir)) return [];

        $structure = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $fileInfo) {
            if (!$fileInfo->isFile()) continue;

            $relativePath = str_replace('\\', '/', substr($fileInfo->getPathname(), strlen($baseDir)));
            $parts = explode('/', $relativePath);
            $current = &$structure;

            foreach ($parts as $part) {
                if ($part === end($parts)) {
                    $current[] = $part;
                } else {
                    if (!isset($current[$part])) $current[$part] = [];
                    $current = &$current[$part];
                }
            }
        }

        return $structure;
    }

    public function listFilesInGroups($group)
    {
        $baseDir = $this->pages . $group . '/';
        if (!is_dir($baseDir)) return [];

        $files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile()) {
                $files[] = str_replace('\\', '/', substr($fileInfo->getPathname(), strlen($baseDir)));
            }
        }

        return $files;
    }

    public function returnGroupPath($group)
    {
        return $this->pages . $group . DIRECTORY_SEPARATOR;
    }

    public function deleteFolderIfEmpty($folderPath)
    {
        $fullPath = rtrim($this->pages, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim($folderPath, DIRECTORY_SEPARATOR);

        if (!is_dir($fullPath)) return false;

        $files = array_diff(scandir($fullPath), ['.', '..']);
        if (count($files) > 0) return false;

        return rmdir($fullPath);
    }

    public function createGroup($group)
    {
        $group = strtolower($group);
        $path = $this->pages . $group . DIRECTORY_SEPARATOR;

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            return true;
        }

        return false;
    }

    public function deleteGroup($group)
    {
        $group = strtolower($group);
        $path = $this->pages . $group . DIRECTORY_SEPARATOR;

        if (is_dir($path)) {
            $this->deleteDirectoryRecursively($path);
            return true;
        }

        return false;
    }

    private function deleteDirectoryRecursively($dir)
    {
        $items = array_diff(scandir($dir), ['.', '..']);
        foreach ($items as $item) {
            $itemPath = $dir . DIRECTORY_SEPARATOR . $item;
            is_dir($itemPath)
                ? $this->deleteDirectoryRecursively($itemPath)
                : unlink($itemPath);
        }
        rmdir($dir);
    }
}
