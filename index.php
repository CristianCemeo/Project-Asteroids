<?php

include_once("./inc/conexion.php");
include_once("./inc/funciones.php");

$mensajeError="";

if (isset($_REQUEST["enviar"])){

    $usuario = $_REQUEST["usuario"];
    $pass = $_REQUEST["password"];

    // Comprobamos que tipo de usuario somos (jugador o administrador)
    if ($_REQUEST["tipo"]=="jugador"){
        // Utilizamos sentencias preparadas y parametrizadas para evitar la inyección de código
        $consulta = "SELECT * FROM `usuarios` WHERE nombre= ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->bind_param("s", $usuario);
        $sentencia->execute();
        $resultado = $sentencia->get_result();
        $fila=$resultado->fetch_assoc();
        if ($fila['pass']==md5($pass)){
            session_name("asteroids");
            session_start();
            $_SESSION["usuario"] = $usuario;
            $_SESSION["idUsuario"] = $fila["idUsuario"];
            header("Location: ./jugador.php");
            exit();
        } else {
            $mensajeError="Error, usuario y/o contraseña incorrectas.";
        }
    } else {
        // Utilizamos sentencias preparadas y parametrizadas para evitar la inyección de código
        $consulta = "SELECT * FROM `administradores` WHERE usuario= ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->bind_param("s", $usuario);
        $sentencia->execute();
        $resultado = $sentencia->get_result();
        $fila=$resultado->fetch_assoc();
        if ($fila['pass']==md5($pass)){
            session_name("asteroids");
            session_start();
            $_SESSION["admin"] = $usuario;
            header("Location: ./administrador.php");
            exit();
        } else {
            $mensajeError="Error, usuario y/o contraseña incorrectas.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Asteroids - Juego HTML5</title>
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <script src="./js/materialize.min.js"></script>
</head>

<body>
    <!-- Cargamos el logo -->
    <?php logo() ?>
    
    <div class="row menu">
        <form class="col s12" method="POST" action="#">
            <div class="row usu-pass-form">
                <div class="input-field col s6">
                    <input placeholder="Usuario" required id="usuario" name="usuario" type="text" class="validate white-text">
                </div>
                <div class="input-field col s6">
                    <input placeholder="Contraseña" required id="password" name="password" type="password" class="validate white-text">
                </div>
            </div>

            <p id="mensajeError"><?php echo $mensajeError ?></p>

            <div class="row">
                <div class="input-field col s12 center-align">
                    <button class="btn waves-effect waves-red btn-large red darken-3" type="submit" name="enviar" id="enviar">Aceptar
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 center-align">
                    <a href="./registro.php" class="waves-effect waves-yellow btn yellow accent-3 black-text">Registrarse</a>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 center-align">
                <p>
                    <label>
                        <input name="tipo" type="radio" value="jugador" checked />
                        <span>Jugador</span>
                    </label>
                </p>
                <p>
                    <label>
                        <input name="tipo" type="radio" value="admin" />
                        <span>Administrador</span>
                    </label>
                </p>
                </div>
            </div>
        </form>
    </div>

</body>

</html>