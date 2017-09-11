<?php

// get json data to $data
include("content_layer_ids.php");

$co="";
$countries=0;
foreach( $J->{"layers"}[$MEMBERS]->{"features"} as $a ){
  $A=preg_split( "/Member /",$a->{"properties"}->{"name"});
  $AA=preg_split( "/ /",$A[1]);
  if( ! preg_match("/x".$AA[0]."x/",$co) ){
    $co.="x".$AA[0]."x";
    $countries++;
  }
}

echo $countries;

?>
