<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/estilos2.css">
    <link rel="shortcut icon" href="img/camion.png" type="image/x-icon">
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
    <title>Transportes</title>
</head>

<body>


    <header>
        <div class="logo-empresa">
            <img src="img/logo_adobe.png" alt="">
        </div>

        <div class="titulo">


            <div class="titulo-nombre">
                <h1 class="dm">Transportes</h1>
            </div>
            <div class="titulo-imagen"> <img class="pitic" src="img/camion.png" alt=""></div>
        </div>

    </header>

    <script>
        function obtenerFecha(e) {

            var fecha = moment(e.value);
            console.log("Fecha original:" + e.value);
            console.log("Fecha formateada es: " + fecha.format("YYYY/MM/DD"));
        }
    </script>

    <div class="consultar">
        <form action="index.php" method="POST" class="formulario-date">
            <div class="dates">
                <div class="date">
                    <label for="desde">Desde:</label>
                    <input type="date" id="desde" name="desde" onchange="obtenerFecha(this)" />
                </div>
                <div class="date">
                    <label for="hasta">Hasta:</label>
                    <input type="date" id="hasta" name="hasta" onchange="obtenerFecha(this)" />
                </div>
            </div>
            <div class="boton-dates">
                <input type="submit" name="consultar" value="Consultar">
            </div>

        </form>
    </div>


    <?php
    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];
    ?>

    <article class="opciones">
        <div class="opciones-i">
            <div class="i"></div>

        </div>
        <div class="opciones-d">
            <div class="d actualizar"><a href="">Actualizar</a></div>
            <div class="i alerta"><a href="https://transportespitic.com/sistemas/caso_soporte.php" target="_blank">Reportar Problema</a></div>
        </div>
    </article>


    <article class="tabla">
        <?php

        include('php/conexion.php');
        include('php/funciones.php');
        $ociConect = new conexion();
        $getconex = $ociConect->conectar();


        echo '<table id="datos" class="table table-hover">';
        echo '<thead class="encabezado2"><tr><th>#</th><th>NOMCLIENTE</th><th>FECHA</th><th>ORIGEN</th><th>Ref_cong</th><th>SUM(A.FLETE)</th><th>SUM(A.CPAC)</th></tr></thead>';
        if ($getconex && isset($_POST['consultar'])) {
            $sql = "SELECT b.nomcliente, a.fecha, b.origen, ref_cong, SUM(a.Flete), SUM(a.Cpac) FROM GUIAS.MOVIMIENTOS a LEFT JOIN GUIAS.GUIAS b ON a.guia = b.guia
            WHERE A.FECHA BETWEEN TO_DATE('$desde','yyyy-mm-dd') AND TO_DATE('$hasta','yyyy-mm-dd') AND tipomov IN ('E','C','Y','N','W')
            AND (b.fraccion<900 OR b.fraccion=903 or b.fraccion=908 OR b.fraccion=913 OR b.fraccion=914 ) AND b.completo = 1 GROUP BY b.nomcliente, a.fecha, b.origen, ref_cong
            ORDER BY a.fecha, b.nomcliente, b.origen, ref_cong";

            $centencia = oci_parse($getconex, $sql);
            $ejecutar = oci_execute($centencia);
            echo $ex = oci_error($centencia);
            echo '<tbody class="tabladato">';
            while (($row = oci_fetch_array($centencia, OCI_ASSOC)) != false) {
                echo '<tr>';
                echo '<td>' . $num=$num+1 . '</td>';
                echo '<td>' . $row['NOMCLIENTE'] . '</td>';
                echo '<td>' . $row['FECHA'] . '</td>';
                echo '<td>' . $row['ORIGEN'] . '</td>';
                echo '<td>' . $row['REF_CONG'] . '</td>';
                echo '<td>' . $row['SUM(A.FLETE)'] . '</td>';
                echo '<td>' . $row['SUM(A.CPAC)'] . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '<script>window.history.replaceState(null, null, window.location.href);</script>';
        }
        echo '</table>';
        oci_close($getconex);


        ?>
    </article>


    <footer>
        <p class="copyright">© 2022 - Desarrollo y Mantenimiento: Andres Salazar (gsalazar) - Versión del sitio 0.9</p>
    </footer>
</body>

</html>