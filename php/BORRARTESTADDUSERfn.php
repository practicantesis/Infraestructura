<?php
require('funciones.php');

session_start();
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

//$conn=ConectaSQL($base);

logit($_SESSION['user'],'Loguear otra cosa nomas porque si','jferia',$conn);

echo "aki";

return false;

//

echo $xxx=addUserToAirwatchAPI('borrame','borranombre','borraapellido');
?>
