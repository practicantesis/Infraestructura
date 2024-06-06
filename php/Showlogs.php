<?php
include 'configuraciones.class.php';
require('funciones.php');

$conn=ConectaSQL('firewall');
//$conn=$db;
$tb ="<div class='table-responsive'><table class='table table-striped' name='TablaLog' id='TablaLog'><thead><tr><th>Usuario</th><th>Actividad</th><th>Hora</th><th>Cambio</th></thead></tr><tbody>";
$sql = "select * from log";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $tb .="<tr><td>".$row["usuario"]."</td><td>".$row["actividad"]."</td><td>".$row["hora"]."</td><td>".$row["valorafectado"]."</td></tr>";
            
        }
    }

$tb .="</tbody></table></div>";

$jsonSearchResults[] =  array(
    'success' => 'YES',
    'error' => 'err',
    'data' => $tb,
);
echo json_encode ($jsonSearchResults);

return false;
?>
