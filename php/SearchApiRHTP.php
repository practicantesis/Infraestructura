<?php
require('funciones.php');
//echo "<pre>";
//print_r($_POST);
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://www.transportespitic.com.mx/light/empleados/info',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "clave_busca":"'.$_POST["valor"].'",
    "clave_usuario":"1839"
}',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJVc3VhcmlvIjoicGl0aWMiLCJpYXQiOjE2NjczMzQ4ODEsImV4cCI6MTY2OTkyNjg4MX0',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

//print_r($response);
$data = json_decode($response, TRUE);
$apep=strtolower($data['info'][0]['apepaterno']);
$apep=ucwords($apep);
$apem=strtolower($data['info'][0]['apematerno']);
$apem=ucwords($apem);
$nom=strtolower($data['info'][0]['nombre']);
$nom=ucwords($nom);



//echo $apep;
//echo $data['success']."ddddd";
//print_r($data);
$activo="NO";
$enpeople="NO";
if (strlen($data['info'][0]['fechabaja']) == 0) {
  $activo="SI";

  // Ahora verificamos si no existe ya un usuario en people con ese numero de empleado
  $exists=CheckExistentValueLDAP("ou=DeviceUsers,dc=transportespitic,dc=com","dunumeroempleado",$_POST["valor"]);
  echo "existes es $exists";
  if ($exists == "YES") {
    $aviso .= '<div class="alert alert-danger">El numero de empleado EXISTE en device users ( '.$pplnempb[0]['noempleado'][0].' )</div><br>';
  }



  $ldap=GetNoEmpInfoFromLDAP($_POST["valor"],'array');
  if (is_array($ldap)) {
    $enpeople="SI";
    $aviso .= '<div class="alert alert-info">No. Empleado Existe En People ( '.$enpeople.' )</div><br>';



    //$exists=CheckExistentValueLDAP("ou=DeviceUsers,dc=transportespitic,dc=com","duusername",$ldap['uid']);
    
    
      $pplnempb=GetUserInfoFromLDAP($ldap['uid'],"array");
      
    } else {
      $pplnemp=GetUserInfoFromLDAP($ldap['uid'],"array");
      if ($pplnemp[0]['noempleado'][0] != $_POST["valor"]) {
        $aviso .= '<div class="alert alert-danger">El numero de empleado no es el mismo En People ( '.$pplnemp[0]['noempleado'][0].' )</div><br>';
      } else {
        //$aviso .= '<div class="alert alert-success">El numero de empleado es el mismo En People </div><br>';
      }

      $username="dup-".strtolower($ldap['uid']);
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
    'success' => 'YES',
    'apep' => $apep,
    'apem' => $apem,
    'nom' => $nom,
    'genuser' => $genuser,
    'fullnom' => $apep." ".$apem." ".$nom,
    'ofi' => $data['info'][0]['oficina'],
    'enpeople' => $enpeople,
    'aviso' => $aviso,
    'username' => $username,
    'activo' => $activo
);


//echo $response;
echo json_encode ($jsonSearchResults);
return false;

//echo "</pre>";
?>
