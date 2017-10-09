<?php

$a=include("get_exchange_rates.php");
$b=include("get_market_price_USD.php");

if( $a != "1" || $b != "1" ) exit;

// get json data to $data
$filename="../rawdata/market_price_USD.csv";
$fp=fopen($filename,"r");
$data1=fread($fp,filesize($filename));
fclose($fp);

$filename="../rawdata/market_exchange_USD_EUR.csv";
$fp=fopen($filename,"r");
$data2=fread($fp,filesize($filename));
fclose($fp);

$filename="../rawdata/market_price_official_EUR.csv";
$fp=fopen($filename,"r");
$data3=fread($fp,filesize($filename));
fclose($fp);

$D1=preg_split("/
/",$data1);

$D2=preg_split("/
/",$data2);

$D3=preg_split("/
/",$data3);

$DATA=[];
array_push($DATA,[ "date","price_market_USD","price_market_EUR","price_official_EUR" ]);

$j=1; $k=1;

for( $i=1; $i < count( $D1 ); $i++ ){
  $DD=preg_split("/,/",$D1[$i]);
  $Y=substr($DD[0],0,4);
  $M=substr($DD[0],5,2);
  $D=substr($DD[0],8,2);
  $T=mktime(0,0,0,$M,$D,$Y);

  do {
    $DD_2=preg_split("/,/",$D2[$j]);
    $Y_2=substr($DD_2[0],0,4);
    $M_2=substr($DD_2[0],5,2);
    $D_2=substr($DD_2[0],8,2);
    $T2=mktime(0,0,0,$M_2,$D_2,$Y_2);
    $j++;
  } while ( $T2 < $T );
  $j--;

  do {
    $DD_3=preg_split("/,/",$D3[$k]);
    $Y_3=substr($DD_3[0],0,4);
    $M_3=substr($DD_3[0],5,2);
    $D_3=substr($DD_3[0],8,2);
    $T3=mktime(0,0,0,$M_3,$D_3,$Y_3);
    $k++;
  } while ( $T3 < $T );
  $k--;

  array_push($DATA,[ $DD[0], $DD[1], $DD_2[1], $DD_3[1] ]);

}

$CSV="";

for( $i=0; $i < count( $DATA ); $i++ ){
  if( $i == 0 ){
    $CSV .= $DATA[$i][0].",".$DATA[$i][1].",".$DATA[$i][2].",".$DATA[$i][3]."
";
  } else {
    $CSV .= $DATA[$i][0].",".$DATA[$i][1].",".( $DATA[$i][1]*$DATA[$i][2] ).",".$DATA[$i][3]."
";
  }

}

$fp=fopen("../rawdata/faircoin_prices.csv","w+");
fwrite($fp,$CSV);
fclose($fp);


?>
