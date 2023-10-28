<?php
require_once 'config.php';
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>SPPA01 101</title>
	<link rel="shortcut icon" href="img/favicon.png">
    <link href="css/icon.css" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min2.css"  media="screen,projection"/>
    <!--Esrtilo propio-->
	<link href="css/style.css" rel="stylesheet">
    <script src="assets/js/bootstrap-collapse.js"></script>
    <script src="assets/js/bootstrap-transition.js"></script>
    <script src="assets/js/bootstrap-tab.js"></script>
    <link href="assets/js/google-code-prettify/prettify.css" rel="stylesheet">
    <link href="js/jquery-ui.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="icofont/icofont.min.css">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta id="request-method" name="request-method" content="<?php echo($_SERVER['REQUEST_METHOD']); ?>" />
    <style>
    	#content {
    		min-height: 400px;
    	}
    </style>
	<style type="text/css">
	<!--
	.Estilo1 {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
		font-weight: bold;
	}
	.Estilo2 {color: #000099}
	-->
	.menuTabla:hover {
		-moz-box-shadow: 0px 0px 9px rgba(0, 0, 0, .9);
		-webkit-box-shadow: 0px 0px 9px rgba(0, 0, 0, .9);
		box-shadow: 0px 0px 9px rgba(0, 0, 0, .9);
	}
	.menuTabla:active {
		-moz-box-shadow: 5px 5px 5px rgba(255, 0, 0, .7);
		-webkit-box-shadow: 5px 5px 5px rgba(255, 0, 0, .7);
		box-shadow: 5px 5px 5px rgba(255, 0, 0, .7);
	}
	.boton-celda:hover {
	    -moz-box-shadow: 0px 1px 19px #000000;
	    -webkit-box-shadow: 0px 1px 19px #000000;
	    -ms-box-shadow: 0px 1px 19px #000000;
	    box-shadow: 0px 1px 19px #000000;
	}
	.boton-celda:active {
	    -moz-box-shadow:inset 0px 1px 19px #000000;
	    -webkit-box-shadow:inset 0px 1px 19px #000000;
	    -ms-box-shadow:inset 0px 1px 19px #000000;
	    box-shadow:inset 0px 1px 19px #000000;
	    background-color: #7F7F7F;
	}
	</style>
</head>
<body class="container" style="padding: 0;">
	<h4 style="background-color: rgba(0,0,255,.1);"> SPPA01 - 101</h4>
	<div class="row" style="width: 98%">
		<div class="col s6 l6 m6" style="min-height: 300px;text-align: center;padding: 0">
			<a class="btn waves-effect waves-light">Nueva Prueba</a>
			<a class="btn waves-effect waves-light" onclick="location.target = 'nomuestra';location.href = 'http://127.0.0.1/navega.php';">Manual</a><br>
			<a class="btn waves-effect waves-light" onclick="location.target = 'nomuestra';location.href = 'http://127.0.0.1/no-nav.php';">Cancel Manual</a>
		</div>
		<div class="col s6 l6 m6" style="background-color: rgba(0,0,0,.1);min-height: 300px;">
			<h5 style="text-align: center;">Prueba Anterior</h5>
			<p>Nro. Serie:</p>
		</div>
	</div>
</body>
</html>