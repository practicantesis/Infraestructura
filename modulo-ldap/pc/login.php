<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesion</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
<?php
session_start();
if (isset($_SESSION['scup_user'])) {
    header("Location: index.php");
} 
?>

    <header>
        <div class="logo-empresa">
            <img src="img/logo_adobe.png" alt="">
        </div>

        <div class="titulo">
            <div class="titulo-nombre">
                <h1 class="dm">Administrador Telefonico</h1>
            </div>
            <div class="titulo-imagen">
                <img class="pitic" src="img/telefonia.png" alt="">
            </div>
        </div>
        
    </header>
    <div class="caja-madre">

        <div class="caja-formulario">
            <div class="titulo-f">
                <h1>
                    Iniciar sesion
                </h1>
            </div>
            <form action="log_captura.php" method="POST" class="formulario">
                <div class="caja">
                    <div>
                        <label for="user">Usuario:</label>
                    </div>
                    <div>
                        <input id="user" name="usuario" type="text">
                    </div>
                </div>
                <br>
                <div class="caja">
                    <div>
                        <label for="password">Clave:</label>
                    </div>
                    <div>
                        <input id="password" name="clave" type="password">
                    </div>
                </div>
                <br>
                <div class="boton">
                    <input type="submit" name="submit" value="Ingresar">
                </div>
            </form>
        </div>
    </div>


</body>

</html>