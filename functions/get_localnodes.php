<?php

// get json data to $data
//$fn="../../datasources/FCLN/FCLN.geo.json";
//$fp=fopen($fn,"r");
//$data=fread($fp,filesize($fn));
//fclose($fp);

exit;

$A=Array();
for( $i=0; $i < count($JSN); $i++ ){
  array_push( $A, $JSN[$i]["properties"]["establishing_assembly"] );
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
