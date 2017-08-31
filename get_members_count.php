<?php

// get json data to $data
include("content_loader.php");

$J = json_decode($data);

echo count( $J->{"layers"}[1]->{"features"} );
?>
