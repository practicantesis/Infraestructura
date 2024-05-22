<?php
require('funciones.php');
include 'configuraciones.class.php';
global $conn;

$conn=ConectaSQL('firewall');


$html = "<table  class='table-striped table table-bordered table-responsive' id='logTable' name='logTable'>";
$html .= '<thead class="table-dark text-center"><tr><th>usuario</th><th>tipo</th><th>tipo</th><th>fecha</th><tr></thead><tbody>';

    $sql = "select * from log";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($value = $result->fetch_assoc()) {
            $html .= "<tr><td>".$value['usuario']."</td><td>".$value['tipo']."</td><td><small>".$value['tipo']."</small></td><td><small>".$value['fecha']."</small></td><tr>";
        }
    }


$html .= "</tbody></table>";




//echo $html;
$jsonSearchResults[] =  array(
    'success' => 'YES',
    'data' => $html
);

//echo $forma;

echo json_encode ($jsonSearchResults);
return false;




?>


