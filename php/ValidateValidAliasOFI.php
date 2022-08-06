<?php
require('funciones.php');
include('configuraciones.class.php');
$db=ConectaSQL('firewall');


$rpw=GetOficinaValidAliases();
//print_r($rpw);

if (in_array($_POST[multi] , $rpw)) {
    $success = "YES";
} else {
	$success = "NO";
}


$jsonSearchResults[] =  array(
    'success' => $success,
    'nomofi' => strtolower($rpw[$_POST[multi]][name]),
);
echo json_encode ($jsonSearchResults);

return false;
?>
