<?php


// map JSON data
$F = glob("../rawdata/stargate_entries/STGT/*.json");

$JSN=Array();

foreach( $F as $f ){

  $fp=fopen($f,"r");
  $J=json_decode( fread( $fp,filesize($f) ),true);
  fclose($fp);
  array_push($JSN, $J);
}

$fp=fopen("../rawdata/STGT.geo.json","w+");
fwrite( $fp, "{ \"type\": \"FeatureCollection\", \"features\": [".json_encode($JSN)."] }");
fclose($fp);

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
