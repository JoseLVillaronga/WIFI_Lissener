<?php
require_once 'config.php';
$search=$_GET['search'];
$query="SELECT * FROM teccam.wifi_5 WHERE w2_fecha > (NOW() - INTERVAL 1 MINUTE) AND address IS NOT NULL";
If(!empty($search)){$query.=" AND w2_id IN (SELECT w2_id FROM teccam.wifi_5 WHERE address LIKE '%".$search."%' OR ssid LIKE '%".$search."%')";}
$query.=" ORDER BY w2_id DESC LIMIT 700";
$res=Db::listar($query);
$res=json_encode($res);
echo $res;