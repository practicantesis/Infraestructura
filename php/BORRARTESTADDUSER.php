<?php


echo "aki";

return false;

            $myfile = fopen("/var/www/html/Infraestructura/.awapi", "r") or die("Unable to open file!");
            $apipw=fgets($myfile);
            fclose($myfile);
            $pass="infra:".$apipw;
            $basic_auth = base64_encode(trim($pass));
            $api_key='Zbh2S+e0ejNOibdtwlFDFssflXSeCniu2oh1/7lVg5A=';
            $ch = curl_init();
            $baseurl="https://as257.awmdm.com";


$endpoint="/api/system/users/adduser";
$url = $baseurl.$endpoint;

//Inicializar array de datos
// datos minimos para regristrar un usuario "UserName", "Password", "FirstName", "LastName", "Email", "SecurityType" => 2
$data = array(
    "UserName" => "TAGabgarcia",
    "Password" => "acotaacota",
    "FirstName" => "Alex",
    "LastName" => "Cota",
    "Email" => "tagabgarcia@example.com",
    "SecurityType" => 2  //ESTE PARAMETRO SIEMPRE DEBE DE SER 2

);


// Convertir datos a formato JSON
$jsonData = json_encode($data);

$headr = array(
    'aw-tenant-code: '.$api_key,
    'Authorization: Basic '.$basic_auth,
    'Accept: application/json',
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData)
);

print_r($headr);



// Configurar opciones de cURL
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//['aw-tenant-code: '.$api_key,'Authorization: Basic '.$basic_auth,'Accept: application/json'];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);

// Ejecutar la solicitud cURL y obtener la respuesta
$response = curl_exec($ch);

$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Obtener cualquier error ocurrido durante la solicitud
$error_msg = curl_error($ch);
$error_code = curl_errno($ch);

// Cerrar la sesión cURL
curl_close($ch);

// Verificar si hubo algún error en la solicitud
if ($response === false) {
    echo "Error en la solicitud: " . $error_msg .  "\n";
    echo "Código de error: " . $error_code .  "\n";
} else {
    // Imprimir la respuesta
    echo "Código de estado HTTP: " . $http_code . "\n";
    echo "Respuesta: " . $response .  "\n" ;
}

?>
