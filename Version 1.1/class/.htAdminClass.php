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
	    		$pfad="../../../html/medien/audio/".$fileName;
	    	}	    	
	    	elseif ($type_info[0]=="image") {
	    		$pfad="../../../html/medien/img/".$fileName;
	    	}	    	
	    	elseif ($type_info[0]=="video") {
	    		$pfad="../../../html/medien/video/".$fileName;
	    	}

	    	elseif ($type_info[0]=="text") {
	    		$pfad="../../../html/".$fileName;
	    	}
	    	else {
	    		return "Unknow Type";
	    		die();
	    	}
	    	
	    	$userpfad=substr($pfad, 8);

    		if (move_uploaded_file($tmpName, $pfad)){
    			return [$fileName, $tmpName, $type, $size, $userpfad, $type_info];
    		}
    		else {
    			return $_FILES["file"]["error"];
    		}
		}
	}

	private function listFilesFromFolder($pfad, $allFiles=[], $all=false)
	{
		if ($handle = opendir($pfad)){
			while (($file = readdir($handle)) !== false){
				$extension = substr($file, strrpos($file, '.') + 1);
				if (filetype( $file )=="dir" || $pfad.$file=="../../../html/medien" || $extension=="php") {
				  continue;
				}
				if (!$all) {
					$allFiles[]=[
						'name' => $file,
						'pfad' => substr($pfad, 8).$file,
						'deleteLink' => "unset.php?file=".$pfad.$file,
						'showLink' => "show.php?file=".$pfad.$file
					];
				}
				else {
					$allFiles[]=[
						'name' => $file,
						'pfad' => substr($pfad, 8).$file,
						'deleteLink' => "unset.php?file=".$pfad.$file,
						'showLink' => "show.php?file=".$pfad.$file."&all=ja"			
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
	    		$verzeichnis="../../../html/medien/audio/";
	    	}	    	
	    	elseif ($type=="image") {
	    		$verzeichnis="../../../html/medien/img/";
	    	}	    	
	    	elseif ($type=="video") {
	    		$verzeichnis="../../../html/medien/video/";
	    	}

	    	else {
	    		$verzeichnis="../../../html/";
	    	}
	    	$files=$this->listFilesFromFolder($verzeichnis, $files);
		}
		else {
	    	$files=$this->listFilesFromFolder("../../../html/", $files, true);
	    	$files=$this->listFilesFromFolder("../../../html/medien/audio/", $files, true);
	    	$files=$this->listFilesFromFolder("../../../html/medien/img/", $files, true);
	    	$files=$this->listFilesFromFolder("../../../html/medien/video/", $files, true);
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

	public function showFile($pfad, $all=false)
	{
		$type=mime_content_type($pfad);
		$type_info = explode("/", $type);
		$path_parts = pathinfo($pfad);

		if ($type_info[0]=="audio") {
	    	$html="<html>\n<head>\n<title>Audio</title>\n</head>\n<body>\n<audio autoplay controls src='".$pfad."'></audio>\n</body>\n</html>";
	    	$back="list.php?type=audio";
	    }	    	
	    elseif ($type_info[0]=="image") {
	    	$html="<html>\n<head>\n<title>Bild</title>\n<style>\nimg{\nwidth: 100%;\nheight: auto;\n}\n</style>\n</head>\n<body>\n<img src='".$pfad."'>\n</body>\n</html>";
	    	$back="list.php?type=image";
	    }	    	
	    elseif ($type_info[0]=="video") {
	    	$html="<html>\n<head>\n<title>Video</title>\n<style>\nvideo{\nwidth: 100%;\nheight: auto;\n}\n</style>\n</head>\n<body>\n<video src='".$pfad."' autoplay preload=”none” controls></video>\n</body>\n</html>";
	    	$back="list.php?type=video";
	    }
	    elseif ($type_info[0]=="text") {
	    	$html=file_get_contents($pfad);
	    	$back="list.php?type=text";
	    }
	    else {
	    	$html="<html>\n<head>\n<title>Error</title>\n</head>\n<body>\n<b>Error: </b>File not found.\n</body>\n</html>";    
	    }
	    if ($all) {
	    	return [$html, $path_parts['basename'], "list.php?type=all", $pfad];
	    }
	    else {
	    	return [$html, $path_parts['basename'], $back, $pfad];
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
}

?>