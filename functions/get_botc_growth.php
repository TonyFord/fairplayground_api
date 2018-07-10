<?php

$f="../rawdata/botc_growth.csv";
$fp=fopen($f,"r");
$JSN=fread( $fp, filesize($f));
fclose($fp);

$JSN=preg_split("/
/",$JSN);


$A=Array( Array( "date", "cnt", "cnt_cum", "membership", "payment_method","shares" ) );
$cnt=0; $cnt_cum=0;

foreach( $JSN as $jsn ){
  $J=preg_split("/,/",$jsn);
  if($J[0] == "" ) continue;
  if( $last == "" ){
    $last = $J[0];
    $lastA= $J;
  }
  if( $last != $J[0] ){
    array_push( $A, Array($last, $cnt, $cnt_cum, $lastA[1], $lastA[2], $lastA[3] ) );
    $last = $J[0]; $lastA= $J;
    $cnt=1;
    $cnt_cum++;
  } else {
    $cnt++; $cnt_cum++;
  }

}
array_push( $A, Array($last ,$cnt, $cnt_cum, $lastA[1], $lastA[2], $lastA[3] ) );

//var_dump($A);

$CSV="";
foreach($A as $a){
  $CSV.=join(",",$a)."
";
}

echo $CSV;


$fp=fopen("../rawdata/botc_growth_calc.csv","w+");
fwrite($fp,$CSV);
fclose($fp);



?>
