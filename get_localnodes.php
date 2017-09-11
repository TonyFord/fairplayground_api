<?php

// get json data to $data
include("content_loader.php");

$J = json_decode($data);

echo json_encode( $J->{"layers"}[$LOCALNODES] );
?>
