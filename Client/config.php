<?php
header("Content-Type: text/html; charset=utf-8");
ini_set('max_execution_time', 3600);
ini_set('max_input_time', -1);
ini_set('memory_limit', '-1');
ini_set("session.cookie_lifetime",43200);
ini_set("session.gc_maxlifetime",43200);
ini_set('session.cache_expire', 43200);
date_default_timezone_set('America/Argentina/Buenos_Aires');
//session_cache_limiter('public');
session_start();

function miAutoCargador($nombreClase){
	require_once ("clases/" . $nombreClase . ".php");
}
spl_autoload_register('miAutoCargador');