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
        ->equal('number','wlan1')
        ->equal('duration','14');

$ARRAY = $client->query($query)->read();
$search=":";
$replace="";
foreach ($ARRAY as $fila) {
    $fila['address']=utf8_encode(str_replace($search,$replace,$fila['address']));
    $arr[]=array("address"=>utf8_encode($fila['address']),
        "ssid"=>utf8_encode($fila['ssid']),
        "band"=>"2ghz-n",
        "channelwidth"=>utf8_encode($fila['channel']),
        "freq"=>utf8_encode($fila['.section']),
        "sig"=>utf8_encode($fila['sig']),
        "nf"=>utf8_encode($fila['nf']),
        "snr"=>utf8_encode($fila['snr'])
    );
}
if(count($arr)>0){
	//$banda=array();
	$con=Conexion::conectar();
	$query="INSERT INTO `teccam`.`wifi_2`
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
			'2ghz-n',
			'".$arr[0]['channelwidth']."',
			'2ghz-n',
			'".$arr[0]['sig']."',
			'".$arr[0]['nf']."',
			'".$arr[0]['snr']."')";
	foreach($arr as $fila2){
		if(empty($fila2['ssid']) OR empty($fila2['sig']) OR empty($fila2['nf']) OR empty($fila2['snr'])){
			continue;
		}
		//$banda=array();
		//$banda=explode("/", $arr[0]['channel']);
		$query.=",('".$fila2['address']."',
			'".str_replace("'", "", preg_replace('([^A-Za-z0-9\-\_\ ])', '', $fila2['ssid']))."',
			'2ghz-n',
			'".$fila2['channelwidth']."',
			'2ghz-n',
			'".$fila2['sig']."',
			'".$fila2['nf']."',
			'".$fila2['snr']."')";
	}
	$stmt=$con->prepare($query);
	$stmt->execute();
	$errorSql = $stmt->errorInfo();
}
/*
$res=json_encode(array_filter($arr));
//exec("echo ".$res." > /var/www/html/wifi.txt");
$file = fopen("/var/www/html/wifi.txt", "w");
fwrite($file, $res);
fclose($file);
*/
print_r($arr);
echo "\n";
print_r($errorSql);
echo "\n";
//echo $query;
//echo "\n";
