<?php

    require_once ("libs/tools.php");
    require_once ("libs/conn.php");

    sesionSegura();
    limpiarEntradas();
    $conn = conexionDB();

    if (isset($_POST["csrf"])) {
        
        if ($_POST["csrf"] != $_SESSION["csrf"]) {
        
            header("Location: menu.php");
            exit;
        }
    }

    if (!isset($_SESSION["User"])) {
        
        header("Location: index.php");
    }

    if ($_SESSION["privilegios"] != 0) {
        
        header("Location: index.php");
    }

    if (isset($_POST["logOut"])) {
        
        session_destroy();
        header("Location: index.php");
    }

    if (isset($_POST["btnInscribir"])) {
        
        if (postInputsInscribir($_POST)) {

            $inscribirCurso = inscribirAlumnoDB($conn, $_SESSION["id"], $_POST["txtCodigo"]);   
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
        <title>Alumnos</title>
    </head>
    <body>
        <div>
            <h1>Mis Cursos</h1>

            <?php

                $cursos = CursosAlumnoDB($conn, $_SESSION["codigo"]);

                echo "
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Código</th>
                            </tr>
                        </thead>
                        <tbody>
                ";

                foreach ($cursos as $cursos) {
                    
                    echo '
                        
                            <tr>
                                <td>' . $cursos["nombre"] . '</td>
                                <td>' . $cursos["codigo"] . '</td>

                            </tr>
                            
                    ';
                } 
                echo '
                        </tbody>
                    </table>
                '; 


            ?>
        </div>

        <div>
            <h1>Cursos a Inscribir</h1>

            <?php

                $cursosNoInscritos = CursosNoInscritosDB($conn, $_SESSION["id"]);

                echo "
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Código</th>
                            </tr>
                        </thead>
                        <tbody>
                ";

                foreach ($cursosNoInscritos as $cursosNoInscritos) {
                    
                    echo '
                        
                            <tr>
                                <td>' . $cursosNoInscritos["nombre"] . '</td>
                                <td>' . $cursosNoInscritos["codigo"] . '</td>
                                <td>
                                        <form action="" method="post">
                                            <input type="hidden" name="txtCodigo" id="txtCodigo" value = "' . $cursosNoInscritos["id"] . '">
                                            <input type="hidden" name="csrf" id="csrf" value = "' . $_SESSION["csrf"] . '">
                                            <input type="submit" name="btnInscribir" id="btnInscribir" value="Inscribir">
                                        </form>
                                </td>
                            </tr>
                            
                    ';
                } 
                echo '
                        </tbody>
                    </table>
                '; 


            ?>
        </div>

        <div>
            <form action="" method="post">
                <br>
                <input type="submit" name="logOut" id="logOut" value="Cerrar Sesión">
            </form>
        </div>
    </body>
</html>