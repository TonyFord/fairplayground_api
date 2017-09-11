<?php

  include("content_loader.php");
  $J = json_decode($data);

  // get members layer
  $MEMBERS=-1;
  foreach( $J->{"layers"} as $k => $a ){
    if( $a->{"_storage"}->{"name"} == "Members" ) $MEMBERS=$k;
  }

  if( $MEMBERS == -1 ) exit;

  // get local nodes layer
  $LOCALNODES=-1;
  foreach( $J->{"layers"} as $k => $a ){
    if( $a->{"_storage"}->{"name"} == "Local Nodes" ) $LOCALNODES=$k;
  }

  if( $LOCALNODES == -1 ) exit;

?>
