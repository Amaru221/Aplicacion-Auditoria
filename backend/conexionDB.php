<?php
	function leer_config($nombre, $esquema){
		$config = new DOMDocument();
		$config->load($nombre);
		$res = $config->schemaValidate($esquema);
		if ($res===FALSE){ 
		   throw new InvalidArgumentException("Revise fichero de configuración");
		} 		
		$datos = simplexml_load_file($nombre);	
		$ip = $datos->xpath("//ip");
		$nombre = $datos->xpath("//nombre");
		$usu = $datos->xpath("//usuario");
		$clave = $datos->xpath("//clave");	
		$cad = sprintf("mysql:dbname=%s;host=%s", $nombre[0], $ip[0]);
		$resul = [];
		$resul[] = $cad;
		$resul[] = $usu[0];
		$resul[] = $clave[0];
		return $resul;
	}	


	function comprobarUsuario($usuario, $pass){
			$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
			$bd = new PDO($res[0], $res[1], $res[2]);
			$ins = "select * from usuario where nombre='$usuario' and contraseña = '$pass';";

			$array;
			$resul = $bd->query($ins);
			if($resul->rowCount() === 1){
				foreach($resul as $devuelto){
					$rol = $devuelto['rol'];
					$entidad = $devuelto['entidadauditora'];
					$edicion = $devuelto['edicion'];
					$insercion = $devuelto['insercion'];
					$visualizacion = $devuelto['visualizacion'];
					$array[] = $rol;
					$array[] = $entidad;
					$array[] = $edicion;
					$array[] = $insercion;
					$array[] = $visualizacion;
					return $array;
				}

			}else{
				return null;
			}


	}

	function insertarUser($nombre, $apellidos, $pass, $rol, $entidad, $edi, $inser, $visu):string{

		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
		$bd = new PDO($res[0], $res[1], $res[2]);

		$ins = "insert into usuario (nombre, apellidos, contraseña, rol, entidadauditora, edicion, insercion, visualizacion) values ('$nombre', '$apellidos', '$pass', '$rol','$entidad', '$edi', '$inser', '$visu')";
		$ins2 = "select * from usuario";

		$usuarios = $bd->query($ins2);
		$existe = false;
		if($usuarios){
			foreach($usuarios as $usuario){
				if($usuario['nombre'] === $nombre)
				{	
					$existe = true;
					return "el usuario ya existe";
				}
			}
			
			if(!$existe)
			{
				$bd->query($ins);
				return "usuario creado";
			}

		}

		return "";
	}

	function insertarTarea($nombre, $desc, $cod, $entidad){

		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
		$bd = new PDO($res[0], $res[1], $res[2]);

		$ins = "insert into tarea (nombre, descripcion, codigo, entidadauditada) values ('$nombre', '$desc', '$cod', '$entidad')";
		$ins2 = "select * from entidadauditada where id ='$entidad'";

		$entidad = $bd->query($ins2);
		if($entidad->rowCount() === 1){
			
			$resul = $bd->query($ins);
			if(!$resul){
				echo "error al insertar tarea";
			}else{
				echo "tarea insertada";
			}
		}else{
			echo "La entidad auditada no existe";

		}

		


	}

	function devolverUsuarios(){
			$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
			$bd = new PDO($res[0], $res[1], $res[2]);
			$ins = "select * from usuario";

			$array;
			$resul = $bd->query($ins);
			if($resul->rowCount() >= 1){
				
				foreach($resul as $devuelto){
					$array[] = $devuelto['nombre'];
					//return $array;
				}
				return $array;

			}else{
				return null;
			}


	}

	function devolverAuditora(){
			$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
			$bd = new PDO($res[0], $res[1], $res[2]);
			$ins = "select * from entidadauditora";

			$entidades = array();

			$resul = $bd->query($ins);

			if($resul->rowCount() >= 1){
				foreach($resul as $devuelto){

					$id = $devuelto['id'];
					$nombre = $devuelto['nombre'];
					$array[] = $id;
					$array[] = $nombre;
				}

				return $array;

			}else{
				return null;
			}


	}

	function devolverAuditada($entidad){
			$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
			$bd = new PDO($res[0], $res[1], $res[2]);
			$ins = "select * from entidadauditada where entidadauditora = '$entidad'";

			$entidades = array();

			$resul = $bd->query($ins);

			if($resul->rowCount() >= 1){
				foreach($resul as $devuelto){

					$id = $devuelto['id'];
					$nombre = $devuelto['nombre'];
					$array[] = $id;
					$array[] = $nombre;
				}

				return $array;

			}else{
				return null;
			}


	}

	function devolverTarea($entidad){
			$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
			$bd = new PDO($res[0], $res[1], $res[2]);
			$ins = "SELECT t.* from tarea t, entidadauditora ea, entidadauditada ead where ead.id = t.entidadauditada and ea.id = ead.entidadauditora and ea.id = $entidad";

			$array;

			$resul = $bd->query($ins);
			if($resul->rowCount() >= 1){
				foreach($resul as $devuelto){

					$id = $devuelto['id'];
					$nombre = $devuelto['nombre'];
					$array[] = $id;
					$array[] = $nombre;
				}

				return $array;

			}else{
				return null;
			}


	}
	function actualizarUser($user, $edi, $insert, $visu){
		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
		$bd = new PDO($res[0], $res[1], $res[2]);

		$ins = "update usuario set edicion='$edi', insercion='$insert', visualizacion='$visu' where nombre = '$user'";
		$resul = $bd->query($ins);
		if($resul->rowCount() === 1){
			return 1;
		}else{
			return 0;
		}
	}

	function insertarTramite($nombre, $desc, $cod, $tarea){

		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
		$bd = new PDO($res[0], $res[1], $res[2]);

		$ins = "insert into tramite (nombre, descripcion, codigo, tarea) values ('$nombre', '$desc', '$cod', '$tarea')";
		$ins2 = "select * from tarea where id ='$tarea'";

		$tarea = $bd->query($ins2);
		if($tarea->rowCount() === 1){
			
			$resul = $bd->query($ins);
			if(!$resul){
				echo "error al insertar tramite";
			}else{
				echo "tramite insertado";
			}
		}else{
			echo "La tarea no existe";

		}

	}


	function mostrarTareas($entidad){

		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
			$bd = new PDO($res[0], $res[1], $res[2]);
			$ins = "SELECT t.* from tarea t, entidadauditora ea, entidadauditada ead where ead.id = t.entidadauditada and ea.id = ead.entidadauditora and ea.id = $entidad";


			$tareas = array();

			$resul = $bd->query($ins);
			if($resul->rowCount() >= 1){
				foreach($resul as $devuelto){

					$tareas[] = $devuelto;
				}

				return $tareas;

			}else{
				return null;
			}

	}

	function mostrarTramites($tarea){

		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
		$bd = new PDO($res[0], $res[1], $res[2]);
		$ins = "select * from tramite where tarea = '$tarea' ";

		$tramites = array();

		$resul = $bd->query($ins);
		if($resul->rowCount() >= 1){
			foreach($resul as $devuelto){

				$tramites[] = $devuelto;
			}

			return $tramites;

		}else{
			return null;
		}

	}

	function mostrarDocumentos($tramite){

		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
		$bd = new PDO($res[0], $res[1], $res[2]);
		$ins = "select * from documento where tramite = '$tramite'";

		$documentos = array();

		$resul = $bd->query($ins);
		if($resul->rowCount() >= 1){
			foreach($resul as $devuelto){

				$documentos[] = $devuelto;
			}

			return $documentos;

		}else{
			return null;
		}

	}

	function mostrarDocumentosPorTarea($tarea){

		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
		$bd = new PDO($res[0], $res[1], $res[2]);
		$ins = "select d.* from documento d, tramite tr, tarea t where d.tramite = tr.id and tr.tarea = t.id and t.id = $tarea";

		$documentos;

		$resul = $bd->query($ins);
		if($resul->rowCount() >= 1){
			foreach($resul as $devuelto){

				$documentos[] = $devuelto;
			}

			return $documentos;

		}else{
			return null;
		}

	}


	function devolverTareaEntidad($entidad){
		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
		$bd = new PDO($res[0], $res[1], $res[2]);
		$ins = "select t.* from tarea t, entidadauditada ead, entidadauditora ea where t.entidadauditada = ead.id and ead.entidadauditora = ea.id and ea.id = $entidad";
		$array;

			$resul = $bd->query($ins);
			if($resul->rowCount() >= 1){
				foreach($resul as $devuelto){

					$id = $devuelto['id'];
					$nombre = $devuelto['nombre'];
					$array[] = $id;
					$array[] = $nombre;
				}

				return $array;

			}else{
				return null;
			}
	}


	function devolverTramiteEntidad($entidad){
		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
		$bd = new PDO($res[0], $res[1], $res[2]);
		$ins = "select tr.* from tramite tr, entidadauditada ead, entidadauditora ea, tarea t where t.id = tr.tarea and ead.id = t.entidadauditada and ead.entidadauditora = ea.id and ea.id = $entidad";
		$array;

			$resul = $bd->query($ins);
			if($resul->rowCount() >= 1){
				foreach($resul as $devuelto){

					$id = $devuelto['id'];
					$nombre = $devuelto['nombre'];
					$array[] = $id;
					$array[] = $nombre;
				}

				return $array;

			}else{
				return null;
			}
	}

	function borrarTarea($idTarea){
		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
		$bd = new PDO($res[0], $res[1], $res[2]);
		$ins = "delete from tarea where id = '$idTarea'";

		$resul = $bd->query($ins);

		if($resul == TRUE)
		{
			return "Tarea eliminada";
		}else{
			return "La tarea no fue eliminida";
		}


	}

	function borrarTramite($idTramite){
		//echo "hola";
		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
		$bd = new PDO($res[0], $res[1], $res[2]);
		$ins = "delete from tramite where id = '$idTramite'";

		$resul = $bd->query($ins);

		if($resul == TRUE)
		{
			return "Tramite eliminado";
		}else{
			return "El tramite no fue eliminido";
		}


	}

	function borrarDocumento($idDocumento){
		echo $idDocumento;
		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
		$bd = new PDO($res[0], $res[1], $res[2]);
		$ins = "delete from documento where id = '$idDocumento'";

		$resul = $bd->query($ins);

		if($resul == TRUE)
		{
			return "Documento eliminado";
		}else{
			return "El documento no fue eliminido";
		}

	}

	function modificarTarea($idTarea, $nombre,$descripcion,$codigo,$entidadAuditada){
		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
		$bd = new PDO($res[0], $res[1], $res[2]);
		$ins = "update tarea set nombre = '$nombre', descripcion = '$descripcion', codigo = '$codigo', entidadauditada = '$entidadAuditada' where id = '$idTarea'";

		$resul = $bd->query($ins);


		if($resul == TRUE)
		{
			return "Tarea modificada";
		}else{
			return "No se pudo modificar la tarea";
		}


	}

	function modificarTramite($idTramite, $nombre,$descripcion,$codigo){
		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
		$bd = new PDO($res[0], $res[1], $res[2]);
		$ins = "update tramite set nombre = '$nombre', descripcion = '$descripcion', codigo = '$codigo' where id = '$idTramite'";

		$resul = $bd->query($ins);


		if($resul == TRUE)
		{
			return "Tramite modificado";
		}else{
			return "No se pudo modificar el tramite";
		}


	}

	function modificarDocumento($idDocumento, $nombre,$descripcion,$codigo){
		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
		$bd = new PDO($res[0], $res[1], $res[2]);
		$ins = "update documento set nombre = '$nombre', descripcion = '$descripcion', codigo = '$codigo' where id = '$idDocumento'";

		$resul = $bd->query($ins);


		if($resul == TRUE)
		{
			return "Documento modificado";
		}else{
			return "No se pudo modificar el documento";
		}


	}

	function subirArchivo($idTramite, $nombre, $descripcion, $codigo, $url){

		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
		$bd = new PDO($res[0], $res[1], $res[2]);
		$ins = "insert into documento (nombre, descripcion, codigo, url, tramite) values ('$nombre', '$descripcion', '$codigo', '$url', '$idTramite')";

		$resul = $bd->query($ins);


		if($resul == TRUE)
		{
			return "Documento subido";
		}else{
			return "No se pudo subir el documento";
		}

	}

	function allDocuments($entidad){

		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
		$bd = new PDO($res[0], $res[1], $res[2]);
		$ins = "select d.* from documento d, entidadauditora ea, entidadauditada ead, tarea ta, tramite tr where tr.id = d.tramite and ta.id = tr.tarea and ead.id = ta.entidadauditada and ea.id = ead.entidadauditora and ea.id = $entidad";

		$documentos = array();

		$resul = $bd->query($ins);
		if($resul->rowCount() >= 1){
			foreach($resul as $devuelto){

				$documentos[] = $devuelto;
			}

			return $documentos;

		}else{
			return null;
		}

	}

	function auditoriasUser($usuario){
		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
		$bd = new PDO($res[0], $res[1], $res[2]);
		$ins = "select ead.* from entidadauditada ead, entidadauditora ea where ead.entidadauditora = ea.id";

		$auditorias = array();

		$resul = $bd->query($ins);
		if($resul->rowCount() >= 1){
			foreach($resul as $devuelto){

				$id = $devuelto['id'];
				$nombre = $devuelto['nombre'];
				$auditorias[] = $id;
				$auditorias[] = $nombre;
			}

			return $auditorias;

		}else{
			return null;
		}
	}


	function devolverAuditoria($option){
		$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
		$bd = new PDO($res[0], $res[1], $res[2]);
		$ins = "select ead.* from entidadauditada ead, entidadauditora ea where ead.entidadauditora = ea.id and ead.nombre = '$option'";

		$auditoria = array();

		$resul = $bd->query($ins);
		if($resul->rowCount() >= 1){
			foreach($resul as $devuelto){
				$auditoria[] = $devuelto;
			}

			return $auditoria;

		}else{
			return null;
		}
	}


?>