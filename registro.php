<?php

    require_once ("libs/tools.php");
    require_once ("libs/conn.php");

    sesionSegura();
    limpiarEntradas();
    $conn = conexionDB();

    if (isset($_POST["csrf"])) {
        
        if ($_POST["csrf"] != $_SESSION["csrf"]) {
        
            header("Location: Admin.php");
            exit;
        }
    }

    if (!isset($_SESSION["User"])) {
        
        header("Location: index.php");
    }

    if ($_SESSION["privilegios"] != 1) {
        
        header("Location: index.php");
    }

    if (isset($_POST["logOut"])) {
        
        session_destroy();
        header("Location: index.php");
    }

    if (isset($_POST["btnRegistrar"])) {
        
        if (postInputsRegistrar($_POST)) {

            $registro = grabarDB($conn, $_POST["txtNombre"], $_POST["txtApellido"], $_POST["txtCodigo"], 
                                $_POST["txtDocumento"], $_POST["numPrivilegios"], $_POST["txtUsuario"], $_POST["txtClave"]);
            
            if ($registro) {

                header("Location: index.php");
            }
        }
    }

    if (isset($_POST["btnRegistrarCurso"])) {
        
        if (postInputsRegistrarCursos($_POST)) {

            $registroCursos = grabarCursoDB($conn, $_POST["txtNombreCurso"], $_POST["txtCodigoCurso"], 
                                            $_POST["numCreditos"]);

            if ($registroCursos) {

                header("Location: index.php");
            }                                             
        }
    }

    $_SESSION["csrf"] = random_int(1000, 9999);
?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro</title>
    </head>
    <div><style>
        /* Estilos CSS */
        body {
            background-image: url('https://img3.wallspic.com/crops/4/4/7/8/4/148744/148744-vuelo-azul-calma-niebla-blanco-3840x2160.jpg');
			background-size: cover;
            background-position: center;
			background-repeat: no-repeat;
			min-height: 100vh;
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
        }
        
        label {
            font-size: 14px;
			font-weight: 700;
			color: #333;
			margin-bottom: 5px;
			margin-top: 10px;
			text-transform: uppercase;
            
        }
        input[type="text"], select {
            width: 50%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            background-position: left;
            
        }
        input[type="password"], select {
            width: 50%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            background-position: left;
        }
        input[type="number"], select {
            width: 50%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            background-position: left;
        }
        input[type="submit"] {
            background-color: #45a049;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
           
        }
        input[type="submit"]:hover {
            background-color: #214b2a;
        }
        table {
            margin: 3 auto;
            border-collapse: collapse;
            width: 100%;
            max-width: 800px;
        }
        th, td {
            text-align: left;
            padding: 8px;
        }
        tr:nth-child(n){background-color: #f2f2f2}
        th {
            background-color: #389ef1;
            color: black;
        }
        h2 {
			text-align: center;
			margin: 0;
			margin-bottom: 30px;
			font-size: 32px;
			font-weight: bold;
			color: #333;
			text-transform: uppercase;
            color: black;
            
		}
        button[type=submit] {
            text-align: center;
			background-color: #214b2a;
			color: #fff;
			padding: 6px 1px;
			margin: 4px 0;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			font-size: 16px;
			width: 30%;
			transition: background-color 0.3s ease;
		}
    </style>
    <body>
        <div>
            <h1>Registrar Alumnos</h1>

            <form action="" method="post">
                <label for="txtNombre">Digite el nombre</label><br>
                <input type="text" name="txtNombre" id="txtNombre" pattern="[A-Za-z ]{2,50}" required><br>

                <label for="txtApellido">Digite el apellido</label><br>
                <input type="text" name="txtApellido" id="txtApellido" pattern="[A-Za-z ]{2,50}" required><br>

                <label for="txtCodigo">Digite el codigo</label><br>
                <input type="text" name="txtCodigo" id="txtCodigo" pattern="[0-9]{10}"><br>

                <label for="txtDocumento">Digite el documento de identidad</label><br>
                <input type="number" name="txtDocumento" id="txtDocumento" pattern="{2, 30}" required><br>

                <label for="txtUsuario">Digite el usuario</label><br>
                <input type="text" name="txtUsuario" id="txtUsuario" pattern="[^' ']+[A-Za-z0-9]{3,15}" required><br>

                <label for="txtClave">Digite su clave</label><br>
                <input type="password" name="txtClave" id="txtClave" pattern="[^' ']+[A-Za-z0-9._%+-]{6,}" required><br>

                <label for="numPrivilegios">Seleccione el tipo privilegio</label><br>
                <select name="numPrivilegios" id="numPrivilegios">
                    <option value="0">Alumno</option>
                    <option value="1">Administrador</option>
                </select><br>

                <input type="hidden" name="csrf" id="csrf" value = "<?php echo $_SESSION["csrf"];?>"><br>

                <input type="submit" name="btnRegistrar" id="btnRegistrar" value="Registrar">
            </form>
        </div>

        <div>
            <h1>Registrar Cursos</h1>

            <form action="" method="post">
                <label for="txtNombreCurso">Digite el nombre</label><br>
                <input type="text" name="txtNombreCurso" id="txtNombreCurso" pattern="[A-Za-z0-9]{8,50}" required><br>

                <label for="numCreditos">Cantidad de creditos</label><br>
                <input type="number" name="numCreditos" id="numCreditos" pattern="[0-5]{1}" required><br>

                <label for="txtCodigoCurso">Digite el codigo</label><br>
                <input type="text" name="txtCodigoCurso" id="txtCodigoCurso" pattern="[0-9]{5}" required><br>

                <input type="hidden" name="csrf" id="csrf" value = "<?php echo $_SESSION["csrf"];?>"><br>

                <input type="submit" name="btnRegistrarCurso" id="btnRegistrarCurso" value="Registrar">
            </form>
        </div>
        <br>
            <div>
        <a href="index.php">Regresar</a>
        </div>
    </body>
</html>