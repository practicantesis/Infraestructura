<?php
include('configuraciones.class.php');
require('funciones.php');

$con=ConectaLDAP();
$conx=ConectaSQL('ocsweb');
set_time_limit(30);
$result = ldap_search($con,"ou=Celulares,ou=Devices,dc=transportespitic,dc=com", "(DeviceTAG=*)");
$err=ldap_error($con);
$ldata = ldap_get_entries($con, $result);
$fnt1="<font face='Trebuchet MS, Arial, Helvetica' size='1'>";
$fnt2="<font face='Trebuchet MS, Arial, Helvetica' size='2'>";


$html  = "<table class='table table-striped table-bordered table-responsive' id='celltable'><thead>";
$html .= "<tr><th>Tag</th><th>Assignedto</th><th>Brand</th><th>Dept</th><th>Imei</th><th>lastenroll</th><th>lastseen</th><th>mac</th><th>office</th><th>Serial</th><th>Numero de Telefono</th><!--<th>ofi en user</th><th>no emp en user</th><th>nombre en deviceuser</th>--><th>OCS Information</th><th>NOTES</th><th style='white-space:nowrap;'>RAZON DE BAJA</th></tr></thead><tbody>";

for ($i=0; $i<$ldata["count"]; $i++) {
	if ($ldata[$i]['deviceassignedto'][0] != "BAJA") {
    $html .= "<tr>";
	$html .= "<td>$fnt2".$ldata[$i]['devicetag'][0]."</td>";
	if ($ldata[$i]['deviceassignedto'][0] == "BAJA") {
		$btnda="";
	} else {
		if ($ldata[$i]['deviceassignedto'][0] == "PORDEFINIR") {
			$btnda="<input size='8' type='TEXT' id='".$ldata[$i]['devicetag'][0]."-TXTUsr'><button id='".$ldata[$i]['devicetag'][0]."-BtnUnassign' type='button' class='btn mb-1 btn-primary btn-xs' onclick=".'"'."AssignCellDevice('".$ldata[$i]['devicetag'][0]."','".$ldata[$i]['deviceassignedto'][0]."');".'"'.">Asignar</button>";			
		} else {
			$btnda="<br><button id='".$ldata[$i]['devicetag'][0]."-BtnUnassign' type='button' class='btn mb-1 btn-primary btn-xs' onclick=".'"'."UnassignCellDevice('".$ldata[$i]['devicetag'][0]."','".$ldata[$i]['deviceassignedto'][0]."');".'"'.">Desasignar</button>";			
		}

	}
    $html .= "<td><center>$fnt2".$ldata[$i]['deviceassignedto'][0]." $btnda </center></td>";
    $html .= "<td>$fnt2".$ldata[$i]['devicebrand'][0]."</td>";
	
	//$html .= "<td>$fnt2".$ldata[$i]['devicedept'][0]."</td>";
	$html .= "<td><div id='CHGDEPT".$ldata[$i]['devicetag'][0]."'><a href='#' onclick=".'"'."ChgDeptCellDevice('".$ldata[$i]['devicetag'][0]."');".'"'.">$fnt2".$ldata[$i]['devicedept'][0]."</a></div></td>";
	//$html .= "<td>$fnt2".$ldata[$i]['devicedept'][0]."<br><button id='".$ldata[$i]['devicetag'][0]."-BtnChgDept' type='button' class='btn mb-1 btn-primary btn-xs' onclick=".'"'."ChgDeptCellDevice('".$ldata[$i]['devicetag'][0]."');".'"'.">Cambiar</button></td>";


    $html .= "<td>$fnt1".$ldata[$i]['deviceimei'][0]."</td>";
    //$html .= "<td>$fnt1".$ldata[$i]['deviceip'][0]."</td>";    	
	$html .= "<td>$fnt1".$ldata[$i]['devicelastenrolledon'][0]."</td>";    	
	$html .= "<td>$fnt1".$ldata[$i]['devicelastseen'][0]."</td>";    	
	$html .= "<td>$fnt1".$ldata[$i]['devicemac'][0]."</td>";    	
	$html .= "<td>$fnt2".$ldata[$i]['deviceoffice'][0]."</td>";    	
	$html .= "<td>$fnt1".$ldata[$i]['deviceserial'][0]."</td>";
	if (strlen($ldata[$i]['devicenumber'][0]) > 1) {
		$namber=$ldata[$i]['devicenumber'][0];
		$namber.="<br><div style='display: inline' id='num-".$ldata[$i]['devicetag'][0]."'><a href='#' onclick=".'"'."ChangeDevCellNumberForm('".$ldata[$i]['devicetag'][0]."');".'"'.">CAMBIAR</a></div>";
	} else {
		$namber.="<br><input type='text' id='inputnumber".$ldata[$i]['devicetag'][0]."'><button id='".$ldata[$i]['devicetag'][0]."-BtnDevNumberAdd' type='button' class='btn mb-1 btn-primary btn-xs' onclick=".'"'."SaveDevCellNumber('".$ldata[$i]['devicetag'][0]."');".'"'.">Save</button>";
	}
	$html .= "<td>$fnt1 <div id='divcell".$ldata[$i]['devicetag'][0]."'>".$namber."<div></td>";
	///////////////////////////echo $n=GetDevUserFromLDAP($ldata[$i]['deviceassignedto'][0],$con);	
	//$ocstag=GetOCSTAG($ldata[$i]['deviceserial'][0],$conx);
	//$html .= "<td>$fnt1*** ".$ocstag."</td>";
	$selectedfalla="";
	if ($ldata[$i]['devicerazonbaja'][0] == "FALLA") {
		$selectedfalla="SELECTED";
	}
	$selectedROTURA="";
	if ($ldata[$i]['devicerazonbaja'][0] == "ROTURA") {
		$selectedROTURA="SELECTED";
	}
	$selectedOBSOLETO="";
	if ($ldata[$i]['devicerazonbaja'][0] == "OBSOLETO") {
		$selectedOBSOLETO="SELECTED";
	}
	$selectedROBO="";
	if ($ldata[$i]['devicerazonbaja'][0] == "ROBO") {
		$selectedROBO="SELECTED";
	}
	$selectedEXTRAVIO="";
	if ($ldata[$i]['devicerazonbaja'][0] == "EXTRAVIO") {
		$selectedEXTRAVIO="SELECTED";
	}

	
	$html .= "<!--<td></td><td></td><td></td>--><td></td>";      
	$html .= "<td><a href='#' onclick='GetComment()'>COMMENTS</a></td>";    	
	$html .= '<td><select style="width:5" class="form-control" name="baja-'.$ldata[$i]['devicetag'][0].'" id="baja-'.$ldata[$i]['devicetag'][0].'" onchange="SelFalla('."'".$ldata[$i]['devicetag'][0]."'".')"><option value="SELECCIONE">SELECCIONE</option><option value="ROTURA" '.$selectedROTURA.' >ROTURA</option><option value="FALLA" '.$selectedfalla.' >FALLA</option><option value="OBSOLETO" '.$selectedOBSOLETO.'>OBSOLETO</option><option value="ROBO" '.$selectedfalla.' >ROBO</option><option value="EXTRAVIO" '.$selectedfalla.' >EXTRAVIO</option></select>  </td>';    		
	//echo $ocstag;      
    $html .= "</tr>";
	// if baja

	}
    
    
}
$html .= "</tbody></table>";



$jsonSearchResults[] =  array(
    'success' => 'YES',
    'data' => $html
);

//echo $forma;

echo json_encode ($jsonSearchResults);
return false;


?>
  
