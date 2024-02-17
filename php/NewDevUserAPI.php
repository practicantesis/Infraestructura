<?php

require('funciones.php');
$forma=NewDevUserFormGuided();
//$forma=NewDevUserFormAPI();

$jsonSearchResults[] =  array(
    'success' => 'YES',
    'data' => $forma
);

//echo $forma;

echo json_encode ($jsonSearchResults);
return false;



?>
  
