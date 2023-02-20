<?php
require('funciones.php');

$dn="DeviceTAG=".$_POST['tag'].",ou=Celulares,ou=Devices,dc=transportespitic,dc=com";

//print_r($_POST);
//echo $dn;
$tup=UpdateLDAPVAl($dn,"PORDEFINIR","deviceassignedto");
//return false;

if ($tup != "YES") {
	echo $tup;
}


//$tup=UpdateLDAPVAl("DeviceTAG=".$_POST['tag'].",ou=Celulares,ou=Devices,dc=transportespitic,dc=com","PORDEFINIR","DeviceAssignedTo");
$dele=DeleteLDAPUser("duusernname=".$_POST['user'].",ou=DeviceUsers,dc=transportespitic,dc=com");


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
