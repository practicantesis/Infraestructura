<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/estilos2.css">
    <link rel="shortcut icon" href="img/telefonia.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@700&display=swap');
    </style>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap');
    </style>
    <title>Extensiones Telefonicas</title>
    <?php
    include('php/conexion.php');
    include('php/funciones.php');

    ?>
</head>

<?php
session_start();
if (isset($_SESSION['scup_user'])) {
} else {
    header("Location: login.php");
}
?>


<body>


    <header id="cabecera">
        <div class="logo-empresa">
            <img src="img/logo_adobe.png" alt="">
        </div>

        <div class="titulo">


            <div class="titulo-nombre">
                <h1 class="dm">Administrador Telefonico</h1>
            </div>
            <div class="titulo-imagen"> <img class="pitic" src="img/telefonia.png" alt=""></div>
        </div>

        <div class="sup" id="sup">
            <div class="sup-mensaje">
                <p>Bienvenido:<?php echo "<b>" . $_SESSION['scup_user'] . "</b>"  ?></p>
            </div>
            <div class="sup-men">
                <a href="logout.php">Cerrar sesion</a>
            </div>

        </div>


    </header>
    
</body>

</html>