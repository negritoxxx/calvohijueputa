<?php

    require_once ("libs/tools.php");
    require_once ("libs/conn.php");

    sesionSegura();
    limpiarEntradas();

    if (isset($_POST["csrf"])) {
        
        if ($_POST["csrf"] != $_SESSION["csrf"]) {
        
            header("Location: index.php");
            exit;
        }
    }

    if (isset($_POST["btnRecovery"])) {
        
        if (postInputsRecovery($_POST)) {
            
            $conn = conexionDB();
            $nuevaContraseña = recuperarClaveDB($conn, $_POST["txtUsuario"], $_POST["txtContraseña"]);
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
        <title>Recuperar Contraseña</title>
    </head>
    <style>
		body {
            
			background-image: url('https://img3.wallspic.com/crops/2/6/2/3/4/143262/143262-luz_blanca_en_cuarto_oscuro-3840x2160.jpg');
			background-size: cover;
			background-position: center;
			background-repeat: no-repeat;
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			font-family: Arial, sans-serif;
			color: #f2f2f2;
		}
		.container {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			height: 3vh;
			background-color: #f2f2f2;
		}
		
		
		input[type=text], input[type=password] {
			width: 120%;
			padding: 12px 20px;
			margin: 8px 0;
			box-sizing: border-box;
			border: 2px solid #ccc;
			border-radius: 4px;
			font-size: 16px;
			color: #f2f2f2;
		}
		
		button[type=submit] {
            background-position: left;
			background-color: #4CAF50;
			color: #fff;
			padding: 8px 10px;
			margin: 8px 0;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			font-size: 16px;
			width: 120%;
			transition: background-color 0.3s ease;
		}
		button[type=submit]:hover {
			background-color: #45a049;
		}
		
		label {
			font-size: 16px;
			font-weight: bold;
			color:#f2f2f2;
			display: block;
			margin-bottom: 5px;
			margin-top: 10px;
			text-transform: uppercase;
		}
        a {
			font-size: 16px;
			font-weight: bold;
			color: #f2f2f2;
			display: block;
			margin-bottom: 5px;
			margin-top: 10px;
			text-transform: uppercase;
            
		}
		h1 {
            text-align: center;
            color: #f2f2f2;
        }
	</style>
	
    <body>
    <br>
	<div>
            <h1>Recuperar Contraseña</h1>

            <form action="" method="post">
                <label for="txtUsuario">Escriba su Usuario</label><br>
                <input type="text" name="txtUsuario" id="txtUsuario" pattern="[^' ']+[A-Za-z0-9]{3,15}" required><br>

                <label for="txtContraseña">Digite la nueva contraseña</label><br>
                <input type="text" name="txtContraseña" id="txtContraseña" pattern="[^' ']+[A-Za-z0-9._%+-]{6,}" required><br>

                <input type="hidden" name="csrf" id="csrf" value = "<?php echo $_SESSION["csrf"];?>"><br>

                <button type="submit" name="btnRecovery" id="btnRecovery" value="Registrar">Restablecer Contraseña</button>
            </form>
			
			<a href="index.php">Regresar</a>
       		
			   <div>
       
    </body>
</html>