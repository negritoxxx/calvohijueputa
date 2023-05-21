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
    <body>
        <div>
            <h1>Recuperar Contraseña</h1>

            <form action="" method="post">
                <label for="txtUsuario">Escriba su Usuario</label><br>
                <input type="text" name="txtUsuario" id="txtUsuario" pattern="[^' ']+[A-Za-z0-9]{3,15}" required><br>

                <label for="txtContraseña">Digite la nueva contraseña</label><br>
                <input type="text" name="txtContraseña" id="txtContraseña" pattern="[^' ']+[A-Za-z0-9._%+-]{6,}" required><br>

                <input type="hidden" name="csrf" id="csrf" value = "<?php echo $_SESSION["csrf"];?>"><br>

                <input type="submit" name="btnRecovery" id="btnRecovery" value="Registrar">
            </form>
        </div>
        <br>
        <a href="index.php">Login</a>
    </body>
</html>