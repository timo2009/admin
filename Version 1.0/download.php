<?php

$pfad =$_GET['pfad'];
if(file_exists($pfad)) {
  header("Content-Disposition: attachment; filename={$pfad}");
  header('Content-length: '. filesize($pfad));
  header('Cache-Control: no-cache');
  header('Content-Transfer-Encoding: chunked'); 
  readfile($pfad);
  exit;
}

?>