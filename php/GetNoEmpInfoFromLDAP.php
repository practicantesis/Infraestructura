<?php
require('funciones.php');
include('configuraciones.class.php');
//print_r($_POST);
error_reporting(1);
$success='DUNNO';
//echo "zz";
$empinfo=GetNoEmpInfoFromLDAP($_POST['valor'],'array');



$exu=CheckExistentValueLDAP("ou=DeviceUsers,dc=transportespitic,dc=com","duusernname",$empinfo[uid]);


if ($exu == "YES") {
	$usi=GetDeviceUserInfoFromLDAP($empinfo[uid]);
	$existedu = "SI";
	$existeduNE = $usi[0]['dunumeroempleado'][0];
	//print_r($usi);
} else {
	$existedu = "NO";
}

//5641
//11475
//12071

$success="YES";


$jsonSearchResults[] =  array(
    'existedu' => $existedu,
    'existeduNE' => $existeduNE,
    'success' => $success,
    'valor' => $empinfo,
    'uid' => $empinfo[uid],
    'cn' => $empinfo[cn],
    'oficina' => $empinfo[oficina]
    
);
echo json_encode ($jsonSearchResults);
return false;

?>

