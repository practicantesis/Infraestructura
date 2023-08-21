<?php
require('funciones.php');
include('configuraciones.class.php');
//print_r($_POST);
$exdu=CheckExistentValueLDAP("ou=DeviceUsers,dc=transportespitic,dc=com","duusernname",$_POST['value']);

if (($exdu == "NO") and ($_POST['value'] != "PORDEFINIR") ) {
	$success="NO";
	$err="El device user ".$_POST['value']." NO Existe en Device users, registrelo antes de continuar";
}

$extel=CheckExistentValueLDAP("ou=Celulares,ou=Devices,dc=transportespitic,dc=com","deviceassignedto",$_POST['value']);


//echo "UUU $extel FFF".$_POST['value'];


if ( ($extel == "YES") and ($_POST['value'] != "PORDEFINIR") ) {
	$success="NO";
	$err="El device user ".$_POST['value']." ya tiene telefono, para que quiere otro?";

}

if ( !preg_match ("/^[a-zA-Z\s]+$/",$_POST['value'])) {
   $err = "Solo Letras mi rey (".$_POST['value'].")...";
   $success="NO";
} 
//echo "el valor es  $exdu";


//return false;




$jsonSearchResults[] =  array(
    'success' => $success,
    'err' => $err,
);
echo json_encode ($jsonSearchResults);
return false;

?>
