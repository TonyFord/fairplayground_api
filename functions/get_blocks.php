<?php

$bh = 30690 + ( time() - 1505926037 ) / 180;
$bh = intval( $bh/100 ) * 100;

$filename="../rawdata/blocks/block_".( $bh-400 ).".html";
if( file_exists($filename) ) exit;

$CSV="";
$JSON=Array();

echo "<textarea cols=100 rows=40>";

$CSV.="block;timestamp;tx_count;tx_amount
";

//$fp=fopen("../rawdata/last_update_blockheight.csv","r");
//$bh=intval( fread($fp,12) );
//fclose($fp);

for( $i=$bh; $i>300; $i-=100 ){

  $filename="../rawdata/blocks/block_".$i.".html";

  if(file_exists($filename)){

    $fp=fopen($filename,"r");
    $data = fread($fp,filesize($filename));
    fclose($fp);

  } else {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://chain.fair.to/?height='.$i.'&num=100');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);

    $fp=fopen($filename,"w+");
    fwrite($fp,$data);
    fclose($fp);

  }


  $A=preg_split("/<a href=\"block?/", $data);

  array_shift($A);


  foreach($A as $a){

    $J=Array();

    // get BlockNo
    $AA=preg_split("/>/",$a);
    $AAA=preg_split("/</",$AA[1]);
    $CSV.=trim( $AAA[0] ).";";
    array_push($J,Array("block"=>trim( $AAA[0] )));

    // get timestamp
    $AA=preg_split("/<td>/",$a);
    $AAA=preg_split("/</",$AA[1]);
    $CSV.=trim( $AAA[0] ).";";
    array_push($J,Array("timestamp"=>trim( $AAA[0] )));

    // get tx count
    $AA=preg_split("/<td>/",$a);
    $AAA=preg_split("/</",$AA[2]);
    $CSV.=trim( $AAA[0] ).";";
    array_push($J,Array("tx_count"=>trim( $AAA[0] )));

    // get tx amount
    $AA=preg_split("/<td>/",$a);
    $AAA=preg_split("/</",$AA[6]);
    $CSV.=trim( $AAA[0] )."
";
    array_push($J,Array("tx_amount"=>trim( $AAA[0] )));

    array_push($JSON,$J);
  }
}


echo $CSV;

echo "</textarea>";


$fp=fopen("../rawdata/blocks.csv","w+");
fwrite($fp,$CSV);
fclose($fp);

$fp=fopen("../rawdata/blocks.json","w+");
fwrite($fp,json_encode( $JSON ));
fclose($fp);

include("get_tx_data_by_day.php");

?>
