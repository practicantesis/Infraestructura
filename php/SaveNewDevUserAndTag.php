<?php


require('funciones.php');
include('configuraciones.class.php');

$ERROR="NO";
$existnem="DUNNO";
$success='DUNNO';
$valid='DUNNO';
$con=ConectaLDAP();

foreach ($_POST['data'] as $value) {
	$vname="";
	$vname=$value[name];
	if (preg_match("/val-(.+)/", $value[name], $out)) {
		$vname=$out[1];
	}
	$forma[$vname]=$value[value];
}	

$dn="duusernname=".$forma['duusernname'].",ou=DeviceUsers,dc=transportespitic,dc=com";
//$forma['duusernname'] = strtolower($forma['duusernname']);
$forma['duusernname'] = trim($forma['duusernname']);
if (!preg_match('/^[A-Za-z]+$/', $forma['duusernname']))  {
   $ERROR="En username solo letras, valide que no tenga espacios o caracteres especiales en el campo de username -".$forma['duusernname']."-";
}

if (strlen($forma['duusernname']) < 1) {
  $ERROR="CAPTURE USUARIO!!!";
} 

if (strlen($forma['dunombre']) < 1) {
  $ERROR="CAPTURE NOMBRE!!!".strlen($forma['dunombre']);
} 

if (strlen($forma['duapellido']) < 1) {
  $ERROR="CAPTURE APELLIDO!!!".strlen($forma['duapellido']);
} 

if (is_numeric($forma['dunumeroempleado'])) {
  
} else {
  $ERROR="CAPTURE NUMERO DE EMPLEADO (SOLO NUMEROS)";
}

$existnem=CheckExistentValueLDAP("ou=DeviceUsers,dc=transportespitic,dc=com","duusernname",$forma['duusernname']);
if ($existnem == "YES") {
 $ERROR="USER YA EXISTE EN DEVICEUSERS"; 
}

$existlus=CheckExistentValueLDAP("ou=DeviceUsers,dc=transportespitic,dc=com","dunumeroempleadoOriginal",$forma['dunumeroempleado']);
if ($existnem == "YES") {
 $ERROR="NO EMPLEADO YA EXISTE EN DEVICEUSERS"; 
}


$entry['duusernname']=$forma['duusernname'];
$entry['dunombre']=$forma['dunombre'];
$entry['duapellido']=$forma['duapellido'];
$entry['dunumeroempleado']=$forma['dunumeroempleado'];
$entry['duoficina']=$forma['duoficina'];
$entry['objectClass'][0] = "deviceuser";


// Save Deviceuser
if ($ERROR == "NO") {
  $mod = ldap_add($con, $dn , $entry);
  $ERROR=ldap_error($con);
  $code=addUserToAirwatchAPI($forma['duusernname'],$forma['dunombre'],$forma['duapellido']);
}

sleep(1);


////////////// NUEVO
$success='DUNNO';
$valid='DUNNO';
$ERRORD="NO";
$dn="DeviceTAG=".$forma['newtag'].",ou=Celulares,ou=Devices,dc=transportespitic,dc=com";

if (strlen($forma['duusernname']) == 0) {
  $ERRORD  = "Assigned to demasiado corto";
} else {
  $entryd['deviceassignedto']=$forma['duusernname'];
}
if (strlen($forma['devicenumber']) == 0) {
  $ERRORD  = "devicenumber demasiado corto";
} else {
  $entryd['devicenumber']=$forma['devicenumber'];
}
if (strlen($forma['devicedept']) == 0) {
  $ERRORD  = "devicedept demasiado corto";
} else {
  $entryd['devicedept']=$forma['devicedept'];
}
$entryd['deviceoffice']=$forma['duoficina'];


if ( $forma['tipod'] == "CPH" ) {
  if (strlen($forma['deviceimei']) == 0) {
    $ERRORD  = "Imei demasiado corto";
  } else {
    $entryd['deviceimei']=$forma['deviceimei'];
  }
  if (strlen($forma['deviceserial']) == 0) {
    $ERRORD  = "Serie demasiado corta";
  } else {
    $entryd['deviceserial']=$forma['deviceserial'];
  }
  if (strlen($forma['devicebrand']) == 0) {
    $ERRORD  = "Marca demasiado corta";
  } else {
    $entryd['devicebrand']=$forma['devicebrand'];
  }
} else {
  $entryd['deviceimei']='PORASIGNAR';  
}
$entryd['objectClass'][0] = "top";
$entryd['objectClass'][1] = "DeviceInfo";


// Save Device
if ($ERRORD == "NO") {
  $mod = ldap_add($con, $dn , $entryd);
  $ERRORD=ldap_error($con);
}


//echo $ERRORD."xxx";


//logDevChange($user,$tipo,$tag,$desc,$conn)



$jsonSearchResults[] =  array(
    'success' => $ERROR,
    'code' => $code,
    'msg' => "DevUser ".$forma['duusernname']." Guardado"
);
echo json_encode ($jsonSearchResults);
return false;



?>


