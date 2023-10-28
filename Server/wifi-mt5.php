<?php
require_once '/var/www/html/vendor/autoload.php';
require_once '/var/www/html/clases/Conexion.php';

error_reporting(E_ALL);

use RouterOS\Client;
use RouterOS\Query;

$client = new Client([
    'timeout' => 1,
    'host'    => 'Mikrotik IP',
    'user'    => 'admin',
    'pass'    => 'password'
]);

/*
 * Create script for automation
 */

// Wrong way (RouterOS don't understand line termination symbol)
$query =
    (new Query('/interface/wireless/scan'))
        ->equal('number','wlan2')
        ->equal('duration','14');

$ARRAY = $client->query($query)->read();
$search=":";
$replace="";
foreach ($ARRAY as $fila) {
    $fila['address']=str_replace($search,$replace,$fila['address']);
    $arr[]=array("address"=>$fila['address'],
        "ssid"=>$fila['ssid'],
        "band"=>"5ghz-n",
        "channelwidth"=>$fila['channel'],
        "freq"=>$fila['.section'],
        "sig"=>$fila['sig'],
        "nf"=>$fila['nf'],
        "snr"=>$fila['snr']
    );
}
if($arr>0){
	//$banda=array();
	$con=Conexion::conectar();
	$query="INSERT INTO `teccam`.`wifi_5`
			(`address`,
			`ssid`,
			`band`,
			`channelwidth`,
			`freq`,
			`sig`,
			`nf`,
			`snr`)
			VALUES
			(null,
			'".str_replace("'", "", preg_replace('([^A-Za-z0-9\-\_\ ])', '', $arr[0]['ssid']))."',
			'5ghz-n',
			'".$arr[0]['channelwidth']."',
			'5ghz-n',
			'".$arr[0]['sig']."',
			'".$arr[0]['nf']."',
			'".$arr[0]['snr']."')";
	foreach($arr as $fila2){
		//$banda=array();
		if(empty($fila2['ssid']) OR empty($fila2['sig']) OR empty($fila2['nf']) OR empty($fila2['snr'])){
			continue;
		}
		//$banda=explode("/", $arr[0]['channel']);
		$query.=",('".$fila2['address']."',
			'".str_replace("'", "", preg_replace('([^A-Za-z0-9\-\_\ ])', '', $fila2['ssid']))."',
			'5ghz-n',
			'".$fila2['channelwidth']."',
			'5ghz-n',
			'".$fila2['sig']."',
			'".$fila2['nf']."',
			'".$fila2['snr']."')";
	}
	$stmt=$con->prepare($query);
	$stmt->execute();
}
/*
//print_r($arr);
$res=json_encode(array_filter($arr));
//$res2=json_encode(array("uno","dos","tres","cuatro","cinco","seis","siete"));
//echo $res2;
//print_r($res);
//echo $res;
//exec("echo ".$res." > /var/www/html/wifi2.txt");
$file = fopen("/var/www/html/wifi2.txt", "w");
fwrite($file, $res);
fclose($file);
*/
print_r($ARRAY);
