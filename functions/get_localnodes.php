<?php

// get json data to $data
$filename="../rawdata/LN_map_data.json";
$fp=fopen($filename,"r");
$data=fread($fp,filesize($filename));
fclose($fp);

$J=json_decode($data,true);
$A=Array();
for( $i=0; $i < count($J); $i++ ){
  array_push( $A, $J[$i]["properties"]["establishing_assembly"] );
}

sort($A);

$B=Array();

$last=""; $cnt=0; $cnt_all=0;

for( $i=0; $i < count($A); $i++ ){
  if( $last == "" ) $last = $A[$i];
  if( $last != $A[$i] ){
    array_push( $B, Array( $last, $cnt, $cnt_all ) );
    $cnt=0;
    $last=$A[$i];
  }
  $cnt++;
  $cnt_all++;

}

array_push( $B, Array( $last, $cnt, $cnt_all ) );

$CSV="date,created,cumulated
";
for( $i=0; $i < count($B); $i++ ){
  $CSV.=$B[$i][0].",".$B[$i][1].",".$B[$i][2]."
";
}

$fp=fopen("../rawdata/localnodes_growth.csv","w+");
fwrite($fp,$CSV);
fclose($fp);

?>
