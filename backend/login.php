<?php
	require('conexionDB.php');

	session_start();
	
	//si existe la session la destruimos
	if(isset($_SESSION['rol']))
	{
		session_destroy();
	}


	//recogemos el formulario del inicio de sesion
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{

		$usuario = $_POST['usuario'];
		$pass = $_POST['password'];

		//comprobamos si existe el usuario y la contraseña, si es distinto de null la autenticacion será valida
		$user =  comprobarUsuario($usuario, $pass);
		
		if($user != null){
			//guardamos el rol del usuario, la entidad a la que pertenece y los permisos de edicion, insercion y visualizacion
			$_SESSION['rol'] = $user[0];
			$_SESSION['entidad'] = $user[1];

			$_SESSION['edicion'] = $user[2];
			$_SESSION['insercion'] = $user[3];
			$_SESSION['visualizacion'] = $user[4];

			header("Location: panelAdministrador.php");
		}else{
			//echo "Usuario o contraseña incorrectos";
			echo "<script type='text/javascript'>alert('Usuario o contraseña incorrectos');</script>";
		}
		
	}

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<title>Login Auditoria</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="Bootstrap4/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./src/login.css">

</head>
<body>
	<div class="cajonera">
		<nav  class="nav">
		  <a id = "user" class="nav-link active" href="#" onclick="verPestania(event, 'active' );">Usuario/Contraseña</a>
		  <a id = "cert" class="nav-link" href="#" onclick="verPestania(event, 'active' );">Certificado ACCV/DNI-e</a>
		  <a id="correo" class="nav-link" href="#" onclick="verPestania(event, 'active' );">Correo GVA</a>
		</nav>
		<!--********************************************-->
	<form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data" method="post">

		<div id ="caja_user" class = "caja_user caja">

			  <div class="form-group">
			    <label for="usuario">Usuario</label>
			    <input type="text" class="form-control" id="usuario" name="usuario" aria-describedby="userHelp" placeholder="Usuario">
			    <small id="userHelp" class="form-text text-muted">no compartas tus credenciales</small>
			  </div>
			  <div class="form-group">
			    <label for="exampleInputPassword1">Contraseña</label>
			    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña">
			  </div>
			  <div class="form-check">
			    <input type="checkbox" class="form-check-input" id="exampleCheck1">
			    <label class="form-check-label" for="exampleCheck1">Check me out</label>
			  </div>
			  <input type="submit" class="btn btn-primary" id="validar" name="validar" value="Enviar"></input>
		</div>
		<!--********************************************-->		
		<div id="caja_cert" class ="caja_cert caja visible">
			  <div class="form-group">
			    <b><label id="labelCertificado" for="certificadoDigital">Acceso con certificado</label></b><br>
			    <label>Internet Explorer debe usar SSL 3.0 desde Opciones de Internet/Opciones avanzadas la propiedad "Usar SSL 2.0" debe estar desmarcada.</label>
			    <input type="file" class="form-control-file" id="certificadoDigital">
			  </div>
		</div>
		<div id="caja_correo" class ="caja_correo caja visible">
			<label>Correo</label>
		</div>
	</form>
	</div>
	
</body>
<script type="text/javascript" src="Bootstrap4/js/bootstrap.min.js"></script>
<script type="text/javascript">

	//funcion para cambiar de visible a invisible y viceversa a traves de css las pestañas de autenticacion
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
</script>	
</html>