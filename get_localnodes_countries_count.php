<?php

// get json data to $data
include("content_layer_ids.php");

$J = json_decode($data);

$co="";
$countries=0;
foreach( $J->{"layers"}[$LOCALNODES]->{"features"} as $a ){
  $A=preg_split( "/Local Node /",$a->{"properties"}->{"name"});
  $AA=preg_split( "/ /",$A[1]);
  if( ! preg_match("/x".$AA[0]."x/",$co) ){
    $co.="x".$AA[0]."x";
    $countries++;
  }
}

echo $countries;

?>
