<?php
	require("conexionDB.php");

	session_start();
	
	if(!isset($_SESSION['rol'])){
		echo "<script type='text/javascript'> window.location = 'login.php'; alert('no has iniciado sesion!');</script>";

		//header("Location: login.php");
	}


	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		if(isset($_POST['actualizar']))
		{
	
			$idDocumento = $_REQUEST['id'];
			$nombre = $_POST['nombre'];
			$descripcion = $_POST['desc'];
			$codigo = $_POST['codigo'];

			echo modificarDocumento($idDocumento, $nombre, $descripcion, $codigo);
			

		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="Bootstrap4/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./src/crearTarea.css">
</head>
<body>

	<nav class="navbar navbar-expand-lg navbar-light bg-light">
 	<a class="navbar-brand" href="#" onclick="redirigir();">Auditoria</a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  </button>

	  <div class="collapse navbar-collapse" id="navbarSupportedContent">
	    <ul class="navbar-nav mr-auto">
	      <li class="nav-item">
	        <a id="gestor"class="nav-link active" href="#">Gestor documental</a>
	      </li>
	    </ul>
	  </div>

	   <a class="navbar-brand" href="./login.php" onclick="cerrarSesion();">
	    <img src="./src/logout.svg" width="30" height="30" class="d-inline-block align-top" alt="">
	    Cerrar sesion
	  </a>
	</nav>


		<form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
			<!-- insertar tarea -->
		<div id = "cajaFormulario">

		  <div class="form-group">
		    <label for="nombre">Nombre</label>
		    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre tarea" required>
		  </div>
		  <div class="form-group">
		    <label for="descripcion">Descripcion</label>
		    <input type="text" class="form-control" name="desc" id="descripcion" placeholder="Descripcion" required>
		  </div>
		  <div class="form-group">
		    <label for="codigo">Codigo</label>
		    <input type="text" class="form-control" name="codigo" id="codigo" placeholder="Codigo" required>
		  </div>

		 
		  <input type="submit" class="btn btn-primary" name="actualizar" value="Actualizar">
		</div>
	</form>

</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="Bootstrap4/js/bootstrap.min.js"></script>
<script type="text/javascript">
	function redirigir(){
		window.location.href = "panelAdministrador.php";
	}

		function cerrarSesion(){
		$.ajax({
                    type: "GET",
                    url: "http://localhost/practicaAuditoria/cerrarSesion.php",
                    data: {
         			
                    }

                }).done(function(msg){
                		alert(msg);
                		window.location.href = "./login.php";
                });
	}
</script>
</html>