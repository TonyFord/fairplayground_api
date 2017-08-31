<?php

$filename="rawdata/umap.openstreetmap.fr.json";
if(file_exists($filename)){

  if( filemtime($filename) < time() - 3600*24 ){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://raw.githubusercontent.com/TonyFord/fairplayground_map/master/umap.openstreetmap.fr.json');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);

    $fp=fopen($filename,"w+");
    fwrite($fp,$data);
    fclose($fp);
  } else {
    $fp=fopen($filename,"r");
    $data = fread($fp,filesize($filename));
    fclose($fp);
  }
} else {
  $fp=fopen($filename,"w+");
  fwrite($fp,"");
  fclose($fp);
}

?>
