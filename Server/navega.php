<?php
require_once "config.php";
$API=new Routeros_Api();
$API->connect('10.10.1.1', 'admin', 'teccammt0810');
$API->write('/ip/route/print');
$READ=$API->read(false);
$ARRAY=$API->parse_response($READ);
$API->disconnect();
$habilita=array();
$deshabilita=array();
foreach ($ARRAY as $key => $value) {
	if($value['gateway']=="10.10.1.101"){
		$habilita[]=$key;
	}elseif(in_array($value['gateway'], array("10.10.1.102","10.10.1.103","10.10.1.104"))){
		$deshabilita[]=$key;
	}
}
//$habilita=implode(",", $habilita);
//$deshabilita=implode(",", $deshabilita);
$API=new Routeros_Api();
$API->connect('10.10.1.1', 'admin', 'teccammt0810');
foreach ($habilita as $key3 => $value3) {
$API->write("/ip/route/enable
=numbers=".$value3);
}
foreach ($deshabilita as $key2 => $value2) {
$API->write("/ip/route/disable
=numbers=".$value2);
}
$READ=$API->read(false);
$ARRAY2=$API->parse_response($READ);
$API->disconnect();
echo "Ok";
/*
echo "<pre>";
print_r($ARRAY);
echo "<br>";
echo $habilita."<br>".$deshabilita;
echo "</pre>";
*/