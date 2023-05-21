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


    if (isset($_POST["btnBusqueda"])) {
        
        if (postInputsBuscar($_POST)) {

            $busqueda = busquedaDB($conn, $_POST["txtBusqueda"], $_POST["numBusqueda"]);   
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro Alumnos</title>
    </head>
    <body>
        <div>
            <h1>Busqueda</h1>

            <form action="" method="post">
                <label for="numBusqueda">Seleccione qué desea buscar</label>
                <select name="numBusqueda" id="numBusqueda" required>
                    <option value="0">Alumno</option>
                    <option value="1">Curso</option>
                </select><br>

                <label for="txtBusqueda"> Digite el código de la busqueda</label>
                <input type="text" name="txtBusqueda" id="txtBusqueda" pattern="[0-9]{5,10}" required><br>

                <input type="hidden" name="csrf" id="csrf" value = "<?php echo $_SESSION["csrf"];?>"><br>

                <input type="submit" name="btnBusqueda" id="btnBusqueda" value="Buscar">
            </form>

            <?php
                if (isset($busqueda)) {

                    if (is_array($busqueda)) {
                        
                        if ($_POST["numBusqueda"] == 0) {

                            $cursos = CursosAlumnoDB($conn, $_POST["txtBusqueda"]);
                        
                            echo "<p>El nombre completo del alumno es: " . $busqueda["nombre_completo"] . "</p>
                                <p>El documento del alumno es: " . $busqueda["documento"] . "</p>
                                <p>El código del alumno es: " . $busqueda["codigo"] . "</p>
                                <p>El alumno esta registrado en las siguientes materias:</p>
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
                        }
                        else {

                            $alumnosInscritos = AlumnosInscritosDB($conn, $_POST["txtBusqueda"]);
                            
                            echo "<p>El nombre del curso es: " . $busqueda["nombre"] . "</p>
                                <p>Los creditos del curso son: " . $busqueda["creditos"] . "</p>
                                <p>El código del curso es: " . $busqueda["codigo"] . "</p>
                                <p>El curso tiene los siguientes alumnos inscritos: </p>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Código</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            ";

                            foreach ($alumnosInscritos as $alumnosInscritos) {
                                
                                echo '
                                    
                                        <tr>
                                            <td>' . $alumnosInscritos["nombre_completo"] . '</td>
                                            <td>' . $alumnosInscritos["codigo"] . '</td>
        
                                        </tr>
                                        
                                ';
                            } 
                            echo '
                                    </tbody>
                                </table>
                            ';
                        }
                    }
                    else {
                    
                        echo "<p>No se encontró ningún registro con lo solicitado</p>";
                    }
                }
            ?>
        </div>

        <div>
            <h1>Alumnos</h1>

            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Código</th>
                        <th>Documento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $alumnos = listaAlumnosDB($conn);

                        foreach ($alumnos as $alumnos) {
                            
                            echo '
                                <tr>
                                    <td>' . $alumnos["nombre_completo"] . '</td>
                                    <td>' . $alumnos["codigo"] . '</td>
                                    <td>' . $alumnos["documento"] . '</td>
                                    <td>
                                        <form action="" method="post">
                                            <input type="hidden" name="txtBusqueda" id="txtBusqueda" value = "' . $alumnos["codigo"] . '">
                                            <input type="hidden" name="numBusqueda" id="numBusqueda" value = "0">
                                            <input type="hidden" name="csrf" id="csrf" value = "' . $_SESSION["csrf"] . '">
                                            <input type="submit" name="btnBusqueda" id="btnBusqueda" value="Detalles">
                                        </form>
                                    </td>

                                </tr>
                            ';
                        }
                    ?>
                </tbody>
            </table>
        </div>


        <div>
            <h1>Cursos</h1>

            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Código</th>
                        <th>Creditos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $cursos = listaCursosDB($conn);

                        foreach ($cursos as $cursos) {
                            
                            echo '
                                <tr>
                                    <td>' . $cursos["nombre"] . '</td>
                                    <td>' . $cursos["codigo"] . '</td>
                                    <td>' . $cursos["creditos"] . '</td>
                                    <td>
                                        <form action="" method="post">
                                            <input type="hidden" name="txtBusqueda" id="txtBusqueda" value = "' . $cursos["codigo"] . '">
                                            <input type="hidden" name="numBusqueda" id="numBusqueda" value = "1">
                                            <input type="hidden" name="csrf" id="csrf" value = "' . $_SESSION["csrf"] . '">
                                            <input type="submit" name="btnBusqueda" id="btnBusqueda" value="Detalles">
                                        </form>
                                    </td>

                                </tr>
                            ';
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <br>
        <div>
            <a href="registro.php">Registrar</a>
        </div>

        <div>
            <form action="" method="post">
                <br>
                <input type="submit" name="logOut" id="logOut" value="Cerrar Sesión">
            </form>
        </div>
    </body>
</html>