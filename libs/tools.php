<?php

    /**
	*Asegura la cookie hasta el cierre de sesión.
	*
	*/
    function sesionSegura(){

		$cookieParams = session_get_cookie_params();
		$path = $cookieParams["path"];

		$secure = true;
		$httponly = true;
		$samesite = "strict";

		session_set_cookie_params([

			"lifetime" => $cookieParams["lifetime"],
			"path" => $path,
			"domain" => $_SERVER["HTTP_HOST"],
			"secure" => $secure,
			"httponly" => $httponly,
			"samesite" => $samesite
		]);

		session_start();
		session_regenerate_id();

		if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > 1800)) {
			session_unset(); 
			session_destroy(); 
		}
		$_SESSION['start'] = time();
    }


    /**
	*Asegura que no inyecten scripts en cada valor del array POST.
	*
	*@param $cadena: Array POST con todos los valores.
	*/
    function limpiarCadena($cadena){

		$patron = array('/<script>.*\/script>/');
		$cadena = preg_replace($patron, "", $cadena);
		$cadena = htmlspecialchars($cadena);
		return $cadena;
    }



	/**
	*Limpia todos los valores del array POST usando la función limpiarCadena().
	*
	*/
    function limpiarEntradas(){

		if(isset($_POST)) {
			
			foreach($_POST as $key => $value) {
				
				$_POST[$key] = limpiarCadena($value);
			}
		}
		elseif (isset($_GET)) {
			
			foreach($_GET as $key => $value) {
				
				$_GET[$key] = limpiarCadena($value);
			}
		}
    }










	// CONTROLES DEL LOGIN


    /**
	*Limitar caracteres
	*
	*@param $usuario: cadena de caracteres del usuario.
	*/
	function usuarioLimitar($usuario){

		if (strlen($usuario) < 4 || strlen($usuario) > 11) {

			return false;
		}
		else {
			
			return true;
		}
	}



    /**
	*Limitar caracteres
	*
	*@param $clave: cadena de caracteres de la clave.
	*/
	function claveLimitar($clave){

		if (strlen($clave) > 4 && strlen($clave) < 30) {

			if (strpos($clave, ' ') == 0) {
				
				return true;
			}
			else {
				
				return false;
			}
		}
		else {
			
			return false;
		}
	}




    /**
	*Conocer las llaves deL array Post
	*
	*@param $post: array POST del PHP.
	*/
	function postInputsLogin($post){

		$nombres_comparar = ["txtxUser", "txtPass", "btnLogin", "csrf"];
		
		if (count($post) == 4) {
			
			foreach ($post as $key => $value) { 
				         
				if (!in_array($key, $nombres_comparar)) {
						
					echo '<script>alert("Error al ingresar");</script>';
					return false;
				}
			}

			if (usuarioLimitar($post["txtxUser"]) && claveLimitar($post["txtPass"])) {
				
				return true;
			}
			else {
				
				echo '<script>alert("Error al ingresar");</script>';
				return false;
			}
		}
		else {
			
			echo '<script>alert("Error al ingresar");</script>';
			return false;
		}
	}









	
	// CONTROLES DEL RESGISTRO DE ALUMNOS



	/**
	*Limitar caracteres
	*
	*@param $nombre: cadena de caracteres del nombre.
	*@param $apellido: cadena de caracteres del apellido.
	*/
	function limitarNombreApellido($nombre, $apellido){

		if ((strlen($nombre) < 4 && strlen($apellido) < 4) || (strlen($nombre) > 40 && strlen($apellido) > 40)) {

			return false;
		}
		else {
			
			return true;
		}
	}



	/**
	*Limitar caracteres
	*
	*@param $nombre: cadena de caracteres del código.
	*/
	function limitarCodigo($codigo){

		if (strlen($codigo) != 10) {

			return false;
		}
		else {
			
			return true;
		}
	}



	/**
	*Limitar caracteres
	*
	*@param $nombre: cadena de caracteres del documento.
	*/
	function limitarDocumento($documento){

		if (strlen($documento) < 10 || strlen($documento) > 20) {

			return false;
		}
		else {
			
			return true;
		}
	}



	/**
	*Limitar cantidad de privilegios
	*
	*@param $privilegios: número entre 0 y 1 para determinar si su privilegio.
	*/
	function limitarPrivilegios($privilegios){

		$privilegios = intval($privilegios);

		if ($privilegios < 0 || $privilegios > 1) {
			
			return false;
		}
		else {
			
			return true;
		}
	}



	/**
	*Conocer las llaves deL array Post
	*
	*@param $post: array POST del PHP.
	*/
	function postInputsRegistrar($POST){

		$nombres_comparar = ["txtNombre", "txtApellido", "txtCodigo", "txtDocumento", "txtUsuario",
							"txtClave", "numPrivilegios", "btnRegistrar", "csrf"];
		
		if (count($POST) == 9) {
			
			foreach ($POST as $key => $value) { 
				         
				if (!in_array($key, $nombres_comparar)) {
						
					echo '<script>alert("Error al registrar 1");</script>';
					return false;
				}
			}

			if (usuarioLimitar($POST["txtUsuario"]) && limitarNombreApellido($POST["txtNombre"], $POST["txtApellido"]) 
				&& limitarCodigo($POST["txtCodigo"]) && limitarDocumento($POST["txtDocumento"]) && limitarPrivilegios($POST["numPrivilegios"])
				&& claveLimitar($POST["txtClave"])) {
				
				return true;
			}
			else {
				
				echo '<script>alert("Error al registrar 2");</script>';
				return false;
			}
		}
		else {
			
			echo '<script>alert("Error al registrar 3");</script>';
			return false;
		}
	}



	/**
	*Conocer las llaves deL array Post
	*
	*@param $post: array POST del PHP.
	*/
	function postInputsEditar($POST){

		$nombres_comparar = ["txtNombre", "txtApellido", "txtDocumento", "btnEditar", "csrf"];
		
		if (count($POST) == 5) {
			
			foreach ($POST as $key => $value) { 
				         
				if (!in_array($key, $nombres_comparar)) {
						
					echo '<script>alert("Error al registrar 1");</script>';
					return false;
				}
			}

			if (limitarNombreApellido($POST["txtNombre"], $POST["txtApellido"]) && limitarDocumento($POST["txtDocumento"])) {
				
				return true;
			}
			else {
				
				echo '<script>alert("Error al registrar 2");</script>';
				return false;
			}
		}
		else {
			
			echo '<script>alert("Error al registrar 3");</script>';
			return false;
		}
	}
	








	
	// CONTROLES DEL RESGISTRO DE CURSOS



	/**
	*Limitar caracteres
	*
	*@param $codigo: cadena de caracteres del codigo.
	*/
	function limitarCodigoCurso($codigo){

		if (strlen($codigo) != 5) {

			return false;
		}
		else {
			
			return true;
		}
	}



	/**
	*Limitar caracteres
	*
	*@param $nombreCurso: cadena de caracteres del nombre del curso.
	*/
	function limitarNombreCurso($nombreCurso){

		if (strlen($nombreCurso) <= 8 && strlen($nombreCurso) >= 50) {

			return false;
		}
		else {
			
			return true;
		}
	}



	/**
	*Limitar cantidad de creditos
	*
	*@param $creditos: número de creditos del curso.
	*/
	function limitarCreditos($creditos){

		$creditos = intval($creditos);

		if ($creditos <= 1 && $creditos >= 5) {
			
			return false;
		}
		else {
			
			return true;
		}
	}



	/**
	*Conocer las llaves deL array Post
	*
	*@param $post: array POST del PHP.
	*/
	function postInputsRegistrarCursos($post){

		$nombres_comparar = ["txtNombreCurso", "numCreditos", "txtCodigoCurso", "btnRegistrarCurso", "csrf"];
		
		if (count($post) == 5) {
			
			foreach ($post as $key => $value) { 
				         
				if (!in_array($key, $nombres_comparar)) {
						
					echo '<script>alert("Error al registrar 1");</script>';
					return false;
				}
			}

			if (limitarNombreCurso($post["txtNombreCurso"]) && limitarCodigoCurso($post["txtCodigoCurso"]) 
				&& limitarCreditos($post["numCreditos"])) {
				
				return true;
			}
			else {
				
				echo '<script>alert("Error al registrar 2");</script>';
				return false;
			}
		}
		else {
			
			echo '<script>alert("Error al registrar 3");</script>';
			return false;
		}
	}



	/**
	*Conocer las llaves deL array Post
	*
	*@param $post: array POST del PHP.
	*/
	function postInputsEditarCursos($post){

		$nombres_comparar = ["txtNombreCurso", "numCreditos", "btnEditar", "csrf"];
		
		if (count($post) == 4) {
			
			foreach ($post as $key => $value) { 
				         
				if (!in_array($key, $nombres_comparar)) {
						
					echo '<script>alert("Error al registrar 1");</script>';
					return false;
				}
			}

			if (limitarNombreCurso($post["txtNombreCurso"]) && limitarCreditos($post["numCreditos"])) {
				
				return true;
			}
			else {
				
				echo '<script>alert("Error al registrar 2");</script>';
				return false;
			}
		}
		else {
			
			echo '<script>alert("Error al registrar 3");</script>';
			return false;
		}
	}
		








	
	// CONTROLES DE LA BUSQUEDA



	/**
	*Limitar el entero al cual accionar
	*
	*@param $privilegios: número entre 0 y 1 para determinar qué busqueda hacer.
	*/
	function limitarTipoBusqueda($busqueda){

		$privilegios = intval($busqueda);

		if ($privilegios < 0 || $busqueda > 1) {
			
			return false;
		}
		else {
			
			return true;
		}
	}



	/**
	*Limitar caracteres
	*
	*@param $codigo: cadena de caracteres del codigo.
	*/
	function limitarCodigoBusqueda($codigo){

		if ((strlen($codigo) == 5) || (strlen($codigo) == 10))  {

			return true;
		}
		else {
			
			return false;
		}
	}



	/**
	*Conocer las llaves deL array Post
	*
	*@param $post: array POST del PHP.
	*/
	function postInputsBuscar($post){

		$nombres_comparar = ["numBusqueda", "txtBusqueda", "btnBusqueda", "csrf"];
		
		if (count($post) == 4) {
			
			foreach ($post as $key => $value) { 
				         
				if (!in_array($key, $nombres_comparar)) {
						
					echo '<script>alert("Error al registrar 1");</script>';
					return false;
				}
			}

			if (limitarTipoBusqueda($post["numBusqueda"]) && limitarCodigoBusqueda($post["txtBusqueda"])) {
				
				return true;
			}
			else {
				
				echo '<script>alert("Error al registrar 2");</script>';
				return false;
			}
		}
		else {
			
			echo '<script>alert("Error al registrar 3");</script>';
			return false;
		}
	}

	/**
	*Conocer las llaves deL array Post
	*
	*@param $post: array POST del PHP.
	*/
	function postInputsEliminar($post){

		$nombres_comparar = ["numEliminar", "txtBusqueda", "btnEliminar", "csrf"];
		
		if (count($post) == 4) {
			
			foreach ($post as $key => $value) { 
				         
				if (!in_array($key, $nombres_comparar)) {
						
					echo '<script>alert("Error al registrar 1");</script>';
					return false;
				}
			}

			if (limitarTipoBusqueda($post["numEliminar"]) && limitarCodigoBusqueda($post["txtBusqueda"])) {
				
				return true;
			}
			else {
				
				echo '<script>alert("Error al registrar 2");</script>';
				return false;
			}
		}
		else {
			
			echo '<script>alert("Error al registrar 3");</script>';
			return false;
		}
	}
		








	
	// CONTROLES DE RECUPERAR CONTRASEÑA



	/**
	*Conocer las llaves deL array Post
	*
	*@param $post: array POST del PHP.
	*/
	function postInputsRecovery($post){

		$nombres_comparar = ["txtUsuario", "txtContraseña", "btnRecovery", "csrf"];
		
		if (count($post) == 4) {
			
			foreach ($post as $key => $value) { 
				         
				if (!in_array($key, $nombres_comparar)) {
						
					echo '<script>alert("Error al registrar 1");</script>';
					return false;
				}
			}

			if (usuarioLimitar($post["txtUsuario"]) && claveLimitar($post["txtContraseña"])) {
				
				return true;
			}
			else {
				
				echo '<script>alert("Error al registrar 2");</script>';
				return false;
			}
		}
		else {
			
			echo '<script>alert("Error al registrar 3");</script>';
			return false;
		}
	}
		








	
	// CONTROLES DE RECUPERAR CONTRASEÑA



	/**
	*Limitar caracteres
	*
	*@param $codigo: cadena de caracteres del codigo.
	*/
	function limitarIdInscribir($id){

		$id = intval($id);

		if ($id < 1 && $id > 900) {
			
			return false;
		}
		else {
			
			return true;
		}
	}



	/**
	*Conocer las llaves deL array Post
	*
	*@param $post: array POST del PHP.
	*/
	function postInputsInscribir($post){

		$nombres_comparar = ["txtCodigo", "btnInscribir", "csrf"];
		
		if (count($post) == 3) {
			
			foreach ($post as $key => $value) { 
				         
				if (!in_array($key, $nombres_comparar)) {
						
					echo '<script>alert("Error al registrar 1");</script>';
					return false;
				}
			}

			if (limitarIdInscribir($post["txtCodigo"])) {
				
				return true;
			}
			else {
				
				echo '<script>alert("Error al registrar 2");</script>';
				return false;
			}
		}
		else {
			
			echo '<script>alert("Error al registrar 3");</script>';
			return false;
		}
	}
?>