<?php
/**
 * AdminClass
 */

class AdminClass
{
    public $settings;

    private $url;

    private $userclass;

    public function __construct($user)
    {
        $this->settings=file("../.htdatabase/.htsettings.txt");
        $this->url = "../../..".str_replace("\n", "", $this->settings[0]);
        $this->userclass = $user;
    }

    public function loadFileUp($data)
    {
        if (isset($data)) {
            $filesCount = count($_FILES['filesToUpload']['name']);
            $uploadData = [];
            for ($i = 0; $i < $filesCount; $i++) {
                $fileName = $_FILES['filesToUpload']['name'][$i];
                $tmpName = $_FILES['filesToUpload']['tmp_name'][$i];
                if ($fileName == "" || $tmpName == "") {
                    continue;
                }
                $type = $_FILES["filesToUpload"]["type"][$i];
                $size = $_FILES["filesToUpload"]["size"][$i];
                $extension = substr($fileName, strrpos($fileName, '.') + 1);
                $type_info = explode("/", $type);


                if ($type_info[0] == "audio") {
                    $pfad = $this->url . "audio/" . $fileName;
                } elseif ($type_info[0] == "image") {
                    $pfad = $this->url . "image/" . $fileName;
                } elseif ($type_info[0] == "video") {
                    $pfad = $this->url . "video/" . $fileName;
                } elseif ($type_info[0] == "text") {
                    $pfad = $this->url . $fileName;
                } else {
                    $pfad = $this->url . $fileName;
                }

                $userpfad = substr($pfad, 8);
                if (file_exists($pfad)) {
                    $uploadData[] = ["false", $fileName];
                    continue;
                }

                $storeIn = move_uploaded_file($tmpName, $pfad);


                if ($storeIn) {
                    $this->userclass->safeAction("Load '" . $fileName . "' up");
                    $uploadData[] = [$fileName, $tmpName, $type, $size, $userpfad, $type_info];
                }
            }
            return $uploadData;
        }
    }

    private function listFilesFromFolder($pfad, $type, $allFiles=[], $all=false)
    {
        if ($handle = opendir($pfad)){
            while (($file = readdir($handle)) !== false){
                $extension = substr($file, strrpos($file, '.') + 1);
                if (is_dir($pfad . '/' . $file) || $extension == "") {
                    continue;
                }
                if (!$all) {
                    $allFiles[]=[
                        'name' => $file,
                        'pfad' => substr($pfad, 8).$file,
                        'deleteLink' => "unset.php?file=".$pfad.$file,
                        'showLink' => "show.php?file=".$pfad.$file,
                        'renameLink' => "rename.php?name=".$pfad.$file."&&type=".$type
                    ];
                }
                else {
                    $allFiles[]=[
                        'name' => $file,
                        'pfad' => substr($pfad, 8).$file,
                        'deleteLink' => "unset.php?file=".$pfad.$file,
                        'showLink' => "show.php?file=".$pfad.$file."&all=ja",
                        'renameLink' => "rename.php?name=".$pfad.$file."&&type=".$type
                    ];
                }

            }
            closedir($handle);
        }
        return $allFiles;
    }

    public function listFiles($type)
    {
        $files=[];


        if ($type!="all") {
            if ($type=="audio") {
                $verzeichnis=$this->url."audio/";
            }
            elseif ($type=="image") {
                $verzeichnis=$this->url."image/";
            }
            elseif ($type=="video") {
                $verzeichnis=$this->url."video/";
            }

            else {
                $verzeichnis=$this->url;
            }
            $files=$this->listFilesFromFolder($verzeichnis, $type, $files);
        }
        else {
            $files=$this->listFilesFromFolder($this->url, $type, $files, true);
            $files=$this->listFilesFromFolder($this->url."audio/", $type, $files, true);
            $files=$this->listFilesFromFolder($this->url."image/", $type, $files, true);
            $files=$this->listFilesFromFolder($this->url."video/", $type, $files, true);
        }
        return $files;
    }
    public function returnActiveIfTypeAreGet($type, $get)
    {
        if ($type==$get) {
            return "active";
        }
    }

    public function unsetFile($pfad)
    {
        if (isset($pfad)) {
            if(unlink($pfad)){
                $this->userclass->safeAction("Unlink '".$pfad."'");
                return true;
            }
            else {
                return false;
            }
        }
    }

    public function renameFile($oldFilename, $newFilename)
    {
        if (!file_exists($newFilename)) {
            $content=file_get_contents($oldFilename);
            unlink($oldFilename);
            file_put_contents($newFilename, $content);
            $this->userclass->safeAction("Renamame '".$oldFilename."' to '".$newFilename."'");
            return true;
        } else {
            return false;
        }
    }

    public function returnFilename($pfad)
    {
        $arr=explode("/", $pfad);
        $name = (isset($arr[count($arr)-1])) ? $arr[count($arr)-1] : null;
        return $name;
    }

    public function showFile($pfad, $all=false)
    {
        $type=mime_content_type($pfad);
        $type_info = explode("/", $type);
        $path_parts = pathinfo($pfad);
        $text=false;
        $parts = explode(".", $pfad);

        if ($type_info[0]=="audio") {
            $html="<f>\n<head>\n<title>Audio</title>\n</head>\n<body>\n<audio autoplay controls src='".$pfad."'></audio>\n</body>\n</f>";
            $back="list.php?type=audio";
        }
        elseif ($type_info[0]=="image") {
            $html="<f>\n<head>\n<title>Bild</title>\n<style>\nimg{\nwidth: 100%;\nheight: auto;\n}\n</style>\n</head>\n<body>\n<img src='".$pfad."'>\n</body>\n</f>";
            $back="list.php?type=image";
        }
        elseif ($type_info[0]=="video") {
            $html="<f>\n<head>\n<title>Video</title>\n<style>\nvideo{\nwidth: 100%;\nheight: auto;\n}\n</style>\n</head>\n<body>\n<video src='".$pfad."' autoplay preload=”none” controls></video>\n</body>\n</f>";
            $back="list.php?type=video";
        }
        elseif ($type_info[1]=="plain" || end($parts) == "php") {
            $html="<f>\n<head>\n<title>Text</title>\n</head>\n<body>\n<pre>".htmlspecialchars(file_get_contents($pfad))."</pre>\n</body>\n</f>";
            $back="list.php?type=text";
            $text=true;
        }
        elseif ($type_info[1]=="pdf") {
            $html="<f>\n<head>\n<title>PDF</title>\n</head>\n<body>\n<object data='".$pfad."' style='width:100%;height:10000px'>\n</body>\n</f>";
            $back="list.php?type=text";
        }
        elseif ($type_info[0]=="text" || $type_info[1]=="x-empty") {
            $html=file_get_contents($pfad);
            $back="list.php?type=text";
            $text=true;
        }
        else {
            $html="<f>\n<head>\n<title>Die Datei kann nicht geladen werden</title>\n</head>\n<body>\n<b>\n Für diesen Typ von Datei gibt es keine Vorschau. \n</b>\n</body>\n</f>";
            $back="list.php?type=all";
            $text=true;
        }
        if ($all) {
            return [$html, $path_parts['basename'], "list.php?type=all", $pfad, $text];
        }
        else {
            return [$html, $path_parts['basename'], $back, $pfad, $text];
        }
    }

    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
    public function readTextFile($pfad)
    {
        return file_get_contents($pfad);
    }
    public function editTextFile($pfad, $content)
    {
        if (file_exists($pfad)) {
            unlink($pfad);
            if(file_put_contents($pfad, $content))
            {
                $this->userclass->safeAction("Edit '".$pfad."'");
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function addFile($name, $art)
    {
        $this->userclass->safeAction("Add '".$name."' (".$art.")");
        $pfad=$this->url.$name;
        $htmlG="<!DOCTYPE f>\n<f>\n<head>\n<meta charset='utf-8'>\n<meta name='viewport' content='width=device-width, initial-scale=1'>\n<title>HTML Datei</title>\n</head>\n<body>\n\n</body>\n</f>";
        if ($art=="f-g") {
            $pfad=$pfad.".f";
            if (file_exists($pfad)) {
                return false;
            }
            file_put_contents($pfad, $htmlG);
            return true;
        }
        elseif ($art=="f") {
            $pfad=$pfad.".f";
            if (file_exists($pfad)) {
                return false;
            }
            file_put_contents($pfad, "");
            return true;
        }
        else {
            $pfad=$pfad.".txt";
            if (file_exists($pfad)) {
                return false;
            }
            file_put_contents($pfad, "");
            return true;
        }
    }
}
