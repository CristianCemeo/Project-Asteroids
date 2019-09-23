<?php

session_name("asteroids");
session_start();

// Si la sesion no esta iniciada, que no pueda acceder al archivo poniendo la ruta en el navegador
if (!isset($_SESSION["usuario"])){
    header("Location:./index.php");
}

include_once("./inc/conexion.php");
include_once("./inc/funciones.php");

$mensajeError="";
$mensajeRegistrado="";

// Si modificamos la contraseña
if (isset($_REQUEST["confirmarModificar"])){
    $pass1=$_REQUEST["nuevaPass"];
    $pass2=$_REQUEST["nuevaPass2"];
    
    if ($pass1 == $pass2){
        $validarClave = comprobarClave($pass1);
        if ($validarClave == NULL){
            $mensajeRegistrado = cambiarClave($conexion, $_SESSION["idUsuario"], md5($pass1));
        } else {
            $mensajeError = $validarClave;
        }
    } else {
        $mensajeError = "¡Error, las contraseñas no coinciden!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Asteroids</title>
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <script src="./js/jquery-3.4.0.min.js"></script>
    <script src="./js/materialize.min.js"></script>
    <script src="./js/phaser.min.js"></script>
    <script src="./js/juego.js"></script>
    <script>
        // Inicializamos las pestañas de Materialize
        $(document).ready(function(){
            $('.tabs').tabs();
        });

        // Ocultamos la confirmacion de modificar la pass
        $(document).ready(function(){
            $(".rowConfirmar").hide();
        });
    </script>
</head>
<body>
    <?php logo() ?>
    <div class="row menu juego">
        <div class="row">
            <div class="col s12">
                <ul class="tabs tabs-fixed-width">
                    <li class="tab col s3"><a href="#juego" class="active">Juego</a></li>
                    <li class="tab col s3" id='ranking10'><a href="#ranking">Top 10</a></li>
                    <li class="tab col s3"><a href="#editar">Editar</a></li>
                    <li class="tab col s3"><a href="#salir">Salir</a></li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div id='juego' class="col s12"></div>
            <div id='ranking' class="col s12">
                <!-- Imprimimos el ranking -->
                <?php tablaRanking($conexion) ?>
            </div>
            <div id='editar' class="col s12">
                <form class="col s12" method="POST" action="#editar">
                    <div class="row">
                        <div class="input-field col s6 offset-s3">
                            <input placeholder="Nueva contraseña" required id="nuevaPass" name="nuevaPass" type="password" class="validate white-text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6 offset-s3">
                            <input placeholder="Repita contraseña" required id="nuevaPass2" name="nuevaPass2" type="password" class="validate white-text">
                        </div>
                    </div>

                    <p id="mensajeAceptar"><?php echo $mensajeRegistrado ?></p>
                    <p id="mensajeError"><?php echo $mensajeError ?></p>

                    <div class="row">
                        <div class="input-field col s12 center-align">
                            <button class="btnwaves-effect waves-yellow btn-large yellow accent-3 black-text" type="button" name="modificar" id="modificar">Modificar
                                <i class="material-icons right">edit</i>
                            </button>
                        </div>
                    </div>
                    <div class="row center-align rowConfirmar">
                        <p>¿Seguro que desea modificar su contraseña?</p>
                        <button class="btn waves-effect waves-red btn-small red darken-3" type="submit" name="confirmarModificar" id="confirmarModificar">Sí</button>
                    </div>
                </form>
            </div>
            <div id='salir' class="col s12 center-align">
                <p>¿Está seguro de que desea salir?</p>
                    <a href="./inc/cerrarSesion.php" id="salir" class="waves-effect waves-yellow btn yellow accent-3 black-text">Sí</a>
            </div>
        </div>
    </div>

    <script>

        // Borramos los mensajes al cambiar entre pestañas
        $(".tabs").on("click", function(){
            $("#mensajeAceptar").text("");
            $("#mensajeError").text("");
        });

        // Al pulsar el boton de modificar contraseña, hacemos visible el div de confirmar modificacion
        $("#modificar").click(function(){
            $(".rowConfirmar").show();;
        });

        // Bloquear tecla intro para que al modificar la pass el usuario le pida confirmacion
        $(document).keypress(function(event){
            if (event.which == '13') {
                event.preventDefault();
            }
        });
        
        // Al pulsar la pestaña del ranking, cargar el ranking de nuevo por si hay modificaciones en él
        $("#ranking10").click(function(){
            $.ajax({
                type: "GET",
                url: "./inc/ranking10.php",
                success: function(data){
                    $("#ranking").fadeIn(2000).html(data);
                }
            });
        });
    
    </script>
</body>
</html>