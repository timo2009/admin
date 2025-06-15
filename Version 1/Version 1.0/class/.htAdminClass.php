<?php 

/**
 * AdminClass
 */
class AdminClass
{
	
	public function loadFileUp($data)
	{
		if (isset($data)) 
		{
	    	$fileName = $_FILES['file']['name'];
	    	$tmpName = $_FILES['file']['tmp_name'];
	    	$type=$_FILES["file"]["type"];
	    	$size=$_FILES["file"]["size"];
	    	$extension = substr($fileName, strrpos($fileName, '.') + 1);
	    	$type_info = explode("/", $type);

	    	if ($type_info[0]=="audio") {
	    		$pfad="../f/medien/audio/".$fileName;
	    	}	    	
	    	elseif ($type_info[0]=="image") {
	    		$pfad="../f/medien/img/".$fileName;
	    	}	    	
	    	elseif ($type_info[0]=="video") {
	    		$pfad="../f/medien/video/".$fileName;
	    	}

	    	elseif ($type_info[0]=="text") {
	    		$pfad="../f/".$fileName;
	    	}
	    	else {
	    		return "Unknow Type";
	    		die();
	    	}
	    	
	    	$userpfad=substr($pfad, 2);

    		if (move_uploaded_file($tmpName, $pfad)){
    			return [$fileName, $tmpName, $type, $size, $userpfad, $type_info];
    		}
    		else {
    			return $_FILES["file"]["error"];
    		}
		}
	}

	public function listFiles($type)
	{
		$files=[];


    	if ($type=="audio") {
    		$verzeichnis="../f/medien/audio/";
    	}	    	
    	elseif ($type=="image") {
    		$verzeichnis="../f/medien/img/";
    	}	    	
    	elseif ($type=="video") {
    		$verzeichnis="../f/medien/video/";
    	}

    	else {
    		$verzeichnis="../f/";
    	}
    	if ($handle = opendir($verzeichnis)){
			while (($file = readdir($handle)) !== false){
				$extension = substr($file, strrpos($file, '.') + 1);
				if (filetype( $file )=="dir" || $file=="dowload.php" || $verzeichnis.$file=="../f/medien" || $extension=="php") {
				  continue;
				}

				$files[]=[
					'name' => $file,
					'pfad' => htAdminClass . phpsubstr($verzeichnis, 2) . $file,
					'deleteLink' => "unset.php?file=".$verzeichnis.$file,
					'showLink' => "show.php?file=".$verzeichnis.$file
				];
			  
			}
			closedir($handle);
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
				return true;
			}
			else {
				return false;
			}
		}
	}

	public function showFile($pfad)
	{
		$type=mime_content_type($pfad);
		$type_info = explode("/", $type);
		$path_parts = pathinfo($pfad);

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
	    elseif ($type_info[0]=="text") {
	    	$html=file_get_contents($pfad);
	    	$back="list.php?type=text";
	    }
	    else {
	    	$html="<f>\n<head>\n<title>Error</title>\n</head>\n<body>\n<b>Error: </b>File not found.\n</body>\n</f>";
	    }
	    return [$html, $path_parts['basename'], $back, $pfad];
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
}

?>