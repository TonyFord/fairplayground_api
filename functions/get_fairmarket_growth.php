<?php

function getFairmarket_growth(){

  $url='https://market.fair.coop/stats';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url );
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

  $param["data"]["name"]="Jane";
  $param["data"]["email"]="jane.doe@gmail.com";

  curl_setopt($ch, CURLOPT_POST,true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $param ) );


  $data = curl_exec($ch);
  curl_close($ch);

  echo $data;

  return $data;
}

$filename="../rawdata/fairmarket_growth.csv";

$fp=fopen($filename,"r");
$a = fread($fp,filesize($filename));
fclose($fp);

$A=preg_split("/
/",$a);

while( $A[count($A)-1] == "" ) {
  array_pop($A);
}

$B=preg_split("/,/",$A[count($A)-1]);

$current_timestamp=mktime( 0,0,0,intVal(substr($B[0],5,2)),intVal(substr($B[0],8,2)),intVal(substr($B[0],0,4))) ;

if( date("Y-m-d",$current_timestamp) != date("Y-m-d",time()) ){

  $JJ = json_decode( getFairmarket_growth(), true );

  $J = json_decode( $JJ["result"], true );

  array_push($A, date("Y-m-d",time()).",".$J["params"]["purchases"].",".$J["params"]["shops"].",".$J["params"]["products"]);

  $a=join("
",$A);

  $fp=fopen($filename,"w+");
  fwrite($fp,$a);
  fclose($fp);

}


?>
