<?php
  $filename=$_GET["id"];
  $fp=fopen("test.txt","w+");
  fwrite($fp,$filename);
  fclose($fp);
?>
