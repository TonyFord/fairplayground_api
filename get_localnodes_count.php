<?php

// get json data to $data
$fp=fopen("rawdata/LN_map_data.json","r");
$data=fread($fp,filesize("rawdata/LN_map_data.json"));
fclose($fp);

$J = json_decode($data);

echo count($J);

?>
