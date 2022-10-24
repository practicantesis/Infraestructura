<?php
require('funciones.php');
include('configuraciones.class.php');
session_start();
//print_r($_POST);
//print_r($_SESSION);
$conn=ConectaSQL('firewall');
if ($_POST["action"] == "unassign") {
	logDevChange($_SESSION["user"],"DESASIGNACION",$_POST["tag"],"Se desasigna telefono desde tabla de telefonos INFRAESTRUCTURA",$conn);
	$success=UpdateLDAPVAl("DeviceTAG=".$_POST["tag"].",ou=Celulares,ou=Devices,dc=transportespitic,dc=com","PORDEFINIR","deviceassignedto");
}

if ($_POST["action"] == "assign") {
	$ex=CheckExistentValueLDAP("ou=DeviceUsers,dc=transportespitic,dc=com","duusernname",$_POST["elvalor"]);
	if ($ex == "YES") {
		$success = "NOEXISTE";
	} else {
		$xxx=GetDeviceTagInfoFromAssignedUserLDAP($_POST["elvalor"],"count");
		//print_r($xxx);
		//echo $xxx['count'];
		if ($xxx['count'] > 0) {
			$success = "ALREADYASSIGNED";
			$val=GetDeviceTagInfoFromAssignedUserLDAP($_POST["elvalor"],"tags");
		} else {
			//return false;
			logDevChange($_SESSION["user"],"ASIGNACION",$_POST["tag"],"Se asigna telefono ".$_POST["tag"]." a ".$_POST["elvalor"]." desde tabla de telefonos INFRAESTRUCTURA",$conn);
			$success=UpdateLDAPVAl("DeviceTAG=".$_POST["tag"].",ou=Celulares,ou=Devices,dc=transportespitic,dc=com",$_POST["elvalor"],"deviceassignedto");

		}
		
	}
	//echo "akia ando perro ";
	//print_r($_POST);
	//print_r($_SESSION);
	//return false;
}	


$jsonSearchResults[] =  array(
    'success' => $success,
    'val' => $val,
    'err' => $err,
);
echo json_encode ($jsonSearchResults);
return false;

/*



//gbadillo






echo $extel=CheckExistentValueLDAP("ou=Celulares,ou=Devices,dc=transportespitic,dc=com","deviceassignedto",$_POST['user']);

*/



$exdu=CheckExistentValueLDAP("ou=DeviceUsers,dc=transportespitic,dc=com","duusernname",$_POST['value']);

$success="NO";

if ($exdu == "YES") {
	$success="YES";
	$err="El device user ".$_POST['value']." Existe en Device users";
}

//$extel=CheckExistentValueLDAP("ou=Celulares,ou=Devices,dc=transportespitic,dc=com","deviceassignedto",$_POST['value']);
//if ($extel == "NO") {
//	$success="NO";
//	$err="El device user ".$_POST['value']." ya tiene telefono, para que quiere otro?";
//}
//echo "el valor es  $exdu";
//return false;

$jsonSearchResults[] =  array(
    'success' => $success,
    'err' => $err,
);
echo json_encode ($jsonSearchResults);
return false;

?>
