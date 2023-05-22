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
    <style>
		body {
            background-image: url('https://img1.wallspic.com/crops/5/0/7/6/4/146705/146705-textil_floral_rojo_y_negro-3840x2160.jpg');
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
            background-color: #60a2ee;
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
            background-color:#60a2ee;
            color: black;
        }
        h1 {
			
			margin: 0;
			margin-bottom: 30px;
			font-size: 32px;
			font-weight: bold;
			color: #333;
			text-transform: uppercase;
            color: white;
            
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

        
    </body>
</html>