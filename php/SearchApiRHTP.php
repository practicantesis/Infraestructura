<?php
require('funciones.php');
//echo "<pre>";
//print_r($_POST);

$success="YES";

$empdata=QueryRHdb($_POST["valor"]);
//echo "<pre>";
//print_r($empdata);
//echo "</pre>";
//return false;


if ($empdata == "NO_RESPONSE") {
  $aviso .= '<div class="alert alert-danger">No. Empleado NO Existe RH </div><br>';
  $jsonSearchResults[] =  array(
    'success' => 'NO',
    'aviso' => $aviso,
    'activo' => "NOTFOUND"
  );
  echo json_encode ($jsonSearchResults);
  return false;
}

$abrevs=GetOfficeAbrevs('x');

// descomentar para probar ofic exixtente con emp 1839
//array_push($abrevs, 'GPO');




if (in_array(strtoupper($empdata['ofi']), $abrevs)) { 
  $combo='<input type="text" style="width:5" class="form-control" name="val-duoficina" id="val-duoficina" value='.strtoupper($empdata['ofi']).' readonly >';  
}  else {
  $combo='<select style="width:5" class="form-control" name="val-duoficina" id="val-duoficina"><option value="SELECCIONE">SELECCIONE</option>';
  foreach ($abrevs as $value) {
    $sel="";
    if (strtoupper($empdata['ofi']) == $value) {
      $sel = "SELECTED";
    }
    $combo.='<OPTION VALUE="'.$value.'" '.$sel.'>'.$value.'</OPTION>';  
  }
  $combo.='</select>';
}





$activo=$empdata['activo'];
$enpeople="NO";
$endeviceusers="NO";
if ($activo=="SI") {
  $aviso .= '<div class="alert alert-info">El numero de empleado EXISTE en BD RH </div><br>';
  // Ahora verificamos si no existe ya un usuario en people con ese numero de empleado
  $exists=CheckExistentValueLDAP("ou=DeviceUsers,dc=transportespitic,dc=com","dunumeroempleadoOriginal",$_POST["valor"]);
  //echo "existes es $exists";
  if ($exists == "YES") {
    $aviso .= '<div class="alert alert-danger">El numero de empleado YA EXISTE en device users de LDAP </div><br>';
    $duinfo=GetDeviceUserInfoFromdunumeroempleado($_POST["valor"],"array","no");
    //echo "PEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE1_";
    //print_r($duinfo);
    //echo "PEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE2_";
    $success="DUP"; 
    $endeviceusers="YES";   
  }
  $ldap=GetNoEmpInfoFromLDAP($_POST["valor"],'array');
  //print_r($ldap);
  if (is_array($ldap)) {
    $enpeople="SI";
    $aviso .= '<div class="alert alert-info">No. Empleado Existe En People </div><br>';
    //$exists=CheckExistentValueLDAP("ou=DeviceUsers,dc=transportespitic,dc=com","duusername",$ldap['uid']);
    $pplnempb=GetUserInfoFromLDAPConnq($ldap['uid'],"array");
    $username="dup-".strtolower($ldap['uid']);
  } else {
    /*
    $pplnemp=GetUserInfoFromLDAPConnq($ldap['uid'],"array");
    if ($pplnemp[0]['noempleado'][0] != $_POST["valor"]) {
        $aviso .= '<div class="alert alert-danger">El numero de empleado no es el mismo En People ( '.$pplnemp[0]['noempleado'][0].' )</div><br>';
    } else {
        //$aviso .= '<div class="alert alert-success">El numero de empleado es el mismo En People </div><br>';
    }
    
    */
    
    }
  } else {
    $aviso .= '<div class="alert alert-info">No. Empleado NO Existe En People</div><br>';
    $enpeople="NO";
    $pre="dun-";
    $fstchar = substr($nom, 0, 1);
    $username = $pre.strtolower($fstchar).strtolower($apep);
  }
//}

//echo gettype($response);
$responseb['activo']=$activo;


$jsonSearchResults[] =  array(
    'success' => $success,
    'apep' => $empdata['pat'],
    'apem' => $empdata['mat'],
    'nom' => $empdata['nom'],
    'feb' => $empdata['feb'],
    'genuser' => $genuser,
    'fullnom' => $empdata['pat']." ".$empdata['mat']." ".$empdata['nom'],
    'ofi' => strtoupper($empdata['ofi']),
    'enpeople' => $enpeople,
    'endeviceusers' => $endeviceusers,
    'aviso' => $aviso,
    'username' => $username,
    'combo' => $combo,
    'activo' => $empdata['activo']
);


//echo $response;
echo json_encode ($jsonSearchResults);
return false;

//echo "</pre>";
?>
