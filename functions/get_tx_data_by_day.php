<?php

$CSV="";

echo "<textarea cols=100 rows=40>";
$CSV.="date;tx_count;tx_amount
";

$JSON=Array();


$fp=fopen("../rawdata/last_update_blockheight.csv","r");
$bh=fread($fp,12);
fclose($fp);

for( $i=$bh; $i>300; $i-=100 ){

  $filename="../rawdata/block_".$i.".html";

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

    // get timestamp
    $AA=preg_split("/<td>/",$a);
    $AAA=preg_split("/</",$AA[1]);

    $dt=substr( trim( $AAA[0]), 0, 10 );


    if($last_date != $dt ){
      if($last_date != ""){
        array_push($JSON, Array("date"=>$last_date,"tx_count"=>$tx_count,"tx_amount"=>$tx_amount));
        $CSV.=$last_date.";".$tx_count.";".$tx_amount."
";
      }
      $last_date=substr( trim( $AAA[0]), 0, 10 );

      $tx_count=0;
      $tx_amount=0;
    }

    // get tx count
    $AA=preg_split("/<td>/",$a);
    $AAA=preg_split("/</",$AA[2]);
    $tx_count += $AAA[0];

    // get tx amount
    $AA=preg_split("/<td>/",$a);
    $AAA=preg_split("/</",$AA[6]);
    $tx_amount += $AAA[0];

  }



}


echo $CSV;

echo "</textarea>";

$fp=fopen("../rawdata/tx_by_day.csv","w+");
fwrite($fp,$CSV);
fclose($fp);

$fp=fopen("../rawdata/tx_by_day.json","w+");
fwrite($fp,json_encode($JSON));
fclose($fp);

?>
