<?php
require('funciones.php');

if ($_POST['tag'] != "NO") {
    $dn="DeviceTAG=".$_POST['tag'].",ou=Celulares,ou=Devices,dc=transportespitic,dc=com";    
    $tup=UpdateLDAPVAl($dn,"PORDEFINIR","deviceassignedto");
    if ($tup != "YES") {
        echo $tup;
    }
}


//print_r($_POST);
//echo $dn;

//return false;



//$tup=UpdateLDAPVAl("DeviceTAG=".$_POST['tag'].",ou=Celulares,ou=Devices,dc=transportespitic,dc=com","PORDEFINIR","DeviceAssignedTo");
$dele=DeleteLDAPUser("duusernname=".$_POST['user'].",ou=DeviceUsers,dc=transportespitic,dc=com");


$jsonSearchResults[] =  array(
    'success' => 'YES',
    'data' => $forma,
    'msg' => $msg,
    'errm' => $dele,
    'error' => 'err',
);
echo json_encode ($jsonSearchResults);

return false;

//print_r($ud);
?>
