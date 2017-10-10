# FairPlayGround API
Get all data of FairPlayGround by API

http://api.fairplayground.info/...

## Usage

### php ( server side )####

    // load JSON or CSV by fopen
    $filename="http://api.fairplayground.info/...";
    $fopen( $filename ,"r");
    $data=fread($filename,filesize($filename));
    fclose($fp);

    // load JSON or CSV by curl
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $filename );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);

    // ###### json part ########
    $J=json_decode($data);
    var_dump($J);

    // ###### csv part #########
    $C=preg_split("/
    /", $data);

    for( $i=0; $i<count($C); $i++ ){
      $CC=preg_split("/,/",$C[$i]);
      var_dump($CC);
    }

### javascript & jquery ( client side ) ###

    var filename = "http://api.fairplayground.info/...";

    // don't forget to set the type json OR csv
    var J = json_load( filename, "json|csv" );


    // #### iteration of array #######
    J.forEach(
      function( v,i ){
        console.log( v, i );
      }
    );


    function json_load( url, type ){
      var json = null;
      $.ajax({
          'type':"GET",
          'async': false,
          'global': false,
          'cache': false,
          'url': url,
          'dataType': type,
          'success': function (data) {
              json = data;
          }
      });
      if( type == "csv" ){
        var json = json.split(/
      /g);
      }

      return json;
    }

## Parameters



### get_members_count.php
http://api.fairplayground.info/get_members_count.php

get count of all members


### get_members.php
http://api.fairplayground.info/rawdata/MB_map_data.json

get all members ( json )



### get_localnodes_count.php
http://api.fairplayground.info/get_localnodes_count.php

get count of all local nodes


### get_localnodes.php
http://api.fairplayground.info/rawdata/LN_map_data.json

get all localnodes ( json )
