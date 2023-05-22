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

    if (isset($_POST["btnEliminar"])) {
        
        if (postInputsEliminar($_POST)) {

            if(eliminarDB($conn, $_POST["txtBusqueda"], $_POST["numEliminar"])) {

                echo '<script>alert("Eliminado correctamente");</script>';
            }
            else {
                
                echo '<script>alert("No se pudo eliminar el registro");</script>';
            }
        }
    }

    if (isset($_POST["btnEditar"])) {
        
        if (isset($_POST['csrf']) && isset($_POST['txtBusqueda']) && limitarCodigo($_POST['txtBusqueda']) &&
            isset($_POST['numEditar'])) {

            $_SESSION['editarAlumno'] = $_POST['txtBusqueda'];
            header('Location: editarAlumno.php'); 
        }
    }

    if (isset($_POST["btnEditarCurso"])) {
        
        if (isset($_POST['csrf']) && isset($_POST['txtBusqueda']) && limitarCodigoCurso($_POST['txtBusqueda']) &&
            isset($_POST['numEditar'])) {

            $_SESSION['editarCurso'] = $_POST['txtBusqueda'];
            header('Location: editarCurso.php'); 
        }
    }

    /*
    Crear variable anti CSRF.
    */
    $_SESSION["csrf"] = random_int(1000, 9999);
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro Alumnos</title>
    </head>
    <style>
        /* Estilos CSS */
        body {
            background-image: url('https://fondosmil.com/fondo/25330.jpg');
			background-size: cover;
            background-position: center;
			background-repeat: no-repeat;
			min-height: 100vh;
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
            
        }
        input[type="text"], select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            background-position: left;
        }
        input[type="submit"] {
            background-color: #f44336;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }
        input[type="submit"]:hover {
            background-color: #f44336;
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
            background-color: #f44336;
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
			background-color: #f44336;
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
            <form action="" method="post">
                <br>
                <input type="submit" name="logOut" id="logOut" value="Cerrar Sesión">
            </form>
        </div>
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
                                    <td>
                                        <form action="" method="post">
                                            <input type="hidden" name="txtBusqueda" id="txtBusqueda" value = "' . $alumnos["codigo"] . '">
                                            <input type="hidden" name="numEditar" id="numEditar" value = "0">
                                            <input type="hidden" name="csrf" id="csrf" value = "' . $_SESSION["csrf"] . '">
                                            <input type="submit" name="btnEditar" id="btnEditar" value="Editar">
                                        </form>
                                    </td>
                                    <td>
                                        <form action="" method="post">
                                            <input type="hidden" name="txtBusqueda" id="txtBusqueda" value = "' . $alumnos["codigo"] . '">
                                            <input type="hidden" name="numEliminar" id="numEliminar" value = "0">
                                            <input type="hidden" name="csrf" id="csrf" value = "' . $_SESSION["csrf"] . '">
                                            <input type="submit" name="btnEliminar" id="btnEliminar" value="Eliminar">
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
                                    <td>
                                        <form action="" method="post">
                                            <input type="hidden" name="txtBusqueda" id="txtBusqueda" value = "' . $cursos["codigo"] . '">
                                            <input type="hidden" name="numEditar" id="numEditar" value = "1">
                                            <input type="hidden" name="csrf" id="csrf" value = "' . $_SESSION["csrf"] . '">
                                            <input type="submit" name="btnEditarCurso" id="btnEditarCurso" value="Editar">
                                        </form>
                                    </td>
                                    <td>
                                        <form action="" method="post">
                                            <input type="hidden" name="txtBusqueda" id="txtBusqueda" value = "' . $cursos["codigo"] . '">
                                            <input type="hidden" name="numEliminar" id="numEliminar" value = "1">
                                            <input type="hidden" name="csrf" id="csrf" value = "' . $_SESSION["csrf"] . '">
                                            <input type="submit" name="btnEliminar" id="btnEliminar" value="Eliminar">
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

        
    </body>
</html>