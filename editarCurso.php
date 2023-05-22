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

    if (!isset($_SESSION["editarCurso"])) {
        
        header("Location: Admin.php");
    }

    if (isset($_POST["logOut"])) {
        
        session_destroy();
        header("Location: index.php");
    }

    if (isset($_POST["btnEditar"])) {
        
        if (postInputsEditarCursos($_POST)) {

            $registro = editarCursoDB($conn, $_SESSION["editarCurso"], $_POST["txtNombreCurso"], $_POST["numCreditos"]);
            
            if ($registro) {

                header("Location: Admin.php");
            }
            else {
                
                echo '<script>alert("No se pudo editar el registro");</script>';
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
        <title>Editar Curso</title>
    </head>
    <style>
		body {
            
			background-image: url('https://img2.wallspic.com/crops/9/0/9/9/3/139909/139909-pintura_abstracta_negra_y_roja-3840x2160.jpg');
			background-size: cover;
			background-color: #f2f2f2;
			font-family: Arial, sans-serif;
            justify-content: center;
			min-height: 100vh;
			
			
		}
		.container {
			margin: auto;
			width: 60%;
			padding: 20px;
			background-color: white;
			border-radius: 10px;
			box-shadow: 0px 0px 10px rgb(248, 248, 248);
			text-align: center;
			justify-content: center;
		}
		input[type=text], input[type=password] {
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
		button {
			background-color: #4CAF50;
			color: white;
			padding: 14px 20px;
			margin: 8px 0;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			width: 60%;
			
		}
		button:hover {
			background-color: #45a049;
		}
        h1 {
            text-align: center;
            color: #f2f2f2;
        }
		.cancelbtn {
			background-color: #f44336;
		}
		.imgcontainer {
			text-align: center;
			margin: 24px 0 12px 0;
		}
        label {
            font-size: 16px;
			font-weight: bold;
			
			display: block;
			margin-bottom: 5px;
			margin-top: 10px;
			text-transform: uppercase;
            color: #f2f2f2;
            
        }
        button:hover {
			background-color: #45a049;
		}
        a {
			color: #f44336;
            font-size: 26px;
			font-weight: bold;
			justify-content: left;
			text-align: left;
			display: flex;
		}
	</style>
    <body>
        <h1>Editar Curso</h1>

        <form action="" method="post">
            <label for="txtNombreCurso">Digite el nombre</label><br>
            <input type="text" name="txtNombreCurso" id="txtNombreCurso" pattern="[A-Za-z0-9]{8,50}" required><br>

            <label for="numCreditos">Cantidad de creditos</label><br>
            <input type="number" name="numCreditos" id="numCreditos" pattern="[0-5]{1}" required><br>

            <input type="hidden" name="csrf" id="csrf" value = "<?php echo $_SESSION["csrf"];?>"><br>

            <input type="submit" name="btnEditar" id="btnEditar" value="Registrar">
        </form>
        <br>
        <br>
        <br>
        <a href="Admin.php">Regresar</a>
        <div>
    </body>
</html>