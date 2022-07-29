<?php
// Inicializa la sesi�n.
session_start();
// Destruye todas las variables de la sesi�n
$_SESSION = array();
//guardar el nombre de la sessi�n para luego borrar las cookies
$session_name = session_name();
//Para destruir una variable en espec�fico
unset($_SESSION['user']);
// Finalmente, destruye la sesi�n
session_destroy();
// Para borrar las cookies asociadas a la sesi�n
// Es necesario hacer una petici�n http para que el navegador las elimine
if ( isset( $_COOKIE[ $session_name ] ) ) {
    if ( setcookie(session_name(), '', time()-3600, '/') ) {
        header("Location: index.php");
        exit();   
    }
}
?>