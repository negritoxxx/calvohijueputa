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
    <body>
        <main>
            <h1>Login</h1>
            <form action="" method="post">
                <label for="txtxUser">Usuario: </label>
                <input type="text" name="txtxUser" id="txtxUser" pattern="[A-Za-z0-9]{2,11}" required>

                <label for="txtPass">Contraseña: </label>
                <input type="password" name="txtPass" id="txtPass" pattern="[A-Za-z0-9._%+-]{4,30}" required>

                <input type="hidden" name="csrf" id="csrf" value = "<?php echo $_SESSION["csrf"];?>">

                <input type="submit" name="btnLogin" id="btnLogin" value="Login">
            </form>
            <a href="recovery.php">Recuperar Contraseña</a>
        </main>
    </body>
</html>