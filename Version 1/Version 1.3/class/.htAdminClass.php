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
	    	if ($fileName=="" || $tmpName=="") {
	    		return false;
	    		die();
	    	}
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
	    	elseif ($type_info[1]=="pdf") {
	    		$pfad="../../../html/".$fileName;
	    	}	    	
    		else {
    			return $type;
    			die();
    		}
	    	$userpfad=substr($pfad, 8);

    		if (move_uploaded_file($tmpName, $pfad)){
    			return [$fileName, $tmpName, $type, $size, $userpfad, $type_info];
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
						'pfad' => htAdminClass . phpsubstr($pfad, 8) . $file,
						'deleteLink' => "unset.php?file=".$pfad.$file,
						'showLink' => "show.php?file=".$pfad.$file
					];
				}
				else {
					$allFiles[]=[
						'name' => $file,
						'pfad' => htAdminClass . phpsubstr($pfad, 8) . $file,
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
		$text=false;

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
	    elseif ($type_info[1]=="plain") {
	    	$html="<html>\n<head>\n<title>Text</title>\n</head>\n<body>\n<pre>".file_get_contents($pfad)."</pre>\n</body>\n</html>";
	    	$back="list.php?type=text";
	    	$text=true;
	    }	    
	    elseif ($type_info[1]=="pdf") {
	    	$html="<html>\n<head>\n<title>PDF</title>\n</head>\n<body>\n<object data='".$pfad."' style='width:100%;height:10000px'>\n</body>\n</html>";
	    	$back="list.php?type=text";
	    }
	    elseif ($type_info[0]=="text" || $type_info[1]=="x-empty") {
	    	$html=file_get_contents($pfad);
	    	$back="list.php?type=text";
	    	$text=true;
	    }
	    else {
	    	$html="<html>\n<head>\n<title>Error</title>\n</head>\n<body>\n<b>Error: </b>File not found.\n</body>\n</html>";    
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
		$pfad="../../../html/".$name;
		$htmlG="<!DOCTYPE html>\n<html>\n<head>\n<meta charset='utf-8'>\n<meta name='viewport' content='width=device-width, initial-scale=1'>\n<title>HTML Datei</title>\n</head>\n<body>\n\n</body>\n</html>";
		if ($art=="html-g") {
			$pfad=$pfad.".html";
			if (file_exists($pfad)) {
				return false;
			}
			file_put_contents($pfad, $htmlG);
			return true;
		}
		elseif ($art=="html") {
			$pfad=$pfad.".html";
			if (file_exists($pfad)) {
				return false;
			}
			file_put_contents($pfad, "");
			return true;
		}
		elseif ($art=="txt") {
			$pfad=$pfad.".txt";
			if (file_exists($pfad)) {
				return false;
			}
			file_put_contents($pfad, "");
			return true;
		}
	}
}

?>