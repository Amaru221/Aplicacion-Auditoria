<?php
	require("conexionDB.php");

	session_start();
	
	if(!isset($_SESSION['rol'])){
		echo "<script type='text/javascript'> window.location = 'login.php'; alert('no has iniciado sesion!');</script>";

		//header("Location: login.php");
	}

	$edi = $_SESSION['edicion'];
	$inser = $_SESSION['insercion'];
	$visu = $_SESSION['visualizacion'];
	$entidad = $_SESSION['entidad'];

?>



<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="Bootstrap4/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./src/panel.css">
</head>
<body>

	<nav class="navbar navbar-expand-lg navbar-light bg-light">
 	<a class="navbar-brand" href="#"  onclick="redirigir();">Auditoria</a>
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


	<div class="tabla">
	<table class="table">
		<thead class="thead-dark">
			<tr>
				<th scope="col">#</th>
				<th scope="col">nombre</th>
				<th scope="col">descripcion</th>
				<th scope="col">codigo</th>
				<th scope="col">entidad Auditada</th>
				<?php
					if($edi == 1){
						echo "<th scope='col'>Borrar</th>";
						echo "<th scope='col'>Modificar</th>";
					}

				?>
		

			</tr>			
		</thead>
		<tbody>
			<?php
				$tareas = mostrarTareas($entidad);
				if($tareas!=null){
					foreach ($tareas as $tarea){
						echo "<tr id='{$tarea['id']}' ondblclick=\"verTramites(event,{$tarea['id']});\"><td>{$tarea['id']}</td><td>{$tarea['nombre']}</td><td>{$tarea['descripcion']}</td><td>{$tarea['codigo']}</td><td>{$tarea['entidadauditada']}</td>";

						if($edi == 1)
						{
							echo "<td><button class='btn btn-secondary' onclick=\"borrarTarea({$tarea['id']});\">Borrar</button></td>";
							echo "<td><button class='btn btn-secondary' onclick=\"modificarTarea({$tarea['id']});\">Modificar</button></td>";
						}
					}

					echo "</tr>";
				}
			?>
			
		</tbody>
		
	</table>

	</div>

</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="Bootstrap4/js/bootstrap.min.js"></script>
<script type="text/javascript">
	function redirigir(){
		window.location.href = "panelAdministrador.php";
	}

	function verTramites(event, id){
		let fila = id;
		window.location.href = "verTramite.php?id="+fila;

	}

	function borrarTarea(id){

		let confirmacion = alerta();
		if(confirmacion == true)
		{
			$.ajax({
                    type: "POST",
                    url: "http://localhost/practicaAuditoria/borrarTarea.php",
                    data: {
                        id: id
                    }
                }).done(function(msg){
                	
                		alert(msg);
                		window.location.reload();
                });

		}
		
	}
	function modificarTarea(id){
		window.location.href = "actualizarTarea.php?id="+id;
	}

	function cerrarSesion(){
		$.ajax({
                    type: "GET",
                    url: "http://localhost/practicaAuditoria/cerrarSesion.php"

                }).done(function(msg){

                		window.location.href = "./login.php";
                });
	}


	function alerta()
    {
	    var mensaje;
	    var opcion = confirm("Seguro que deseas eliminar la tarea?");
		return opcion;
	}
</script>
</html>