<?php

    /**
    *Genera la conexión con la base de datos de tipo PDO.
    *
    */
    function conexionDB(){

		$servername = "asdsadsad";
		$database = "linea_tres";
		$username = "calvoCabronnn";
		$password = 'Negro99012610';

		$sql = "mysql:host=$servername;dbname=$database;";
		$dsn_Options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

		try { 
			$my_Db_Connection = new PDO($sql, $username, $password, $dsn_Options);
			return $my_Db_Connection;
		} catch (PDOException $error) {
			echo 'Connection error: ' . $error->getMessage();
			return NULL;
		}
	}



    /**
	*Carga clave y usuario en la sesion y verifica que exista en la base de datos.
	*
    *@param $conexion: Conexión PDO para la base de datos.
	*@param $usuario: Login Usuario.
	*@param $password: Contraseña Usuario.
	*/
    function loginDB($conexion, $usuario, $password){

        try {
            
            $validar_usuario = $conexion->prepare("SELECT id, User, nombre, apellido, codigo, documento, privilegios, pass
                                                FROM users WHERE User = :User");
            $validar_usuario->bindParam(":User", $usuario, PDO::PARAM_STR);
            $validar_usuario->execute();
            $validar_password = $validar_usuario->fetch(PDO::FETCH_ASSOC);

            if (is_array($validar_password)) {

                if (password_verify($password, $validar_password["pass"])) {
                    
                    $_SESSION["User"] = $validar_password["User"];
                    $_SESSION["privilegios"] = $validar_password["privilegios"];
                    $_SESSION["nombre"] = $validar_password["nombre"];
                    $_SESSION["apellido"] = $validar_password["apellido"];
                    $_SESSION["codigo"] = $validar_password["codigo"];
                    $_SESSION["documento"] = $validar_password["documento"];
                    $_SESSION["id"] = $validar_password["id"];

                    return true;
                }
                else{

                    return false;
                }
            }
            else {
                
                return false;
            }
            
        } catch (\Throwable $th) {

            //throw $th;
        }
	}



    /**
	*Graba los datos de los usuarios en la base de datos.
	*
    *@param $conexion: Conexión PDO para la base de datos.
    *@param $nombre: Nombre del usuario en string.
	*@param $apellido: Apellido dle usuario en string.
    *@param $codigo: codigo de la persona.
	*@param $documento: número dle doumento de la persona.
    *@param $privilegios: diferenciar administrador de alumno.
    *@param $user: usuario del alummno.
	*@param $clave: Contraseña del usuario en string.
	*/
    function grabarDB($conexion, $nombre, $apellido, $codigo, $documento, $privilegios, 
                        $user, $clave){

		$clave = password_hash($clave, PASSWORD_DEFAULT);

        try {
            
            $comprobar_usuario = $conexion->prepare("SELECT User FROM users WHERE User = :User OR codigo = :codigo");
            $comprobar_usuario->bindParam(":User", $user, PDO::PARAM_STR);
            $comprobar_usuario->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $comprobar_usuario->execute();
            $comprobar_usuario->fetchAll();
            $num_filas = $comprobar_usuario->rowCount();

            if($num_filas >= 1) {

                echo '<script>alert("El Usuario ya existe");</script>';
                return false;
            }
            else{

                $registro = $conexion->prepare("INSERT INTO users (
                                                User,
                                                pass,
                                                nombre, 
                                                apellido, 
                                                codigo, 
                                                documento, 
                                                privilegios)

                                                VALUES (:User, :pass, :nombre, :apellido, :codigo, :documento, :privilegios)");
                
                $registro->bindParam(":User", $user, PDO::PARAM_STR);
                $registro->bindParam(":pass", $clave, PDO::PARAM_STR);
                $registro->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $registro->bindParam(":apellido", $apellido, PDO::PARAM_STR);
                $registro->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $registro->bindParam(":documento", $documento, PDO::PARAM_STR);
                $registro->bindParam(":privilegios", $privilegios, PDO::PARAM_INT);

                $registro->execute();
                return true;
            }
            
        } catch (\Throwable $th) {
            
            echo $th;
        }
	}



    /**
	*Graba los datos de los usuarios en la base de datos.
	*
    *@param $conexion: Conexión PDO para la base de datos.
    *@param $nombreCurso: Nombre del usuario en string.
    *@param $codigoCurso: codigo de la persona.
	*@param $creditos: número dle doumento de la persona.
	*/
    function grabarCursoDB($conexion, $nombreCurso,$codigoCurso, $creditos){

        try {
            
            $comprobar_usuario = $conexion->prepare("SELECT codigo FROM curso WHERE codigo = :codigo");
            $comprobar_usuario->bindParam(":codigo", $codigoCurso, PDO::PARAM_STR);
            $comprobar_usuario->execute();
            $comprobar_usuario->fetchAll();
            $num_filas = $comprobar_usuario->rowCount();

            if($num_filas == 1) {

                echo '<script>alert("El Curso ya existe");</script>';
                return false;
            }
            else{

                $registro = $conexion->prepare("INSERT INTO curso (
                                                nombre,
                                                creditos,
                                                codigo)

                                                VALUES (:nombre, :creditos, :codigo)");
                
                $registro->bindParam(":nombre", $nombreCurso, PDO::PARAM_STR);
                $registro->bindParam(":creditos", $creditos, PDO::PARAM_INT);
                $registro->bindParam(":codigo", $codigoCurso, PDO::PARAM_STR);

                $registro->execute();
                return true;
            }
            
        } catch (\Throwable $th) {
            
            echo $th;
        }
	}



    /**
	*Realiza la busqueda de un curso o un alumno.
	*
    *@param $conexion: Conexión PDO para la base de datos.
    *@param $codigo: código único del usuario o curso a mostrar sus datos.
    *@param $opncion: define si la busqueda es un alumno o un curso.
	*/
    function busquedaDB($conexion, $codigo, $opcion){

        if ($opcion  == 0) {
            
            $busqueda_alumno = $conexion->prepare("SELECT concat (nombre, ' ', apellido) AS nombre_completo, codigo, documento 
                                                    from users 
                                                    WHERE codigo = :codigo AND privilegios = '0'");
            $busqueda_alumno->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $busqueda_alumno->execute();
            $array_busqueda_alumno = $busqueda_alumno->fetch(PDO::FETCH_ASSOC);

            return $array_busqueda_alumno;
        }else{

            $busqueda_curso = $conexion->prepare("SELECT nombre, creditos, codigo 
                                                    from curso 
                                                    WHERE codigo = :codigo");
            $busqueda_curso->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $busqueda_curso->execute();
            $array_busqueda_curso = $busqueda_curso->fetch(PDO::FETCH_ASSOC);

            return $array_busqueda_curso;
        }
    }



    /**
	*Muestra lows cursos a los cuales esta inscrito el alumno.
	*
    *@param $conexion: Conexión PDO para la base de datos.
    *@param $codigo: código único del usuario o curso a mostrar sus datos.
	*/
    function CursosAlumnoDB($conexion, $codigo){
          
        $inscripción_cursos = $conexion->prepare("SELECT c.nombre, c.codigo
                                                FROM users u
                                                
                                                INNER JOIN alumno_curso ac ON (u.id=ac.id_alumno)
                                                INNER JOIN curso c ON (ac.id_curso=c.id)
                                                
                                                WHERE u.codigo = :codigo");
        $inscripción_cursos->bindParam(":codigo", $codigo, PDO::PARAM_STR);
        $inscripción_cursos->execute();
        $array_inscripción_cursos = $inscripción_cursos->fetchAll(PDO::FETCH_ASSOC);

        return $array_inscripción_cursos;
    }



    /**
	*Muestra los alumnos a los cuales est´sn inscrito en un curso.
	*
    *@param $conexion: Conexión PDO para la base de datos.
    *@param $codigo: código único del usuario o curso a mostrar sus datos.
	*/
    function AlumnosInscritosDB($conexion, $codigo){
          
        $inscripción_cursos = $conexion->prepare("SELECT concat (u.nombre, ' ', u.apellido) as nombre_completo, u.codigo
                                                FROM curso c 
                                                                                               
                                                INNER JOIN alumno_curso ac ON (c.id=ac.id_curso)
                                                INNER JOIN users u ON (ac.id_alumno=u.id)

                                                WHERE c.codigo = :codigo");
        $inscripción_cursos->bindParam(":codigo", $codigo, PDO::PARAM_STR);
        $inscripción_cursos->execute();
        $array_inscripción_cursos = $inscripción_cursos->fetchAll(PDO::FETCH_ASSOC);

        return $array_inscripción_cursos;
    }



    /**
	*Graba los datos de los usuarios en la base de datos.
	*
    *@param $conexion: Conexión PDO para la base de datos.
    *@param $usuario: Nombre del usuario en string.
    *@param $clave: Contraseña nueva a reemplazar.
	*/
    function recuperarClaveDB($conexion, $usuario, $clave){

        $clave = password_hash($clave, PASSWORD_DEFAULT);

        try {
            
            $comprobar_usuario = $conexion->prepare("SELECT User FROM users WHERE User = :User AND privilegios = 0");
            $comprobar_usuario->bindParam(":User", $usuario, PDO::PARAM_STR);
            $comprobar_usuario->execute();
            $comprobar_usuario->fetchAll();
            $num_filas = $comprobar_usuario->rowCount();       
        
            if($num_filas == 1) {

                $new_clave = $conexion->prepare("UPDATE users SET pass = :pass 
                                                WHERE User = :User");
                $new_clave->bindParam(":User", $usuario, PDO::PARAM_STR);
                $new_clave->bindParam(":pass", $clave, PDO::PARAM_STR);
                $new_clave->execute();

                return true;
            }
            else {
                
                echo '<script>alert("El Usuario no existe");</script>';
                return false;
            }
            
        } catch (\Throwable $th) {
            
            echo $th;
        }
	}



    /**
	*Realiza el listado de todos los alumnos.
	*
    *@param $conexion: Conexión PDO para la base de datos.
	*/
    function listaAlumnosDB($conexion){
            
        try {
            
            $lista_usuarios = $conexion->prepare("SELECT concat (nombre, ' ', apellido) as nombre_completo, codigo, documento, id 
                                                FROM users 
                                                WHERE privilegios = 0");
            $lista_usuarios->execute();
            $arrayLista = $lista_usuarios->fetchAll(PDO::FETCH_ASSOC);

            return $arrayLista;

        } catch (\Throwable $th) {
            //throw $th;
        }
    }



    /**
	*Realiza el listado de todos los alumnos.
	*
    *@param $conexion: Conexión PDO para la base de datos.
	*/
    function listaCursosDB($conexion){
            
        try {
            
            $lista_cursos = $conexion->prepare("SELECT nombre, codigo, creditos 
                                                FROM curso");
            $lista_cursos->execute();
            $arrayLista = $lista_cursos->fetchAll(PDO::FETCH_ASSOC);

            return $arrayLista;

        } catch (\Throwable $th) {
            //throw $th;
        }
    }



    /**
	*Muestra los cursos a los cuales esta inscrito el alumno.
	*
    *@param $conexion: Conexión PDO para la base de datos.
    *@param $id: id para identificar al usuario.
	*/
    function CursosNoInscritosDB($conexion, $id){
          
        $cursos_no_inscritos = $conexion->prepare("SELECT nombre, codigo, id
                                                    FROM curso

                                                    WHERE nombre NOT IN(
                                                        SELECT c.nombre
                                                        FROM alumno_curso ac
                                                        INNER JOIN users u ON (ac.id_alumno=u.id)
                                                        INNER JOIN curso c ON (ac.id_curso=c.id)
                                                        WHERE ac.id_alumno = :id
                                                    )");
        $cursos_no_inscritos->bindParam(":id", $id, PDO::PARAM_INT);
        $cursos_no_inscritos->execute();
        $array_cursos_no_inscritos = $cursos_no_inscritos->fetchAll(PDO::FETCH_ASSOC);

        return $array_cursos_no_inscritos;
    }



    /**
	*Inscribe un alumno a un curso
	*
    *@param $conexion: Conexión PDO para la base de datos.
    *@param $id: id para identificar al usuario.
	*/
    function inscribirAlumnoDB($conexion, $id_alumno, $id_curso){

        try {
            
            $validar_curso = $conexion->prepare("SELECT nombre 
                                                FROM `curso` 
                                                WHERE id = :id");
            $validar_curso->bindParam(":id", $id_curso, PDO::PARAM_INT);
            $validar_curso->execute();
            $arrya_validar_curso = $validar_curso->fetch(PDO::FETCH_ASSOC);

            if (is_array($arrya_validar_curso)) {

                $validar_inscripcion_alumno = $conexion->prepare("SELECT id 
                                                                FROM `alumno_curso` 
                                                                WHERE id_alumno = :id_alumno AND id_curso = :id_curso");
                $validar_inscripcion_alumno->bindParam(":id_alumno", $id_alumno, PDO::PARAM_INT);
                $validar_inscripcion_alumno->bindParam(":id_curso", $id_curso, PDO::PARAM_INT);
                $validar_inscripcion_alumno->execute();
                $arrya_validar_inscripcion_alumno = $validar_inscripcion_alumno->fetch(PDO::FETCH_ASSOC);

                if (is_array($arrya_validar_inscripcion_alumno)) {

                    return false;
                }
                else {

                    $inscribir_curso = $conexion->prepare("INSERT INTO alumno_curso(
                                                        id_alumno, 
                                                        id_curso) 

                                                        VALUES (:id_alumno, :id_curso)");

                    $inscribir_curso->bindParam(":id_alumno", $id_alumno, PDO::PARAM_INT);
                    $inscribir_curso->bindParam(":id_curso", $id_curso, PDO::PARAM_INT);

                    $inscribir_curso->execute();
                    return true;
                }
            }
            else {
                
                return false;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
?>
