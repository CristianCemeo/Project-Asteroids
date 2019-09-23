<?php
require_once('./conexion.php');
require_once('./funciones.php');

// Recogemos las variables que nos manda el ajax
$page = $_GET['page'];
$ordenar = $_GET['ordenar'];
// Metemos la constante de número de registro por página en una variable
$rowsPerPage = NUM_ITEMS_BY_PAGE;
// Variable para saber donde empieza la busqueda en la BBDD
$offset = ($page - 1) * $rowsPerPage;
// Añadimos una parada de 1 segundo para que se vea la imagen de carga
sleep(1);
 
$resultado = $conexion->query(
    'SELECT ranking.idRanking, usuarios.nombre, usuarios.idUsuario, ranking.puntuacion, ranking.fecha FROM `ranking` INNER JOIN `usuarios` ON ranking.idUsuario=usuarios.idUsuario ORDER BY ' . $ordenar . ' LIMIT ' . $offset. ', ' . $rowsPerPage
);
if ($resultado->num_rows > 0) {
    // Variable que nos marca el número de registro en la base de datos
    $contador = $offset+1;
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
}

?>