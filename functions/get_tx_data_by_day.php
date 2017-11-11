<?php


$bh = 30690 + ( time() - 1505926037 ) / 180;
$bh = intval( $bh/100 ) * 100;


$CSV="";

echo "<textarea cols=100 rows=40>";
$CSV.="date;tx_count;tx_amount,tx_count_ex
";

$JSON=Array();


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

    // get timestamp
    $AA=preg_split("/<td>/",$a);
    $AAA=preg_split("/</",$AA[1]);

    $dt=substr( trim( $AAA[0]), 0, 10 );



    if($last_date != $dt ){
      if($last_date != ""){

        array_push($JSON, Array("date"=>$last_date,"blocks"=>$blocks, "tx_count"=>$tx_count,"tx_amount"=>$tx_amount,"tx_count_ex"=>( $tx_count-$blocks )));


      }
      $last_date=substr( trim( $AAA[0]), 0, 10 );

      $tx_count=0;
      $tx_amount=0;
      $blocks=0;
    }

    // get blocks
    $blocks++;

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

$JSON=array_reverse($JSON);
array_pop($JSON);
$TX_COUNT_AVG=Array();

for( $i=0; $i<count($JSON); $i++ ){
  array_push( $TX_COUNT_AVG, $JSON[$i]["tx_count_ex"] );
  if( count($TX_COUNT_AVG) > 6 ){
    array_shift( $TX_COUNT_AVG );
  }
  $tx_count_ex_avg = round( array_sum($TX_COUNT_AVG) / count($TX_COUNT_AVG) );
  $CSV.=$JSON[$i]["date"].";".$JSON[$i]["tx_count"].";".$JSON[$i]["tx_amount"].";".$JSON[$i]["tx_count_ex"].";".$tx_count_ex_avg."
";
  $JSON[$i]["tx_count_ex_avg"] = $tx_count_ex_avg;
}

echo $CSV;

echo "</textarea>";

$fp=fopen("../rawdata/tx_by_day.csv","w+");
fwrite($fp,$CSV);
fclose($fp);


array_shift($JSON);

$fp=fopen("../rawdata/tx_by_day.json","w+");
fwrite($fp,json_encode($JSON));
fclose($fp);

?>
