<?php
require_once 'config.php';
$search=str_replace("'", "", preg_replace('([^A-Za-z0-9\-\_\ ])', '', str_replace("+", " ", $_GET['search'])));
$res=json_decode(file_get_contents("http://['Server IP']/wifi5.php?search=".str_replace(" ", "+", $search)),true);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>WiFi 5Ghz</title>
</head>
<body onkeydown="if (event.keyCode == 27) window.open('','_self').close();">
	<table style="border: solid 1px grey;width: 85%;margin: auto;">
		<tr style="background-color: rgba(0,0,0,.5);color: white;text-align: center;">
			<td colspan="8">
				<form method="get"><input type="text" name="search" style="width: 200px;" /> <input type="submit" value="Buscar" /></form>
			</td>
		</tr>
		<tr style="background-color: rgba(0,0,0,.5);color: white;text-align: center;">
			<td>MAC</td>
			<td>SSID</td>
			<td>Banda</td>
			<td>Ancho Canal</td>
			<td>Frecuencia</td>
			<td>Señal</td>
			<td>Piso de Ruido</td>
			<td>Relación S/R</td>
		</tr>
		<?php foreach($res as $fila){ 
		$mac=str_split($fila['address'],1);
		?>
		<tr style="background-color: rgba(0,0,0,.1);text-align: center;">
			<td><?php echo $mac[0].$mac[1].":".$mac[2].$mac[3].":".$mac[4].$mac[5].":".$mac[6].$mac[7].":".$mac[8].$mac[9].":".$mac[10].$mac[11]; ?></td>
			<td><?php echo $fila['ssid']; ?></td>
			<td><?php echo $fila['band']; ?></td>
			<td><?php echo $fila['channelwidth']; ?></td>
			<td><?php echo explode("/",$fila['channelwidth'])[0]."Mhz"; ?></td>
			<td><?php echo $fila['sig']; ?></td>
			<td><?php echo $fila['nf']; ?></td>
			<td><?php echo $fila['snr']; ?></td>
		</tr>
		<?php } ?>
	</table>
</body>
</html>
