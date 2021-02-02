<?php
 require('./conexionDB.php');
 if($_SERVER['REQUEST_METHOD'] == 'POST')
 {
 	$user = $_POST['selectedUser'];
 	$edi = $_POST['editar'];
 	$insert = $_POST['insertar'];
 	$visu = $_POST['visualizar'];

 	echo actualizarUser($user, $edi, $insert, $visu);
 }
 
?>