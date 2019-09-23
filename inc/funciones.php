<?php

// Constante número de registros a sacar por página
define('NUM_ITEMS_BY_PAGE', 10);

// Imprimir el logo
function logo(){
    echo '<img class="imgLogo" src="./img/Asteroids_logo.png" alt="Asteroids">';
}

// Comprobación, al registrarse, de cuantos caracteres tiene el nombre del usuario (>4 y <20)
function comprobarUsuario($usuario){
    $usuario = trim($usuario);
    if (strlen($usuario)<4 || strlen($usuario)>20){
        $mensaje = "¡El usuario debe tener de 4 a 20 caracteres!";
    } else {
        $mensaje = NULL;
    }
    return $mensaje;
} 

// Comprobación, al registrarse, de cuantos caracteres tiene la contraseña del usuario (>4 y <12)
function comprobarClave($clave) {
    $clave = trim($clave);
    if ( strlen($clave)<4 || strlen($clave)>12 ) {
        $mensaje = "¡La contraseña debe tener de 4 a 12 caracteres!";
    } else {
        $mensaje = NULL;
    }
    return $mensaje;
}

// Formatear la fecha al meterla en un input y pasarla a la BBDD
function formatoFechaBBDD($fecha){
    $separar = explode("/", $fecha);
    $dia = $separar[0];
    $mes = $separar[1];
    $anio = $separar[2];
    return $anio . "-" . $mes . "-" . $dia;
}

// Formatear fecha al sacarla de la BBDD e imprimirla por pantalla
function formatoFecha($fecha){
    $separar = explode("-", $fecha);
    $dia = $separar[2];
    $mes = $separar[1];
    $anio = $separar[0];
    return $dia . "/" . $mes . "/" . $anio;
}

// Imprimir la tabla del ranking a los usuarios
function tablaRanking($conexion) {
    $consulta = "SELECT usuarios.nombre, ranking.puntuacion, ranking.fecha FROM `ranking` INNER JOIN `usuarios` ON ranking.idUsuario=usuarios.idUsuario ORDER BY `ranking`.`puntuacion` DESC";
    $resultado = $conexion->query($consulta);
    $contador = 0;
    if ($resultado->num_rows < 10){
        $contador = $resultado->num_rows;
    } else {
        $contador = 10;
    }
    if ($contador == 0){
        echo "<p>Todavía no hay ningún registro.</p>";
    } else {
        echo "<table class='centered'>
        <thead>
            <tr>
                <th>#</th>
                <th>Usuario</th>
                <th>Puntos</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>";
        for ($i=0; $i < $contador; $i++) { 
            echo "<tr>";
            echo "<td>" . ($i+1) . "</td>";
            $fila=$resultado->fetch_assoc();
            foreach ($fila as $key => $value) {
                if ($key == "fecha"){
                    $fechaFormateada = formatoFecha($value);
                    echo "<td>" . $fechaFormateada . "</td>";
                } else {
                    echo "<td>" . $value . "</td>";
                }
            }
            echo "</tr>";
        }
        echo "</tbody>
            </table>
            ";
    }
}

// Crea los diferentes options de usuario de un select
function selectDeUsuarios($conexion){
    $consulta = "SELECT idUsuario, nombre FROM `usuarios` ORDER BY nombre ASC";
    $resultado = $conexion->query($consulta);
    echo '<option value="" disabled selected>Seleccione usuario</option>';
    while ($fila = $resultado->fetch_assoc()){
        echo '<option value="' . $fila["idUsuario"] . '">' . $fila["nombre"] . '</option>';
    }
}

// Menu editar del administrador (tabla)
function menuEditar($conexion){
    $consulta = "SELECT usuarios.nombre, ranking.puntuacion FROM `ranking` INNER JOIN `usuarios` ON ranking.idUsuario=usuarios.idUsuario ORDER BY `ranking`.`puntuacion` DESC";
    $resultado = $conexion->query($consulta);
    $num_total_rows = $resultado->num_rows;
    if ($num_total_rows > 0) {
        // Dividimos el número total de lineas que nos ha devuelto la consulta 
        // entre el número de registro que queremos que tenga cada página y redondeamos 
        // el número hacia arriba
        $num_pages = ceil($num_total_rows / NUM_ITEMS_BY_PAGE);
        $resultado = $conexion->query(
            'SELECT ranking.idRanking, usuarios.nombre, usuarios.idUsuario, ranking.puntuacion, ranking.fecha FROM `ranking` INNER JOIN `usuarios` ON ranking.idUsuario=usuarios.idUsuario ORDER BY `ranking`.`puntuacion` DESC LIMIT 0, '.NUM_ITEMS_BY_PAGE
        );
        if ($resultado->num_rows > 0) {
            $contador = 1;
            echo "<div id='rankingEditar' class='items col s12'>";
            echo "<table class='centered'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Puntos</th>
                    <th>Fecha</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody>";
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . ($contador) . "</td>";
                echo '<td><a onclick="openModalUsuario(' . $fila["idUsuario"] .')">' . $fila["nombre"] . '</a></td>';
                foreach ($fila as $key => $value) {
                    if ($key == "fecha"){
                        $fechaFormateada = formatoFecha($value);
                        echo "<td>" . $fechaFormateada . "</td>";
                    } else if ($key!="idRanking" && $key!="idUsuario" && $key!="nombre"){
                        echo "<td>" . $value . "</td>";
                    }
                }
                echo '<td><a class="waves-effect waves-light btn editar" onclick="openModalEditar(' . $fila["idRanking"] .')"><i class="material-icons right black-text">edit</i></a></td>';
                echo "</tr>";
                $contador++;
            }
            ?>
            <div id="modal1" class="modal editar">
                <div class="modal-content">
                    <h4 style="text-align:center;">Editar registro</h4>
                    <div id="modal_content" class="row"></div>
                </div>
            </div>
            <div id="modal2" class="modal editarUsuario">
                <div class="modal-content">
                    <h4 style="text-align:center;">Editar usuario</h4>
                    <div id="modal_content" class="row"></div>
                </div>
            </div>
            <?php
            echo "</tbody>
            </table>
            ";
            echo '</div>';
        } else {
            echo "<p>Todavía no hay ningún registro.</p>";
        }
    
        if ($num_pages > 1) {
            echo '<div class="row">';
            echo '<div class="input-field col s12 center-align">';
            echo '<ul class="pagination editar">';
            for ($i=1;$i<=$num_pages;$i++) {
                $class_active = '';
                if ($i == 1) {
                    $class_active = 'active';
                }
                echo '<li class="page-item '.$class_active.'"><a class="page-link" href="#" dataEditar="'.$i.'">'.$i.'</a></li>';
            }
            echo '</ul>';
            echo '</div>';
            echo '</div>';
        }
    }
}

// Menu borrar del administrador (tabla)
function menuBorrar($conexion){
    $consulta = "SELECT usuarios.nombre, ranking.puntuacion FROM `ranking` INNER JOIN `usuarios` ON ranking.idUsuario=usuarios.idUsuario ORDER BY `ranking`.`puntuacion` DESC";
    $resultado = $conexion->query($consulta);
    $num_total_rows = $resultado->num_rows;
    if ($num_total_rows > 0) {
        // Dividimos el número total de lineas que nos ha devuelto la consulta 
        // entre el número de registro que queremos que tenga cada página y redondeamos 
        // el número hacia arriba
        $num_pages = ceil($num_total_rows / NUM_ITEMS_BY_PAGE);
        $resultado = $conexion->query(
            'SELECT ranking.idRanking, usuarios.nombre, usuarios.idUsuario, ranking.puntuacion, ranking.fecha FROM `ranking` INNER JOIN `usuarios` ON ranking.idUsuario=usuarios.idUsuario ORDER BY `ranking`.`puntuacion` DESC LIMIT 0, '.NUM_ITEMS_BY_PAGE
        );
        if ($resultado->num_rows > 0) {
            $contador = 1;
            echo "<div id='rankingBorrar' class='items col s12'>";
            echo "<table class='centered'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Puntos</th>
                    <th>Fecha</th>
                    <th>Borrar</th>
                </tr>
            </thead>
            <tbody>";
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . ($contador) . "</td>";
                echo '<td><a onclick="openModalBorrarUsuario(' . $fila["idUsuario"] .')">' . $fila["nombre"] . '</a></td>';
                foreach ($fila as $key => $value) {
                    if ($key == "fecha"){
                        $fechaFormateada = formatoFecha($value);
                        echo "<td>" . $fechaFormateada . "</td>";
                    } else if ($key!="idRanking" && $key!="idUsuario" && $key!="nombre"){
                        echo "<td>" . $value . "</td>";
                    }
                }
                echo '<td><a class="waves-effect waves-light btn borrar" onclick="openModalBorrar(' . $fila["idRanking"] .')"><i class="material-icons right">delete</i></a></td>';
                echo "</tr>";
                $contador++;
            }
            ?>
            <div id="modal3" class="modal borrar">
                <div class="modal-content">
                    <h4 style="text-align:center;">Borrar registro</h4>
                    <div id="modal_content" class="row"></div>
                </div>
            </div>
            <div id="modal4" class="modal borrarUsuario">
                <div class="modal-content">
                    <h4 style="text-align:center;">Borrar usuario</h4>
                    <div id="modal_content" class="row"></div>
                </div>
            </div>
            <?php
            echo "</tbody>
            </table>
            ";
            echo '</div>';
        } else {
            echo "<p>Todavía no hay ningún registro.</p>";
        }
    
        if ($num_pages > 1) {
            echo '<div class="row">';
            echo '<div class="input-field col s12 center-align">';
            echo '<ul class="pagination borrar">';
            for ($i=1;$i<=$num_pages;$i++) {
                $class_active = '';
                if ($i == 1) {
                    $class_active = 'active';
                }
                echo '<li class="page-item '.$class_active.'"><a class="page-link" href="#" dataBorrar="'.$i.'">'.$i.'</a></li>';
            }
            echo '</ul>';
            echo '</div>';
            echo '</div>';
        }
    }
}

// Crear nuevo usuario
function registrarse($conexion, $usuario, $clave){
    $consulta = "SELECT * FROM `usuarios` WHERE nombre='" . $usuario . "'";
    $resultado = $conexion->query($consulta);
    if ($resultado->num_rows == 0){
        $conexion->query("INSERT INTO `usuarios` (`idUsuario`, `nombre`, `pass`) VALUES (NULL, '" . $usuario . "', '" . md5($clave) . "');");

        return "Usuario registrado correctamente.";
    } else {
        return "¡Nombre de usuario ya en uso!";
    }
}

// Cambiar contraseña desde el usuario
function cambiarClave($conexion, $idUsuario, $clave){
    $consulta = "UPDATE `usuarios` SET `pass` = '" . $clave . "' WHERE `usuarios`.`idUsuario` = " . $idUsuario . ";";
    $conexion->query($consulta);
    return "Contraseña actualizada correctamente.";
}

// Añadir nuevo registro al ranking
function agregarRegistro($conexion, $id, $puntuacion, $fecha){
    $fechaFormateada = formatoFechaBBDD($fecha);
    $consulta= "INSERT INTO `ranking` (`idRanking`, `idUsuario`, `puntuacion`, `fecha`) VALUES (NULL, '" . $id . "', '" . $puntuacion . "', '" . $fechaFormateada . "');";
    $conexion->query($consulta);
    return "Registro añadido correctamente.";
}

// Editar nombre y/o contraseña de un usuario
function editarUsuario($conexion, $idUsuario, $nombre, $clave){
    $nombre = trim($nombre);
    $clave = trim($clave);
    if ($nombre!=NULL){
        $consulta = "SELECT * FROM `usuarios` WHERE nombre='" . $nombre . "'";
        $resultado = $conexion->query($consulta);
        if ($resultado->num_rows > 0){
            return "¡El nombre ya está en uso!";
        }
    }
    
    // Comprobamos si hemos metido solo un valor o todos
    if ($nombre==NULL){
        $comprobarPass=comprobarClave($clave);
        if ($comprobarPass==NULL){
            $consulta = "UPDATE `usuarios` SET `pass` = '" . md5($clave) . "' WHERE `usuarios`.`idUsuario` = " . $idUsuario . ";";
        } else {
            return $comprobarPass;
        }
    } else if ($clave==NULL){
        $comprobarNombre = comprobarUsuario($nombre);
        if ($comprobarNombre==NULL){
            $consulta = "UPDATE `usuarios` SET `nombre` = '" . $nombre . "' WHERE `usuarios`.`idUsuario` = " . $idUsuario . ";";
        } else {
            return $comprobarNombre;
        }
    } else {
        $comprobarPass=comprobarClave($clave);
        $comprobarNombre = comprobarUsuario($nombre);
        if ($comprobarNombre==NULL && $comprobarPass==NULL){
            $consulta = "UPDATE `usuarios` SET `nombre` = '" . $nombre . "', `pass` = '" . md5($clave) . "' WHERE `usuarios`.`idUsuario` = " . $idUsuario . ";";
        } else {
            return $comprobarNombre . "<br>" . $comprobarPass;
        }
    }
    $conexion->query($consulta);
    return "Usuario editado correctamente.";
}

// Editar registro del ranking
function editarRegistro($conexion, $idRanking, $nuevaPuntuacion, $nuevaFecha){
    if ($nuevaFecha!=NULL){
        $fechaFormateada = formatoFechaBBDD($nuevaFecha);
    }

    if ($nuevaPuntuacion==NULL){
        $consulta = "UPDATE `ranking` SET `fecha` = '" . $fechaFormateada . "' WHERE `ranking`.`idRanking` = " . $idRanking . ";";
    } else if ($nuevaFecha==NULL){
        $consulta = "UPDATE `ranking` SET `puntuacion` = '" . $nuevaPuntuacion . "' WHERE `ranking`.`idRanking` = " . $idRanking . ";";
    } else {
        $consulta = "UPDATE `ranking` SET `puntuacion` = '" . $nuevaPuntuacion . "', `fecha` = '" . $fechaFormateada . "' WHERE `ranking`.`idRanking` = " . $idRanking . ";";
    }
    $conexion->query($consulta);
    return "Registro editado correctamente.";
}

function borrarUsuario($conexion, $idUsuario){
    $consulta = "DELETE FROM `usuarios` WHERE `usuarios`.`idUsuario` = " . $idUsuario . "";
    $conexion->query($consulta);
    return "Usuario borrado correctamente.";
}

function borrarRegistro($conexion, $idRanking){
    $consulta = "DELETE FROM `ranking` WHERE `ranking`.`idRanking` = " . $idRanking . "";
    $conexion->query($consulta);
    return "Registro borrado correctamente.";
}

// Añadir puntuacion del juego
function anadirPuntuacion($conexion, $idUsuario, $puntuacion){
    $fecha = date("Y") . "-" . date("m") . "-" . date("d");
    $consulta = "INSERT INTO `ranking` (`idRanking`, `idUsuario`, `puntuacion`, `fecha`) VALUES (NULL, '" . $idUsuario . "', '" . $puntuacion . "', '" . $fecha . "');";
    $conexion->query($consulta);
}

?>