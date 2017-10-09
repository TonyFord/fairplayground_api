<?php
  $filename="../rawdata/market_exchange_USD_EUR.csv";

  $fp=fopen($filename,"r");
  $J = fread($fp,filesize($filename));
  fclose($fp);

  $JJ=preg_split("/
/",$J);

  $JJJ=array_pop($JJ);

  while( trim( $JJJ ) == "" ){
   $JJJ = array_pop($JJ);
  }
  array_push($JJ, $JJJ);

  $JJJJ=preg_split("/,/", $JJJ);

  $last_date = $JJJJ[0];
  $current_date = date("Y-m-d",time());

  if( $last_date != $current_date ) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://apiv2.bitcoinaverage.com/constants/exchangerates/local');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    $ER=json_decode($data);

    $tim =  substr( $ER->{"time"}, 0,10 );
    $rate = $ER->{"rates"}->{"EUR"}->{"rate"};

    array_push($JJ, $tim.",".$rate );

    $J = join("
", $JJ);

    $fp=fopen($filename,"w+");
    fwrite($fp, $J );
    fclose($fp);

  } else {
    echo "1";
  }


?>
