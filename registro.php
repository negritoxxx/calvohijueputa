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
                    <input type="text" name="txtDocumento" id="txtDocumento" pattern="[A-Za-z ]{2, 30}" required><br>

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
    </body>
</html>