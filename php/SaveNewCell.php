<?php
require('funciones.php');
include('configuraciones.class.php');
$success='DUNNO';
$valid='DUNNO';
$con=ConectaLDAP();

foreach ($_POST['data'] as $value) {
  $vname=$value[name];
  $forma[$vname]=$value[value];
} 
$dn="DeviceTAG=".$forma['val-newtag'].",ou=Celulares,ou=Devices,dc=transportespitic,dc=com";
$ERROR="NO";
if (strlen($forma['val-deviceassignedto']) == 0) {
	$ERROR	= "Assigned to demasiado corto";
} else {
	$entry['deviceassignedto']=$forma['val-deviceassignedto'];
}
if (strlen($forma['val-devicenumber']) == 0) {
	$ERROR	= "devicenumber demasiado corto";
} else {
	$entry['devicenumber']=$forma['val-devicenumber'];
}
if (strlen($forma['val-devicedept']) == 0) {
	$ERROR	= "devicedept demasiado corto";
} else {
	$entry['devicedept']=$forma['val-devicedept'];
}
$entry['deviceoffice']=$forma['val-oficina'];

if ( $_POST['data'][0]['name'] == "noaw" ) {
	if (strlen($forma['val-deviceimei']) == 0) {
		$ERROR	= "Imei demasiado corto";
	} else {
		$entry['deviceimei']=$forma['val-deviceimei'];
	}
	if (strlen($forma['val-deviceserial']) == 0) {
		$ERROR	= "Serie demasiado corta";
	} else {
		$entry['deviceserial']=$forma['val-deviceserial'];
	}
	if (strlen($forma['val-devicebrand']) == 0) {
		$ERROR	= "Marca demasiado corta";
	} else {
		$entry['devicebrand']=$forma['val-devicebrand'];
	}
	if ($forma['val-oficina'] == "SELECCIONE") {
		$ERROR	= "Aun no abrimos la oficina SELECCIONE";
	}
	//echo "xxxxxxxxxxxxxxxxxxxxxxxxx---".strlen($forma['val-deviceimei'])."---uuuuuuuuuuuuuuuuuxxxxxxxxxxxxxxxxxxx";
} else {
  $entry['deviceimei']='PORASIGNAR';  
}
$entry['objectClass'][0] = "top";
$entry['objectClass'][1] = "DeviceInfo";


/*
echo "<pre>";
print_r($entry);
echo "-------------------------";
echo $_POST['data'][0]['name'];
echo "-------------------------";
print_r($_POST['data']);
echo "</pre>";
echo $ERROR;
return false;
*/


if ($ERROR == "NO") {
 	$mod = ldap_add($con, $dn , $entry);
	$ERROR=ldap_error($con);
}

$jsonSearchResults[] =  array(
    'success' => $ERROR
);
echo json_encode ($jsonSearchResults);
return false;


?>


