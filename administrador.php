<?php

session_name("asteroids");
session_start();

// Si la sesion no esta iniciada, que no pueda acceder al archivo poniendo la ruta en el navegador
if (!isset($_SESSION['admin'])){
    header("Location:./index.php");
}

include_once("./inc/conexion.php");
include_once("./inc/funciones.php");

$mensajeRegistrado= "";
$mensajeError="";
$mensajeEditado="";

// Agregar un registro al ranking
if (isset($_REQUEST["agregar"])){
    if (isset($_REQUEST["nombreUsuario"])){
        $id = $_REQUEST["nombreUsuario"];
        $puntuacion = $_REQUEST["puntuacion"];
        $fecha = $_REQUEST["fecha"];
        $mensajeRegistrado = agregarRegistro($conexion, $id, $puntuacion, $fecha);
    } else {
        $mensajeError = "¡Error, selecciona un usuario!";
    }        
    
}

// Editar un usuario
if (isset($_REQUEST["editarUsuario"])){
    $idUsuario = $_REQUEST["idUsuario"];
    $nuevoNombre = $_REQUEST["nuevoNombre"];
    $nuevaPass1 = $_REQUEST["nuevaPass"];
    $nuevaPass2 = $_REQUEST["nuevaPass2"];

    if ($nuevoNombre!=NULL || $nuevaPass1!=NULL || $nuevaPass2!=NULL){
        if ($nuevaPass1==$nuevaPass2){
            $mensaje = editarUsuario($conexion, $idUsuario, $nuevoNombre, $nuevaPass1);
            if ($mensaje=="Usuario editado correctamente."){
                $mensajeRegistrado = $mensaje;
            } else {
                $mensajeError = $mensaje;
            }
        } else {
            $mensajeError = "¡Error, la contraseña no coincide!";
        }
    } else {
        $mensajeError = "¡Error, no ha introducido ningún dato nuevo!";
    }
}

// Editar un registro del ranking
if (isset($_REQUEST["editar"])){
    $idRanking = $_REQUEST["idRanking"];
    $nuevaPuntuacion = $_REQUEST["nuevaPuntuacion"];
    $nuevaFecha = $_REQUEST["nuevaFecha"];

    if ($nuevaPuntuacion!=NULL || $nuevaFecha!=NULL){
        $mensajeRegistrado = editarRegistro($conexion, $idRanking, $nuevaPuntuacion, $nuevaFecha);
    } else {
        $mensajeError = "¡Error, no ha introducido ningún dato nuevo!";
    }
}

// Borrar usuario
if (isset($_REQUEST["borrarUsuario"])){
    $idUsuario = $_REQUEST["idUsuario"];

    $mensajeRegistrado = borrarUsuario($conexion, $idUsuario);
}

// Borrar registro del ranking
if (isset($_REQUEST["borrar"])){
    $idRanking = $_REQUEST["idRanking"];

    $mensajeRegistrado = borrarRegistro($conexion, $idRanking);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administración - Asteroids</title>
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <script src="./js/jquery-3.4.0.min.js"></script>
    <script src="./js/materialize.min.js"></script>
    <script>
        // Inicializamos las pestañas de Materialize
        $(document).ready(function(){
            $('.tabs').tabs();
        });

        // Inicializamos el reloj de Materialize para seleccionar una hora
        // $(document).ready(function(){
        //     $('.timepicker').timepicker({
        //         twelveHour: false,
        //         i18n: {
        //             cancel: "Cancelar",
        //             clear: "Limpiar",
        //             done: 'Aceptar'
        //         }
        //     });
        // });
        
        // Inicializamos el calendario de Materialize para seleccionar una fecha
        $(document).ready(function(){
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                firstDay: true,
                i18n: {
                    months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                    weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
                    weekdaysAbbrev: ['D','L','M','X','J','V','S'],
                    clear: 'Limpiar',
                    done: 'Aceptar',
                    cancel: "Cancelar",
                }
            });
        });

        // Inicializamos el select de Materialize
        $(document).ready(function(){
            $('select').formSelect();
        });

        // Inicializamos los modals (ventanas flotantes) de Materialize
        $(document).ready(function(){
            $('.modal').modal();
        });
    </script>
</head>
<body>
    <?php logo() ?>
    <div class="row menu admin">
        <div class="row">
            <div class="col s12">
                <ul class="tabs tabs-fixed-width">
                    <li class="tab col s3"><a href="#anadir" class="active">Añadir</a></li>
                    <li class="tab col s3"><a href="#editar">Editar</a></li>
                    <li class="tab col s3"><a href="#borrar">Borrar</a></li>
                    <li class="tab col s3"><a href="#salir">Salir</a></li>
                </ul>
            </div>
        </div>
        <div class="row">
            <!-- Pestañá añadir -->
            <div id='anadir' class="col s12">   
                <div class="row">
                    <form class="col s12" method="POST" action="#anadir">
                        <div class="row">
                            <div class="input-field col s6 offset-s3">
                                <select name="nombreUsuario">
                                    <?php
                                        selectDeUsuarios($conexion);
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">    
                            <div class="input-field col s6 offset-s3">
                                <input placeholder="Puntuación" required id="puntuacion" name="puntuacion" type="number" class="validate white-text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s6 offset-s3">
                                <input placeholder="Fecha" required id="fecha" name="fecha" type="text" class="datepicker white-text">
                            </div>
                        </div>

                        <p id="mensajeAceptar"><?php echo $mensajeRegistrado ?></p>
                        <p id="mensajeError"><?php echo $mensajeError ?></p>

                        <div class="row">
                            <div class="input-field col s12 center-align">
                                <button class="btn waves-effect waves-red btn-large red darken-3" type="submit" name="agregar" id="agregar">Agregar registro
                                    <i class="material-icons right">add</i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Pestaña editar -->
            <div id='editar' class="col s12">
                <p id="mensajeEditado"><?php echo $mensajeRegistrado ?></p>
                <p id="mensajeError2"><?php echo $mensajeError ?></p>
                <div class="row">
                    <div class="input-field col s5 center-align">
                        <p>Ordenar por:</p>
                    </div>
                    <div class="input-field col s5 center-align">
                        <select name="ordenarEditar" id="ordenarEditar">
                            <option value="ranking.puntuacion DESC"  selected>Puntuación DESC</option>
                            <option value="ranking.puntuacion ASC">Puntuación ASC</option>
                            <option value="usuarios.nombre DESC">Nombre DESC</option>
                            <option value="usuarios.nombre ASC">Nombre ASC</option>
                            <option value="ranking.fecha DESC">Fecha DESC</option>
                            <option value="ranking.fecha ASC">Fecha ASC</option>
                        </select>
                    </div>
                </div>
                <?php
                    menuEditar($conexion);
                ?>
            </div>
            <!-- Pestaña borrar -->
            <div id='borrar' class="col s12">
                <p id="mensajeBorrado"><?php echo $mensajeRegistrado ?></p>
                <div class="row">
                    <div class="input-field col s5 center-align">
                        <p>Ordenar por:</p>
                    </div>
                    <div class="input-field col s5 center-align">
                        <select name="ordenarBorrar" id="ordenarBorrar">
                            <option value="ranking.puntuacion DESC"  selected>Puntuación DESC</option>
                            <option value="ranking.puntuacion ASC">Puntuación ASC</option>
                            <option value="usuarios.nombre DESC">Nombre DESC</option>
                            <option value="usuarios.nombre ASC">Nombre ASC</option>
                            <option value="ranking.fecha DESC">Fecha DESC</option>
                            <option value="ranking.fecha ASC">Fecha ASC</option>
                        </select>
                    </div>
                </div>
                <?php
                    menuBorrar($conexion);
                ?>
            </div>
            <div id='salir' class="col s12 center-align">
                <p>¿Está seguro de que desea salir?</p>
                <a href="./inc/cerrarSesion.php" id="salir" class="waves-effect waves-yellow btn yellow accent-3 black-text">Sí</a>
            </div>
        </div>
    </div>
    <script>

        // Al clicar en una pestaña, borramos todos los mensajes
        $(".tabs").on("click", function(){
            $("#mensajeAceptar").text("");
            $("#mensajeEditado").text("");
            $("#mensajeBorrado").text("");
            $("#mensajeError").text("");
            $("#mensajeError2").text("");
        });
    
    </script>

    <script src="./js/modals.js"></script>
    <script src="./js/ajax.js"></script>
</body>
</html>