<?php

require_once('./conexion.php');
require_once('./funciones.php');

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

?>