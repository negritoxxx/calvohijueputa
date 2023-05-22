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

    if (isset($_SESSION["User"])) {
        
        if ($_SESSION["privilegios"] == 1) {
            
            header("Location: Admin.php");
        }
        elseif ($_SESSION["privilegios"] == 0) {
            
            header("Location: menu.php");
        }
        else {
            
            session_destroy();
        }
    }

    if(isset($_POST["btnLogin"])){
		
		if (postInputsLogin($_POST)) {
            
            $conn = conexionDB();

            if(loginDB($conn, $_POST["txtxUser"], $_POST["txtPass"])){

                if ($_SESSION["privilegios"] == 1) {
                    
                    header("location: Admin.php");
                }
                else {
                    
                    header("location: menu.php");
                }
            }
            else{

                echo '<script>alert("Clave o Usuario no coinciden");</script>';
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
        <title>Inicio</title>
    </head>
    <style>
		body {
			background-image: url('https://img2.wallspic.com/crops/8/3/5/9/6/169538/169538-negro-gris-de_colores-azul-luz-3840x2160.png');
			background-size: cover;
			background-position: center;
			background-repeat: no-repeat;
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			font-family: Arial, sans-serif;
		}
		.container {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			height: 3vh;
			background-color: #f2f2f2;
		}
		h1 {
			text-align: center;
			margin: 0;
			margin-bottom: 30px;
			font-size: 32px;
			font-weight: bold;
			color: #333;
			text-transform: uppercase;
            color: #fff;
            
		}
		form {
			width: 350px;
			padding: 30px;
			background-color: #fff;
			border: 1px solid #ccc;
			box-shadow: 0 0 10px rgba(0,0,0,0.3);
			border-radius: 5px;
			margin-top: 50px;
		}
		input[type=text], input[type=password] {
			width: 100%;
			padding: 12px 20px;
			margin: 8px 0;
			box-sizing: border-box;
			border: 2px solid #ccc;
			border-radius: 4px;
			font-size: 16px;
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
			width: 100%;
			transition: background-color 0.3s ease;
		}
		button[type=submit]:hover {
			background-color: #45a049;
		}
		span {
			color: red;
			font-size: 14px;
			margin-top: 5px;
			display: block;
		}
		label {
			font-size: 16px;
			font-weight: bold;
			color: #333;
			display: block;
			margin-bottom: 5px;
			margin-top: 10px;
			text-transform: uppercase;
		}
        a {
			font-size: 16px;
			font-weight: bold;
			color: #333;
			display: block;
			margin-bottom: 5px;
			margin-top: 10px;
			text-transform: uppercase;
            color: rgb(8, 8, 8);
		}
	</style>
    <body>
        <main>
            <h1>Login</h1>
            <form action="" method="post">
                <label for="txtxUser">Usuario: </label>
                <input type="text" name="txtxUser" id="txtxUser" pattern="[A-Za-z0-9]{2,11}" required>

                <label for="txtPass">Contraseña: </label>
                <input type="password" name="txtPass" id="txtPass" pattern="[A-Za-z0-9._%+-]{4,30}" required>

                <input type="hidden" name="csrf" id="csrf" value = "<?php echo $_SESSION["csrf"];?>">

                <button type="submit" name="btnLogin" id="btnLogin" value="Login">Ingresar</button>
                </form>
            <div class="container">
            <a href="recovery.php">Recuperar Contraseña</a>
            </div>
        </main>
    </body>
</html>