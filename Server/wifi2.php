<?php
require_once 'config.php';
$search=str_replace("'", "", preg_replace('([^A-Za-z0-9\-\_\ ])', '', str_replace("+", " ", $_GET['search'])));
$query="SELECT * FROM teccam.wifi_2 WHERE w2_fecha > (NOW() - INTERVAL 1 MINUTE) AND address IS NOT NULL";
if(!empty($search)){$query.=" AND w2_id IN (SELECT w2_id FROM teccam.wifi_2 WHERE address LIKE '%".$search."%' OR ssid LIKE '%".$search."%')";}
$query.=" ORDER BY w2_id DESC LIMIT 700";
$res=Db::listar($query);
$res=json_encode($res);
echo $res;
