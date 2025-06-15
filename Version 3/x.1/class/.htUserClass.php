<?php

/**
 * The UserClass
 */
class UserClass
{
    public $username;
    public $passwort;
    public $login;
    public $rightsNumber;

    private $allUsers;
    private $allUsersRights;

    private $filename;

    public $settings;
    private $url;
    public $pages;

    public function __construct()
    {
        $this->filename = "../.htdatabase/.htusers.txt";
        $this->settings = file("../.htdatabase/.htsettings.txt");
        $this->url = "../../.." . trim($this->settings[0]);
        $this->pages = "../../.." . trim($this->settings[1]);

        $this->allUsers = file($this->filename);
        $this->allUsersRights = file("../.htdatabase/.htuserrights.txt");
    }

    public function _setUsernameAndPasswort($username, $passwort, $login = false)
    {
        $this->username = $username;
        $this->passwort = $passwort;
        $this->login = $login;
    }

    public function login()
    {
        if (!$this->login) {
            foreach ($this->allUsers as $user) {
                $user_info = explode(";", $user);
                $username = $user_info[0];

                if (substr($user_info[1], -1) == "\n") {
                    $passwort = substr($user_info[1], 0, -1);
                } else {
                    $passwort = $user_info[1];
                }
                if (
                    $username == $this->username
                    &&
                    password_verify($this->passwort, $passwort)
                ) {
                    $this->login = true;
                    $this->rightsNumber = str_replace("\n", "", $user_info[2]);
                    return true;
                }
            }
            if (!$this->login) {
                return false;
            }
        } else {
            return true;
        }
    }

    public function getAndCheckCookie($cookie)
    {

        foreach ($this->allUsers as $user) {
            $user_info = explode(";", $user);
            if (password_verify($user_info[0], $cookie)) {
                return $user_info;
            }
        }
        return false;
    }

    public function checkUserRights($action)
    {
        foreach ($this->allUsers as $user) {
            $user_info = explode(";", $user);
            $username = $user_info[0];

            if (substr($user_info[1], -1) == "\n") {
                $passwort = substr($user_info[1], 0, -1);
            } else {
                $passwort = $user_info[1];
            }
            if (
                $username == $this->username
                &&
                $passwort == $this->passwort
            ) {
                $this->rightsNumber = str_replace("\n", "", $user_info[2]);
            }
        }

        foreach ($this->allUsersRights as $userright) {
            $right_info = explode(";", $userright);

            if ($this->rightsNumber == $right_info[0]) {

                $rights = str_replace("only", "", $right_info[1]);
                $rights = str_replace("\n", "", $rights);
                if (preg_match("/-/", $rights)) {
                    $rights = explode("-", $rights);

                } else {
                    $rights = [$rights];
                }

                foreach ($rights as $right) {
                    if ($right == $action) {
                        return true;
                    }
                    if ($right == "superuser") {
                        return true;
                    }
                }
            }

        }
        return false;
    }

    public function addCookie($username)
    {
        $encodeUsername = password_hash($username, PASSWORD_DEFAULT);
        return $encodeUsername;
    }

    public function addFirstUser($username, $passwort)
    {
        if (!empty($username) && !empty($passwort)) {
            unlink($this->filename);
            $hashedPassword = password_hash($passwort, PASSWORD_DEFAULT);
            file_put_contents($this->filename, $username . ";" . $hashedPassword . ";1" . "\n");

            mkdir("../../.." . $this->pages . "main/src/", 0705,  true);
            // Beispiel-Inhalt für styles.css
            $cssContent = <<<CSS
body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    margin: 20px;
    color: #333;
}
CSS;

// Beispiel-Inhalt für index.html
            $htmlContent = '
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hello World</title>
    <link rel="stylesheet" href="' . substr($this->pages, 8) . 'main/src/styles.css" />
</head>
<body>
    <h1>Hello World</h1>
    <p>Dies ist eine Beispielseite.</p>
</body>
</html>';
            file_put_contents("../../.." . $this->pages . "main/src/styles.css", $cssContent);
            file_put_contents("../../.." . $this->pages . "main/index.php", $htmlContent);
            file_put_contents("../../../index.php", '<?php

$requestUri = $_SERVER["REQUEST_URI"];

if ($requestUri == "/") {
    header("Location: /index.php");
    exit;
}

$baseFile = basename(__FILE__);
$pathInfo = str_replace("/" . $baseFile, "", $requestUri);
$path = ltrim($pathInfo, "/");
if ($path == "") {
    $path = "main/index.php";
}

// Hier wird der Pfad als string literal eingefügt
$basePagesDir = ' . var_export(substr($this->pages, 7), true) . ';
$file = $basePagesDir . $path;

if (!file_exists($file)) {
    header("HTTP/1.0 404 Not Found");
    echo "404 - Datei nicht gefunden";
    exit;
}

$mime = mime_content_type($file);

if (pathinfo($file, PATHINFO_EXTENSION) === "php") {
    ob_start();
    include $file;
    $output = ob_get_clean();
    header("Content-Type: text/html; charset=utf-8");
    echo $output;
    exit;
}

$videoTypes = ["video/mp4", "video/webm", "video/ogg"];

if (in_array($mime, $videoTypes)) {
    header("Content-Type: $mime");
    header("Content-Length: " . filesize($file));
    header("Cache-Control: public, max-age=86400");
    readfile($file);
    exit;
}

header("Content-Type: $mime");
echo file_get_contents($file);

');


            mkdir("../../.." . $this->url, 0700, true);
            mkdir("../../.." . $this->url . "audio/", 0700, true);
            mkdir("../../.." . $this->url . "docs/", 0700, true);
            mkdir("../../.." . $this->url . "files/", 0700, true);
            mkdir("../../.." . $this->url . "image/", 0700, true);
            mkdir("../../.." . $this->url . "video/", 0700, true);
            return true;
        } else {
            return false;
        }
    }

    public function addUser($username, $passwort, $right)
    {
        $file = $this->filename;
        $hashedPassword = password_hash($passwort, PASSWORD_DEFAULT);

        $data = $username . ";" . $hashedPassword . ";" . $right . "\n";

        $handle = fopen($file, "a");
        fwrite($handle, $data);
        fclose($handle);

        return true;
    }

    public function safeAction($action)
    {
        $file = "../.htdatabase/.htlog.txt";

        date_default_timezone_set('Europe/Berlin');
        $current_time = date('H:i:s d.m.Y');

        $data = $current_time . ";" . $this->username . ";" . $action . "\n";

        $handle = fopen($file, "a");
        fwrite($handle, $data);
        fclose($handle);
    }

    public function getAction()
    {
        $file = "../.htdatabase/.htlog.txt";
        return file($file);
    }

    public function clearAction()
    {
        $file = "../.htdatabase/.htlog.txt";
        file_put_contents($file, "");
    }

    public function findAllUsers()
    {
        $usersInUsersTxt = file($this->filename);
        foreach ($usersInUsersTxt as $line) {
            $user_info = explode(";", $line);
            for ($i = 0; $i < 3; $i++) {
                if ($i == 0) {
                    $username = $user_info[0];
                } elseif ($i == 1) {
                    $password = $user_info[1];
                } elseif ($i == 2) {
                    $status = $user_info[2];
                }
            }
            $usersData[] = [
                'username' => $username, 'password' => '******', 'right' => $status];
        }
        return $usersData;

    }

    public function editUser($username, $newStatus)
    {

        $oldContent = file_get_contents($this->filename);

        foreach ($this->allUsers as $user) {
            $info = explode(";", $user);
            if ($info[0] == $username) {

                $string = $username . ";" . $info[1] . ";" . $newStatus;
                $newContent = str_replace($user, $string, $oldContent);
                file_put_contents($this->filename, $newContent);
                return true;
            }
        }

        return false;

    }

    public function resetPassword($username, $newPasswort)
    {

        $oldContent = file_get_contents($this->filename);

        foreach ($this->allUsers as $user) {
            $info = explode(";", $user);
            if ($info[0] == $username) {
                $hashedPassword = password_hash($newPasswort, PASSWORD_DEFAULT);

                $string = $username . ";" . $hashedPassword . ";" . $info[2];
                $newContent = str_replace($user, $string, $oldContent);
                file_put_contents($this->filename, $newContent);
                return true;
            }
        }

        return false;

    }

    public function deleteUser($username)
    {

        $oldContent = file_get_contents($this->filename);

        foreach ($this->allUsers as $user) {
            $info = explode(";", $user);
            if ($info[0] == $username) {
                $newContent = str_replace($user, "", $oldContent);
                file_put_contents($this->filename, $newContent);
                return true;
            }
        }

        return false;

    }
}
