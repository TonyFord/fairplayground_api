<?php

  $path="../rawdata/";
  if( !file_exists($path."logs.json") ){
    $JSON=Array("last_log_timestamp"=>0,"layers"=>Array(Array(),Array()));
    $fp=fopen($path."logs.json","w+");
    fwrite($fp,json_encode($JSON));
    fclose($fp);
  }

  $fp=fopen($path."logs.json","r");
  $json=fread($fp,filesize($path."logs.json"));
  fclose($fp);

  if(trim($json) == "" ){
    $JSON=Array("last_log_timestamp"=>0,"layers"=>Array(Array(),Array()));
  } else {
    $JSON=json_decode($json,true);
  }

  if($JSON["last_log_timestamp"] > time() - 24*3600) exit;

  // JSON
  // logs{layers[ {type[new|edit|removed],datum,entry,coordinates[lon,lat]}

  $fp=fopen($path."umap.openstreetmap.fr_last.json","r");
  $a = fread($fp,filesize($path."umap.openstreetmap.fr_last.json") );
  fclose($fp);


  $fp=fopen($path."umap.openstreetmap.fr.json","r");
  $b = fread($fp,filesize($path."umap.openstreetmap.fr.json") );
  fclose($fp);
  $json=$b;

  $A = json_decode($a);
  $B = json_decode($b);

  $a_id=Array(0,0);
  $b_id=Array(0,0);

  // get members layer
  $a_id[0]=-1;
  foreach( $A->{"layers"} as $k => $a ){
    if( $a->{"_storage"}->{"name"} == "Members" ) $a_id[0]=$k;
  }

  if( $a_id[0] == -1 ) exit;

  $b_id[0]=-1;
  foreach( $B->{"layers"} as $k => $b ){
    if( $b->{"_storage"}->{"name"} == "Members" ) $b_id[0]=$k;
  }

  if( $b_id[0] == -1 ) exit;

  // get local nodes layer
  $a_id[1]=-1;
  foreach( $A->{"layers"} as $k => $a ){
    if( $a->{"_storage"}->{"name"} == "Local Nodes" ) $a_id[1]=$k;
  }

  if( $a_id[1] == -1 ) exit;

  $b_id[1]=-1;
  foreach( $B->{"layers"} as $k => $b ){
    if( $b->{"_storage"}->{"name"} == "Local Nodes" ) $b_id[1]=$k;
  }

  if( $b_id[1] == -1 ) exit;

  for( $i=0; $i<2; $i++ ){
    echo $i;

    $co="";

    foreach($A->{"layers"}[$a_id[$i]]->{"features"} as $k=>$a){
      $co.="X".$a->{"geometry"}->{"coordinates"}[0]."Y".$a->{"geometry"}->{"coordinates"}[1]."Z".$k."A";
    }


    foreach($B->{"layers"}[$b_id[$i]]->{"features"} as $k=>$b){

      // search existing member by coordinates
      $c1=$b->{"geometry"}->{"coordinates"}[0];
      $c2=$b->{"geometry"}->{"coordinates"}[1];

      if( preg_match("/X".$c1."Y".$c2."Z/",$co )){

        // member exists -> check name
        // get array id old values
        $I=preg_split("/X".$c1."Y".$c2."Z/",$co);
        $II=preg_split("/A/",$I[1]);
        $id=$II[0];

        // old member found in new json -> remove it ( member deletion check )
        $co=preg_replace("/X".$c1."Y".$c2."Z".$id."A/","",$co);

        // old value
        $comp_a=$A->{"layers"}[$a_id[$i]]->{"features"}[$id]->{"properties"}->{"name"};

        // new value
        $comp_b=$b->{"properties"}->{"name"};

        if($comp_a != $comp_b){
          // members name different
          array_push($JSON["layers"][$i],Array("type"=>"edit","datum"=>time(),"entry"=>$comp_b,"entry_old"=>$comp_a));
        }

      } else {
        // member not found -> new member
        array_push($JSON["layers"][$i],Array("type"=>"new","datum"=>time(),"entry"=>$b->{"properties"}->{"name"},"entry_old"=>""));
      }
    }

    if($co != ""){
      $I=preg_split("/Z/",$co);
      array_shift($I);
      while(count($I)>0){
        $II=preg_split("/A/",array_shift($I));
        $id=$II[0];
        array_push($JSON["layers"][$i],Array("type"=>"removed","datum"=>time(),"entry"=>$A->{"layers"}[$a_id[$i]]->{"features"}[$id]->{"properties"}->{"name"},"entry_old"=>""));
      }

    }
  }


  $fp=fopen($path."logs.json","w+");
  fwrite($fp,json_encode($JSON));
  fclose($fp);

  // overwrite old json file with new one
  $fp=fopen($path."umap.openstreetmap.fr_last.json","w+");
  fwrite($fp,$json);
  fclose($fp);
?>
