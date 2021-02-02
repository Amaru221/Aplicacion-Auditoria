<?php
	require('conexionDB.php');

	session_start();


	if(!isset($_SESSION['rol'])){
		echo "<script type='text/javascript'> window.location = 'login.php'; alert('no has iniciado sesion!');</script>";

	}

	$inser = $_SESSION['insercion'];
	$visu = $_SESSION['visualizacion'];
	$entidadUser = $_SESSION['entidad'];

	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		if(isset($_POST['insertar']))
		{
			$nombre = $_POST['nombre'];
			$apellidos = $_POST['apellidos'];
			$pass = $_POST['pass'];
			$rol = $_POST['rolinput'];
			$auditora = $_POST['entidadinput'];
			
			$checkedi = null;
			$checkinser = null;
			$checkvisu = null;

			if(isset($_POST['checkinser'])){
				$checkinser = $_POST['checkinser'];

			}

			if(isset($_POST['checkedi'])){
				$checkedi = $_POST['checkedi'];

			}

			if(isset($_POST['checkvisu'])){
				$checkvisu = $_POST['checkvisu'];

			}


			$checkedi == null? $checkedi = 0 : $checkedi = 1;
			$checkvisu == null? $checkvisu = 0 : $checkvisu = 1;
			$checkinser == null? $checkinser = 0 : $checkinser = 1;
			

			echo insertarUser($nombre, $apellidos,$pass, $rol,$auditora, $checkedi, $checkinser, $checkvisu);

		}
		if(isset($_POST['crearTarea'])){
			header("Location: crearTarea.php");
		}

		if(isset($_POST['verTarea'])){
			header("Location: verTarea.php");
		}

		if(isset($_POST['crearTramite'])){
			header("Location: crearTramite.php");
		}
		if(isset($_POST['consultarDocumentos']))
		{
			header("Location: consultarDocumentos.php");
		}

		if(isset($_POST['tareasearch']))
		{
			$idtarea = $_POST['selectarea'];
			header("Location: consultarDocumentosTarea.php?id=$idtarea");
		}

		if(isset($_POST['tramitesearch']))
		{

			$idtramite = $_POST['selectramite'];
			header("Location: consultarDocumentosTramite.php?id=$idtramite");
		}



	}

	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Panel de administracion</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="Bootstrap4/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./src/panel.css">
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
 	<a class="navbar-brand" href="#">Auditoria</a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">

    	<?php
    		$entraRol = $_SESSION['rol'];
    		if($entraRol == "administrador"){
    			echo '<li class="nav-item">
    					<a id="alta" class="nav-link" href="#" onclick=\'verPestania(event, "active")\';">Alta Usuario <span class="sr-only">(current)</span></a>
    				</li>';
    		}
    	?>


      <li class="nav-item">
        <a id="gestor" class="nav-link active" href="#" onclick="verPestania(event, 'active');">Gestor documental</a>
      </li>
      <li class="nav-item">
        <a id="ejecucion" class="nav-link" href="#" onclick="verPestania(event, 'active');">Ejecución</a>
      </li>
    </ul>
  </div>

  <a class="navbar-brand" href="./login.php" onclick="cerrarSesion();">
    <img src="./src/logout.svg" width="30" height="30" class="d-inline-block align-top" alt="">
    Cerrar sesion
  </a>
</nav>
<!-- **********************************************************-->
	<form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
					  
			<!-- alta usuario -->
		<div id ="caja_alta" class="caja_alta caja visible">
			<div id = "cajaFormulario">
			  <div class="form-group row">
			    <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" required>
			    </div>
			  </div>

			  <div class="form-group row">
			    <label for="apellidos" class="col-sm-2 col-form-label">Apellidos</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" name="apellidos" id="apellidos" placeholder="apellidos" required>
			    </div>
			  </div>

			  <div class="form-group row">
			  	<label for="password" class="col-sm-2 col-form-label">Contraseña</label>
			    <div class="col-sm-10">
			      <input type="password" class="form-control" name="pass" id="password" placeholder="Contraseña" required>			  	
			    </div>
			  </div>

			  <label class="col-sm-2 col-form-label">Permisos:</label>
			  <div class="form-check form-check-inline">
				  <input name="checkedi" class="form-check-input" type="checkbox" id="inlineCheckbox1" value="edicion">
				  <label class="form-check-label" for="inlineCheckbox1">edicion</label>
				</div>
				<div class="form-check form-check-inline">
				  <input name="checkinser" class="form-check-input" type="checkbox" id="inlineCheckbox2" value="insercion">
				  <label class="form-check-label" for="inlineCheckbox2">insercion</label>
				</div>
				<div class="form-check form-check-inline">
				  <input name="checkvisu" class="form-check-input" type="checkbox" id="inlineCheckbox3" value="visualizacion">
				  <label class="form-check-label" for="inlineCheckbox3">visualizacion</label>
				</div>

			  <div class="input-group mb-3">
				  <div class="input-group-prepend">
				    <label class="input-group-text" for="inputGroupSelect01">Nivel de acceso (rol)</label>
				  </div>
				  <select name="rolinput" class="custom-select" id="inputGroupSelect01">
				    <option value="auditada" selected>Usuario de entidad auditada</option>
				    <option value="auditora">Usuario de empresa auditora</option>
				    <option value="interventores">Usuario interventor</option>
				    <option value="administrador">Usuario super-administrador</option>
				  </select>			  
			  </div>
			  

			   <div class="input-group mb-3">
				  <div class="input-group-prepend">
				    <label class="input-group-text" for="inputGroupSelect04">Entidad Auditora</label>
				  </div>


			  		<select name="entidadinput" class="custom-select" id="inputGroupSelect04">
			  			
			  			<?php
			  				$array = devolverAuditora();
							if($array != null){
								for($i = 0; $i<count($array); $i+=2){
								$segundo = $i+1;
								echo "<option value='$array[$i]' selected>$array[$segundo]</option>";

								}
							}

							
			  			?>



    				</select>
    			</div>

		      <br><input type="submit" class="btn btn-secondary btn-lg btn-block" id="guardar" name="insertar" value="Guardar">
			</div>
		</div>
	</form>


	<center>
	<form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
		<!-- gestor documental-->
		<div id="caja_gestor" class="caja_gestor caja"><br>


			<div class="card text-center">
				<div class="card-header">
				    Gestion de Tareas
				</div>
				<div class="card-body">	
			 
				 
					&nbsp<input type="submit" name="verTarea" class="btn btn-secondary btn-sm" value="Ver Tareas">
					

					<?php
						if($inser == 1){
							
							echo '<input type="submit" name="crearTarea" class="btn btn-primary btn-sm" value="Crear Tarea"></div>';
							echo "<div class='card-header'> Gestion de Tramites</div>";
							echo "<div class='card-body>'";
							echo '<br><br>&nbsp<input type="submit" name="crearTramite" class="btn btn-primary btn-sm" value="Crear Tramite"></div><br>';
						}else{
							echo "</div>";
						}

					?>

					<div class="card text-center">
					  <div class="card-header">
					    Gestion de Documentos
					  </div>
					  <div class="card-body">
					  	<input type="submit" name="consultarDocumentos" class="btn btn-secondary btn-sm" value="Ver Documentos"><br>

					  	<?php
					  		if($visu == 1){

					  			echo "<h4 class = 'titulo'>Filtrar por tarea:</h4>";
    							echo '<br><select name="selectarea" class="custom-select" id="inputGroupSelect02">';
    							$arraytareas = devolverTareaEntidad($entidadUser);
								if($arraytareas != null){
									for($i = 0; $i<count($arraytareas); $i+=2){
									$segundo = $i+1;
									echo "<option value='$arraytareas[$i]' selected>$arraytareas[$segundo]</option>";

									}
								}

								echo "</select>";

    							echo '<button name="tareasearch" class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button><br><br>';


    							echo "<h4 class = 'titulo'>Filtrar por tramite:</h4>";
    							echo '<br><select name="selectramite" class="custom-select" id="inputGroupSelect03">';
    							$arraytramites = devolverTramiteEntidad($entidadUser);
								if($arraytramites != null){
									for($i = 0; $i<count($arraytramites); $i+=2){
									$segundo = $i+1;
									echo "<option value='$arraytramites[$i]' selected>$arraytramites[$segundo]</option>";

									}
								}

								echo "</select>";


    							echo '<button name="tramitesearch" class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>';
					  		}
					  	?>
					  </div>
					</div>
				
				
				</div>
			</div>
		</div>
		<!--Ejecucion auditoria-->
		<div id = "caja_ejecucion" class="caja_gestor caja" style="padding-left: 5%;padding-right: 5%; padding-top: 2%;">

			<nav class="navbar navbar-light" style="background-color: #e3f2fd;">
			  <a class="navbar-brand" href="#">Menú Ejecución</a>
			  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			    <span class="navbar-toggler-icon"></span>
			  </button>
			  <div class="collapse navbar-collapse" id="navbarNav">
			    <ul class="navbar-nav">
			      <li class="nav-item">
			        <a id="seleccionAud" class="navbar-lightnk" href="#" onclick="verPestaniaDos(event, 'active_dos')">Selección auditoria</a>
			      </li><br>
			      <li class="nav-item">
			        <a id="gestion_usuarios" class="navbar-lightnk" href="#" onclick="verPestaniaDos(event, 'active_dos')">Gestión de usuarios</a>
			      </li><br>
			      <li class="nav-item">
			        <a id="planificacion" class="navbar-lightnk" href="#" onclick="verPestaniaDos(event, 'active_dos')">Planificación</a>
			      </li><br>
			      <li class="nav-item">
			        <a id="informes" class="navbar-lightnk" href="#" onclick="verPestaniaDos(event, 'active_dos')">Informes</a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link disabled" href="#">Disabled</a>
			      </li>
			    </ul>
			  </div>
			</nav>

			<div>
				<!--cajonera seleccion auditoria-->

				<div id = "caja_seleccionAud" class="caja_dos active_dos">
					<p class="h5">Seleccion de Auditoria</p>
					<?php
						echo '<div style="padding-left: 20%;padding-right: 20%;">';
						echo '<br><select name="selectauditoria" class="custom-select" id="selectauditoria" >';
						echo "<option value='0' selected>Elige una auditoria</option>";
						//*******************************************
						$arrayAdutorias = auditoriasUser("usuario");
						for($i = 0; $i<count($arrayAdutorias); $i+=2)
						{
							$segundo = $i+1;
							echo "<option value='$arrayAdutorias[$i]'>$arrayAdutorias[$segundo]</option>";
						}
						echo "</select>";
						echo "</div>";
					?>
					<div id="mostrardatos" style="display: none;">
						<p id="nombreAuditoria">Sin auditoria</p>
						<br>
						<div>
							<table class="table">
								<thead align="center">
								<tr>
									<th scope="col" rowspan="2" width="300">Area de trabajo</th>
									<th scope="col" rowspan="2" align="center">control interno</th>
									<th scope="col" align="center" colspan="2">Poblacion</th>
									<th scope="col" colspan="5" align="center">Muestra</th>
								</tr>
								<tr>
									<th scope="col">importe</th>
									<th scope="col">tamaño poblacion</th>
									<th scope="col">importe</th>
									<th scope="col">tamaño muestral</th>
									<th scope="col">% importe</th>
									<th scope="col">% items</th>
									<th scope="col">% subarea</th>
								</tr>
								</thead>
								<tbody>
								<!--**************-->
								<tr>
									<td class="datoPrincipal">Personal</td>
									<td></td>
								</tr>
								<tr>
									<td>Retribuciones</td>
									<td></td>
								</tr>
								<tr>
									<td>Indemnizaciones</td>
									<td></td>
								</tr>
								<tr>
									<td>Incorporaciones</td>
									<td></td>
								</tr>
								<tr>
									<td>Indemnizaciones por despido o cese</td>
									<td></td>
								</tr>
								<tr>
									<td>Otras percepciones</td>
									<td></td>
								</tr>
								<!--**************-->
								<tr>
									<td class="datoPrincipal">Contratacion</td>
									<td></td>
								</tr>
								<tr>
									<td>Contratos mayores</td>
									<td></td>
								</tr>
								<tr>
									<td>Contratos menores y otros gastos</td>
									<td></td>
								</tr>
								<tr>
									<td>Contratos modificados</td>
									<td></td>
								</tr>
								<tr>
									<td>Contratos prorrogados</td>
									<td></td>
								</tr>
								<tr>
									<td>Contratos excluidos</td>
									<td></td>
								</tr>
								<!--**************-->
								<tr>
									<td class="datoPrincipal">Encargos a medios propios</td>
									<td></td>
								</tr>
								<tr>
									<td>Encargos ordenados por la entidad</td>
									<td></td>
								</tr>
								<tr>
									<td>Encargos recibidos por la entidad</td>
									<td></td>
								</tr>
								<tr>
									<td>Encomiendas de gestion ordenadas</td>
									<td></td>
								</tr>
								<tr>
									<td>Encomiendas de gestion recibidas</td>
									<td></td>
								</tr>
								<tr>
									<td>Convenios</td>
									<td></td>
								</tr>
								<!--**************-->
								<tr>
									<td class="datoPrincipal">Ayudas concevidas y subvenciones</td>
									<td></td>
								</tr>
								<tr>
									<td>Concurrencia competitiva</td>
									<td></td>
								</tr>
								<tr>
									<td>Concesion directa</td>
									<td></td>
								</tr>
								<tr>
									<td>Entregas dinerarias sin contraprestacion</td>
									<td></td>
								</tr>
								<!--**************-->
								<tr>
									<td class="datoPrincipal">Endeudamiento y otras operaciones financieras</td>
									<td></td>
								</tr>
								<tr>
									<td>Operacion de endeudamiento</td>
									<td></td>
								</tr>
								<tr>
									<td>Acuerdos con entidades financieras</td>
									<td></td>
								</tr>
								<!--**************-->
								<tr>
									<td class="datoPrincipal">Analisis de gestion: procedimientos de tesoreria</td>
									<td></td>
								</tr>
								<tr>
									<td>Cuentas bancarias</td>
									<td></td>
								</tr>
								</tbody>

							</table>
						</div>
					</div>

				</div>
				<!-- Cajonera de gestion de usuarios -->
				<div id = "caja_gestion_usuarios" class="caja_dos visible_dos">
					<p class="h5">Gestion de usuarios</p>					  
						<!-- Gestion usuarios -->
					<div style="padding-left: 20%;padding-right: 20%;">
						<select name="usuarioinput" class="custom-select" id="usuarioinput" >
				  			
				  			<?php
				  				echo "<option value='seleciona_user' selected>Selecciona un usuario</option>";
				  				$array = devolverUsuarios();
								if($array != null){
									for($i = 0; $i<count($array); $i++){
									//$segundo = $i+1;
									echo "<option value='$array[$i]'>$array[$i]</option>";

									}
								}

								
				  			?>

				  		</select>
			  		</div>

			  		<label class="col-sm-2 col-form-label">Permisos:</label>
					<div class="form-check form-check-inline">
						  <input name="checkedi" class="form-check-input" type="checkbox" id="checkeditedi" value="edicion">
						  <label class="form-check-label" for="checkeditedi">edicion</label>
					</div>
					<div class="form-check form-check-inline">
						  <input name="checkinser" class="form-check-input" type="checkbox" id="checkeditisert" value="insercion">
						  <label class="form-check-label" for="checkeditisert">insercion</label>
					</div>
					<div class="form-check form-check-inline">
						  <input name="checkvisu" class="form-check-input" type="checkbox" id="checkeditvisu" value="visualizacion">
						  <label class="form-check-label" for="checkeditvisu">visualizacion</label>
					</div>
					<br>
					<button id="editarPermisos" type="button" class="btn btn-dark">Editar</button>
				

				</div>

				<!-- Cajonera de planificacion -->
				<div id = "caja_planificacion" class="caja_dos visible_dos">
					<p class="h5">Planificacion</p>


				</div>

				<!-- Cajonera de informes -->
				<div id = "caja_informes" class="caja_dos visible_dos">
					<p class="h5">Informes</p>


				</div>
			</div>

		</div>
	</form>
	</center>

</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="Bootstrap4/js/bootstrap.min.js"></script>
<script type="text/javascript">

	//habilita las pestañas y deshabilita las demas en funcion de si son seleccionadas, a traves del atributo de clase visible
	function verPestania(event, css){
		let activas = document.getElementsByClassName('active');
		for(let i = 0; i<activas.length; i++){
			activas[i].classList.remove('active');
		}

		let activada = event.target.id;
		document.getElementById(activada).className += " "+css;

		let listaCajas = document.getElementsByClassName('caja');

		for(let i = 0; i<listaCajas.length; i++){
			if(listaCajas[i].classList[2] !== "visible"){
				listaCajas[i].className += " visible";
			}
		}

		let cambio = document.getElementById("caja_"+activada);
		console.log(document.getElementById("caja_"+activada));
		cambio.classList.remove("visible");
	}

	function accesoGestionUsuarios(){
		let rol = "<?php echo $_SESSION['rol']; ?>";
		return rol;
	}

	function verPestaniaDos(event, css){

		let rol = accesoGestionUsuarios();
		let activada = event.target.id;
		//comprobamos que el usuario sea administrador para poder configurar los permisos de los usuarios
		if(rol != "administrador" && activada == "gestion_usuarios")
		{
			alert("Su rol en la entidad no es administrador");
		}else{

			let activas = document.getElementsByClassName('active_dos');
			for(let i = 0; i<activas.length; i++){
				activas[i].classList.remove('active_dos');
			}

			
			document.getElementById(activada).className += " "+css;

			let listaCajas = document.getElementsByClassName('caja_dos');

			for(let i = 0; i<listaCajas.length; i++){
				if(listaCajas[i].classList[2] !== "visible_dos"){
					listaCajas[i].className += " visible_dos";
				}
			}

			let cambio = document.getElementById("caja_"+activada);
			console.log(document.getElementById("caja_"+activada));
			cambio.classList.remove("visible_dos");
		}

		
	}

	//funcion ajax que cierra sesión y redirige a login
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



	//añadimos evento cuando elijamos una auditoria en el menu de ejecucion
	var select = document.getElementById('selectauditoria');
	select.addEventListener('change',function(){
    	var selectedOption = this.options[select.selectedIndex];
    	var selectedAuditoria = selectedOption.text;


    			$.ajax({
                    type: "POST",
                    url: "http://localhost/practicaAuditoria/datosAuditoria.php",
                    data: {
         				selectedOption: selectedAuditoria
                    }

                }).done(function(msg){
                	//console.log(msg);
                	if(msg != "null")
                	{
                		var objeto = JSON.parse(msg);
                		console.log(objeto[0].nombre);
                		parrafo = document.getElementById('nombreAuditoria');
                		parrafo.innerHTML = objeto[0].nombre;
                		$('#mostrardatos').css('display', 'inline');

	                	
                	}
                	
                	//console.log(msg);
                });

    	//console.log(selectedOption.value + ': ' + selectedOption.text);
    	
  	});

  	//añadimos evento para actualizar permisos de usuario
  	var selectUser = document.getElementById('usuarioinput');
  	var editar = document.getElementById('editarPermisos');
	editar.addEventListener('click',function(){

    	var selectedOption = selectUser.options[selectUser.selectedIndex];
    	var selectedUser = selectedOption.text;
    	var edi = 0;
    	var insert = 0;
    	var visu = 0;
    	if(document.getElementById("checkeditedi").checked==true){
    		edi = 1;
    	}

    	if(document.getElementById("checkeditisert").checked==true){
    		insert = 1;
    	}

    	if(document.getElementById("checkeditvisu").checked==true){
    		visu = 1;
    	}


    			$.ajax({
                    type: "POST",
                    url: "http://localhost/practicaAuditoria/editarUser.php",
                    data: {
         				selectedUser: selectedUser,
         				editar: edi,
         				insertar: insert,
         				visualizar: visu

                    }

                }).done(function(msg){

                	if(msg ==='1')
                	{
 						alert("Permisos de usuario actualizados");
                	}else{
                		alert("No se puedo realizar la operación");
                	}

                });

  	});

</script>	
</html>