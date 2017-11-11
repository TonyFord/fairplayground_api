<?php

// get json data to $data
$fn="rawdata/FCLN.geo.json";
$fp=fopen($fn,"r");
$data=fread($fp,filesize($fn));
fclose($fp);

$J = json_decode($data);

echo count($J->{"features"}[0]);

?>
