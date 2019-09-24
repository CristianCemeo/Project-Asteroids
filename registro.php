<?php

include_once("./inc/conexion.php");
include_once("./inc/funciones.php");

$mensajeError="";
$mensajeRegistrado="";

if (isset($_REQUEST["enviar"])){
    $usuario = $_REQUEST["usuario"];
    $pass1 = $_REQUEST["password"];
    $pass2 = $_REQUEST["password2"];

    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify'; 
    $recaptcha_secret = 'CLAVE_PRIVADA'; 
    $recaptcha_response = $_POST['recaptcha_response']; 
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response); 
    $recaptcha = json_decode($recaptcha); 

	// Comprobamos que no es un robot
    if($recaptcha->score >= 0.8){
        // Comprobamos el nombre de usuario introducido
        $validarUsuario = comprobarUsuario($usuario);
        if ($validarUsuario == NULL){
            // Comprobamos la contraseña introducida
            $validarPass = comprobarClave($pass1);
            if ($validarPass == NULL){
                // Comprobamos que las dos contraseñas concuerdan
                if ($pass1 == $pass2){
                    $mensaje = registrarse($conexion, $usuario, $pass1);
                    if ($mensaje=="Usuario registrado correctamente."){
                        $mensajeRegistrado = $mensaje;
                    } else {
                        $mensajeError = $mensaje;
                    }
                } else {
                    $mensajeError = "¡La contraseña no coincide!";
                }
            } else {
                $mensajeError = $validarPass;
            }
        } else {
            $mensajeError = $validarUsuario;
        }
    }else{
        $mensajeError = "Error con el captcha. Si es humano, intentelo de nuevo."
    }    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registro - Asteroids</title>
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <script src="./js/materialize.min.js"></script>
    <script src='https://www.google.com/recaptcha/api.js?render=CLAVE_PUBLICA'></script>
    <script>
        grecaptcha.ready(function() {
        grecaptcha.execute('CLAVE_PUBLICA', {action: 'Project Asteroids'})
        .then(function(token) {
        var recaptchaResponse = document.getElementById('recaptchaResponse');
        recaptchaResponse.value = token;
        });});
    </script>
</head>
<body>
    <?php logo() ?>
    <div class="row menu">
        <form class="col s12" method="POST" action="#">
            <div class="row">
                <div class="input-field col s12 center-align">
                    <input placeholder="Usuario (no podrá ser modificado)" required id="usuario" name="usuario" type="text" class="validate white-text">
                </div>
            </div>

            <div class="row">
                    <div class="input-field col s6 center-align">
                        <input placeholder="Contraseña" required id="password" name="password" type="password" class="validate white-text">
                    </div>
                    <div class="input-field col s6 center-align">
                            <input placeholder="Repita contraseña" required id="password2" name="password2" type="password" class="validate white-text">
                    </div>
            </div>

            <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

            <p id="mensajeAceptar"><?php echo $mensajeRegistrado ?></p>
            <p id="mensajeError"><?php echo $mensajeError ?></p>

            <div class="row">
                <div class="input-field col s12 center-align">
                    <button class="btn waves-effect waves-red btn-large red darken-3" type="submit" name="enviar" id="enviar">Registrarse
                        <i class="material-icons right">add</i>
                    </button>
                </div>
            </div>            
        </form>
        <div class="row">
                <div class="input-field col s12 center-align">
                    <a href="./index.php" class="waves-effect waves-yellow btn yellow accent-3 black-text">Volver
                        <i class="material-icons right">arrow_back</i>
                    </a>
                </div>
        </div>
    </div>

</body>
</html>
