<?php
	require("conexionDB.php");
	session_start();
	if(!isset($_SESSION['rol'])){
		echo "<script type='text/javascript'> window.location = 'login.php'; alert('no has iniciado sesion!');</script>";

		//header("Location: login.php");
	}

	$id =$_REQUEST['id'];
	$ruta = $_REQUEST['ruta'];
	unlink($ruta);
	echo borrarDocumento($id);
?>