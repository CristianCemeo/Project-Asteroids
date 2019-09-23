<?php

session_name("asteroids");
session_start();

include_once("./conexion.php");
include_once("./funciones.php");

$puntuacionFinal = $_GET["puntuacion"];
$idUsuario = $_SESSION["idUsuario"];

anadirPuntuacion($conexion, $idUsuario, $puntuacionFinal);

?>