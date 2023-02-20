<?php
//ini_set('display_errors',0);
require('funciones.php');
$html='';

//print_r($_POST);
$success="YES";

$dn="DeviceTAG=".$_POST['tag'].",ou=Celulares,ou=Devices,dc=transportespitic,dc=com";
UpdateLDAPVAl($dn,$_POST['tag'],"devicenumber");
$err="OK";

$jsonSearchResults[] =  array(
    'success' => $success,
    'data' => $err,
    'error' => $err
);
echo json_encode ($jsonSearchResults);


return false;



?>




