<?php
		
	$ruta = $_GET['ruta'];
	//$ruta = "_Ejercicios PHP 8. Ajax.pdf";
	if(file_exists($ruta)){
		header('Content-Description: FileTransfer');
		header('Content-Type: text/csv');
		header("Content-Disposition: attachment; filename=$ruta");
		ob_clean();
		flush();
		readfile($ruta);
		echo $ruta;
		exit;

	}
	


?>