<?php

require('funciones.php');
include('configuraciones.class.php');

echo $xxx = CheckExistentValueLDAP("ou=People,dc=transportespitic,dc=com","uid","acota");


$rh=QueryRHdb('11434');
print "<pre>";
print_r($rh);
print "</pre>";

$rh=QueryRHdb('1839');
print "<pre>";
print_r($rh);
print "</pre>";


?>

