<?php

// get json data to $data
include("content_layer_ids.php");

$J = json_decode($data);

echo count( $J->{"layers"}[$MEMBERS]->{"features"} );
?>
