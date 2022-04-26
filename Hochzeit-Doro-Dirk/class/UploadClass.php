<?php 
/**
 * UploadClass
 */
class UploadClass
{
	private function createFileName($oldFileName) {
		$pfad="./upload/";
		$allFiles=[];
		if ($handle = opendir($pfad)){
			while (($file = readdir($handle)) !== false){
				$extension = substr($file, strrpos($file, '.') + 1);
				if (filetype( $file )=="dir") {
				  continue;
				}
				$allFiles[]=[
					'name' => $file,
				];  
			}
			closedir($handle);
		}

		$fileKey=00000;

		if (empty($allFiles)) {
			$fileKey=sprintf("%'.05d\n", $fileKey);
		}

		foreach ($allFiles as $file) {
			$key=substr($file, 0, 5);
			if ($fileKey=$key) {
				$fileKey=sprintf("%'.05d\n", $fileKey+1);
			} else {
				$fileKey=sprintf("%'.05d\n", $fileKey);
			}
		}
		echo $fileKey."_".$oldFileName;;
		return $fileKey."_".$oldFileName;
	}
	
	public function loadFileUp($data) {
		$fileName = $_FILES['file']['name'];
	    $tmpName = $_FILES['file']['tmp_name'];
	    if ($fileName=="" || $tmpName=="") {
    		return false;
    		die();
    	}
    	$type=$_FILES["file"]["type"];
    	$size=$_FILES["file"]["size"];
    	$type_info = explode("/", $type);

    	if ($type_info[0]=="image") {
    		$pfad="./upload/".$this->createFileName($fileName);
    	}	    		    	
		else {
			return $type;
			die();
		}
		if (move_uploaded_file($tmpName, $pfad)){
			return [$fileName, $tmpName, $type, $size, $type_info];
		}
	}
}

?>