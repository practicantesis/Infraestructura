<?php
require('funciones.php');
include('configuraciones.class.php');
//print_r($_POST);
error_reporting(1);
$success='DUNNO';
//echo "zz";

$devs=GetFilteredDeviceListFromLDAP("ou=Celulares,ou=Devices,dc=transportespitic,dc=com","deviceoffice",$_POST['ofi'],"devicetag");
$numbas=Array();

if ($_POST['aw'] == "yes") {
    $tipo="CEL";
}
if ($_POST['aw'] == "no") {
    $tipo="CPH";
}


foreach ($devs as $value) {
    //if (preg_match("/([A-Z]+)(\d+)/i",$value,$mat)) {
    if (preg_match("/([A-Z][A-Z][A-Z])([A-Z][A-Z][A-Z1])(\d\d\d)$/i",$value,$mat)) {
        if ($mat['1'] == $tipo) {
            array_push($numbas, $mat['3']);
            //echo "tipo enviado es $tipo detectado es ".$mat['1']."  ofi es ".$mat['2']." value  ".$mat['3']."\n";    
        }
        //echo "tipo enviado es $tipo detectado es ".$mat['1']."  ofi es ".$mat['2']." value  ".$mat['3']."\n";            
        //$tagbody=$mat['1'];
    }
}
$elmax=(max($numbas)+1);

//echo "el tipo es ".$tipo;

$tagbody=$tipo.$_POST['ofi'];


//echo "el max es ".$tagbody.$elmax;
//print_r($numbas);

//print_r($devs);

//$ofi=GetCellAvailableTag($_POST['ofi']);

//echo $xxxx  = strlen($elmax);
//print_r($devs) ;

if (strlen($elmax) > 0) {
	$success="YES";

} else {
	$success="NO";	
}

$elmax=str_pad($elmax, 3, '0', STR_PAD_LEFT);

$jsonSearchResults[] =  array(
    'success' => $success,
    'tag' => $tagbody.$elmax
    
);
echo json_encode ($jsonSearchResults);
return false;

?>

