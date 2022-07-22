<?php
//print_r($_POST);

require('funciones.php');
//include('php/configuraciones.class.php');
error_reporting(E_ALL);
ConectaSQL('local');


$sql = 'update wifihmo set ser="'.$_POST['laserie'].'", mac="'.$_POST['lamac'].'" where ident="'.$_POST['multi'].'" ';
$result = $conn->query($sql);

$jsonSearchResults[] =  array(
    'success' => "YES"
);
echo json_encode ($jsonSearchResults);

return false;

?>