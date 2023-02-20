<?php
require('funciones.php');
//print_r($_POST);
//$exist=CheckExistentValueLDAP("ou=Celulares,ou=Devices,dc=transportespitic,dc=com","deviceassignedto",$_POST['duusername']);
//$ud=GetDeviceUserInfoFromLDAP($_POST['duusername']);
$ud=GetDeviceInfoFromLDAP("ou=Devices,dc=transportespitic,dc=com","deviceassignedto",$_POST['duusername']);
if ($ud[count] == 1 ) {
	$msg = "Este usuario esta asignado al telefono ".$ud[0][devicetag][0]. " al borrar el usuario dicho dispositivo se pongra asignado a PORDEFINIR <a href='#' onclick=".'"'."ConfirmDUNuke('".$_POST['duusername']."','".$ud[0][devicetag][0]."')".'"'.">CONTINUAR</a>";
	//
}

$jsonSearchResults[] =  array(
    'success' => 'YES',
    'data' => $forma,
    'msg' => $msg,
    'error' => 'err',
);
echo json_encode ($jsonSearchResults);

return false;

//print_r($ud);
?>