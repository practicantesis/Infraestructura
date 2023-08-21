<?php
require('funciones.php');
include('configuraciones.class.php');
//print_r($_POST);
$success='DUNNO';
$LDAPBase="dc=transportespitic,dc=com";
if($_POST['what'] == "dunumeroempleado") {
    $LDAPBase="ou=DeviceUsers,dc=transportespitic,dc=com";
}
$success=CheckExistentValueLDAP($LDAPBase,$_POST['what'],$_POST['value']);
//CheckExistentValueLDAP("dc=transportespitic,dc=com","uid",$_POST['value']);

//echo "xxxxxxxxxxxx".$success;

$jsonSearchResults[] =  array(
    'success' => $success,
);
echo json_encode ($jsonSearchResults);
return false;

?>
