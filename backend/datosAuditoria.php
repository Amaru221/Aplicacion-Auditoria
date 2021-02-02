<?php
 require('./conexionDB.php');
 if($_SERVER['REQUEST_METHOD'] == 'POST')
 {
 	if($_POST['selectedOption'] != "Elige una auditoria"){
 		$option = $_POST['selectedOption'];
 		echo json_encode(devolverAuditoria($option));
 	}else{
 		echo 'null';
 	}
 }
 
?>