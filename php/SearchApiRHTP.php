<?php

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
echo $response;
//echo "</pre>";
?>
