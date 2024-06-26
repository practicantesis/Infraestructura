<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/estilos2.css">
    <link rel="shortcut icon" href="../img/acceso.png" type="image/x-icon">
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
    <title>Asignacion de equipo</title>
    <?php
    include('conexion.php');
    include('funciones.php');
    ?>
</head>

<?php
session_start();
if (isset($_SESSION['user'])) {
} else {
    header("Location: login.php");
}
?>


<body>


    <header id="sup">
        <div class="logo-empresa">
            <img src="../img/logo_adobe.png" alt="">
        </div>

        <div class="titulo">


            <div class="titulo-nombre">
                <h1 class="dm">Nivel de acceso</h1>
            </div>
            <div class="titulo-imagen"> <img class="pitic" src="../img/acceso.png" alt=""></div>
        </div>

        <div class="sup">
            <div class="sup-mensaje">
                <p>Bienvenido:<?php echo "<b>" . $_SESSION['user'] . "</b>"  ?></p>
            </div>
            <div class="sup-men">
                <a href="logout.php">Cerrar sesion</a>
            </div>

        </div>

    </header>


    <script>
        function doSearch() {
            const tableReg = document.getElementById('datos');
            const searchText = document.getElementById('searchTerm').value.toLowerCase();
            let total = 0;

            // Recorremos todas las filas con contenido de la tabla
            for (let i = 1; i < tableReg.rows.length; i++) {
                // Si el td tiene la clase "noSearch" no se busca en su cntenido
                if (tableReg.rows[i].classList.contains("noSearch")) {
                    continue;
                }

                let found = false;
                const cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
                // Recorremos todas las celdas
                for (let j = 0; j < cellsOfRow.length && !found; j++) {
                    const compareWith = cellsOfRow[j].innerHTML.toLowerCase();
                    // Buscamos el texto en el contenido de la celda
                    if (searchText.length == 0 || compareWith.indexOf(searchText) > -1) {
                        found = true;
                        total++;
                    }
                }
                if (found) {
                    tableReg.rows[i].style.display = '';
                } else {
                    // si no ha encontrado ninguna coincidencia, esconde la
                    // fila de la tabla
                    tableReg.rows[i].style.display = 'none';
                }
            }

            // mostramos las coincidencias

        }
    </script>


    <script type="text/javascript">
        //la funcion de busqueda ase una busqueda en la tabla y regresa los resultados
        function busquedaPorOfficina() {

            var select = document.getElementById('officinas');
            var optionSelect = select.options[select.selectedIndex]; //obtenemos las opciones que hay dentro del select

            var tabla = document.getElementById('datos'); //Obtenemos la tabla
            var Pbusqueda = optionSelect.value; //obtenemos el value que esta en las obciones de la tabla
            //Se ase un recorrido a la tabla
            for (var i = 1; i < tabla.rows.length; i++) {
                var cellsOfRow = tabla.rows[i].getElementsByTagName('td') //obtiene todos los objetos'td' de la tabla y los guarda en un array
                var found = false; //puntero

                for (var j = 0; j < cellsOfRow.length && !found; j++) {

                    //si encuentra coincidencia
                    if (cellsOfRow[j].innerHTML === Pbusqueda) {
                        found = true;

                    } //si la opcion esta vacia en la busqueda found es true para que muestre todo el recorrdio
                    else if (Pbusqueda == "") {
                        found = true;
                    }
                }
                if (found) {
                    tabla.rows[i].style.display = '';
                } else {
                    tabla.rows[i].style.display = 'none';

                }
            }
        }
        //	busqueda();



        function cerrar() {
            div = document.getElementById('divagregar');
            div.style.display = 'none';
        }

        function mostrar() {
            div = document.getElementById('divagregar');
            div.style.display = '';
        }
    </script>

    <?php

    ?>


    <?php

    // PROCESO PARA AGREGAR DATOS
    if (isset($_POST['ingresar'])) {

        $nivel = $_POST["bnivel"];
        $usuario = $_POST["busuario"];


        $objConLDAP = new Conexion();
        $ds = $objConLDAP->conectarLDAP();
        //$ds = ldap_connect();  // Asumiendo que el servidor de LDAP está en el mismo host

        if ($ds) {
            // Asociar con el dn apropiado para dar acceso de actualización
            ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
            $r = ldap_bind($ds, "cn=$nivel,ou=Niveles,ou=groups,dc=transportespitic,dc=com", "sistemaspitic");

            // Preparar los datos
            $contenido = "uid=$usuario,ou=People,dc=transportespitic,dc=com";
            $info['member'] = $contenido;

            $filter = "(cn=$nivel)";
            $srch = ldap_search($ds, "ou=Niveles,ou=groups,dc=transportespitic,dc=com", $filter);
            $count = ldap_count_entries($ds, $srch);

            $infos = ldap_get_entries($ds, $srch);
            //$arr = GetDevUsersFromLDAPCells("array", $info[$i]['usuariotelefono'][0], $con);
            for ($i = 0; $i < $infos["count"]; $i++) {
                $contar = $infos[$i]['member']['count'];
            }
            echo "total contadas: $contar , $nivel , $usuario , $contenido";





            // Agregar datos al directorio
            if ($count >= 1) {
                ldap_mod_add($ds, "cn=$nivel,ou=Niveles,ou=groups,dc=transportespitic,dc=com", $info);
                ldap_close($ds);
            } else {
                echo "<script>alert('Usuario ya existe');</script>";
                echo '<script>window.history.replaceState(null, null, window.location.href);</script>';
            }
        } else {
            echo "No se pudo conectar al servidor LDAP";
        }
    }



    /*PROCESO PARA EDITAR UN DATOS
    if (isset($_POST['editar'])) {

        if (empty($_POST["blanip"])) {
            $lanip = "NO";
        } else {
            $lanip = $_POST["blanip"];
        }
        if (empty($_POST["blanmac"])) {
            $lanmac = "NO";
        } else {
            $lanmac = $_POST["blanmac"];
        }
        if (empty($_POST["bwip"])) {
            $wip = "NO";
        } else {
            $wip = $_POST["bwip"];
        }
        if (empty($_POST["bwmac"])) {
            $wmac = "NO";
        } else {
            $wmac = $_POST["bwmac"];
        }
        $usuario = $_POST["busuario"];
        $oficina = $_POST["boficina"];

        $objConLDAP = new Conexion();
        $ds = $objConLDAP->conectarLDAP();
        //$ds = ldap_connect();  // Asumiendo que el servidor de LDAP está en el mismo host

        if ($ds) {
            // Asociar con el dn apropiado para dar acceso de actualización
            ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
            $r = ldap_bind($ds, "cn=feria,dc=transportespitic,dc=com", "sistemaspitic");

            // Preparar los datos
            $info['lanip'][0] = $lanip;
            $info['lanmac'][0] = $lanmac;
            $info['wifiip'][0] = $wip;
            $info['wifimac'][0] = $wmac;
            $info['accesosdered'][0] = $nivel;
            $info['uid'][0] = $usuario;
            $info['oficina'][0] = $oficina;

            // Agregar datos al directorio

            $r = ldap_modify($ds, "uid=$usuario,ou=People,dc=transportespitic,dc=com", $info);
            echo "<script>alert('Modificaste exitosamente al usuario: $usuario  =)');window.history.replaceState(null, null, window.location.href);</script>"; //mensaje y elimina historial para que no se recargue el post

            ldap_close($ds);
        }
    }
    // window.history.back();*/


    ?>



    <div id="divagregar" class="divagregar">

    </div>


    <article class="consultas">
        <div class="combobox">
            <div class="label-oficina">
                <label>Nivel:</label>
            </div>

            <div class="select">
                <form action="index.php" method="post" class="formulario-niveles">
                    <select id="officinas" name="nivel">
                        <option hidden></option>
                        <option value="Nivel1">Nivel 1</option>
                        <option value="Nivel2">Nivel 2</option>
                        <option value="Nivel3">Nivel 3</option>
                        <option value="Nivel5">Nivel 5</option>
                        <option value="Nivel9">Nivel 9</option>
                        <option value="PA_Nivel1">PA Nivel 1</option>
                        <option value="PA_Nivel2">PA Nivel 2</option>
                        <option value="PA_Nivel3">PA Nivel 3</option>
                        <option value="PA_Nivel5">PA Nivel 5</option>
                        <option value="PA_Nivel9">PA Nivel 9</option>
                    </select>
                    <input type="submit" name='niveles' value="Mostrar">
                </form>

            </div>
        </div>
        <div class="input-buscador">
            <div class="label-buscador">
                <label for="">Buscador: </label>

            </div>
            <div>
                <abbr title="Ya puedes buscar por usuario, nombre, oficina e incluso por puesto "> <input id="searchTerm" onkeyup="doSearch()" type="text" name="buscador"></abbr>
            </div>
        </div>
    </article>

    <article class="botones">
        <div class="agregar">
            <div>
                <form id="formagregar" method="POST">
                    <a class="boton-b" onclick="mostrar()" type="button" href="#sup" id="agregar">Agregar</a>
                </form>
            </div>
            <script>
                $("#agregar").click(function() {
                    $.ajax({
                        url: "agregar.php",
                        type: "POST",
                        data: $("#formagregar").serialize(),
                        success: function(res) {
                            $("#divagregar").html(res);

                        }
                    });
                });
            </script>
        </div>
        <div class="mensaje-php">
            <div>
                <h6><?php if (isset($_POST["niveles"])) {
                        echo "Acceso de " .  $_POST["nivel"];
                    }  ?></h6>
            </div>
        </div>
        <div class="boton-actualizar">
            <div><a class="boton-a" href="">Actualizar</a></div>
        </div>
    </article>



    <article class="tabla">
        <?php
        $objConLDAP = new Conexion();
        $con = $objConLDAP->conectarLDAP();


        echo '<table id="datos" class="table table-hover">';
        echo '<thead class="encabezado2"><th>Usuario</th></tr></thead>';
        if ($con && isset($_POST['niveles'])) {

            // $filter2 = "member=uid=kpartida,ou=People,dc=transportespitic,dc=com";
            // $filter = "member=*";
            //$filter = "(duusernname=*)";duoficina
            $combo = $_POST["nivel"];
            if ($combo == "Nivel1" or $combo == "Nivel2" or $combo == "Nivel3" or $combo == "Nivel5" or $combo == "Nivel9") {
                $srch = ldap_search($con, "ou=Niveles,ou=groups,dc=transportespitic,dc=com", "(cn=" . $_POST['nivel'] . ")");
                $contar = ldap_count_entries($con, $srch);
                $info = ldap_get_entries($con, $srch);
                //$arr = GetDevUsersFromLDAPCells("array", $info[$i]['usuariotelefono'][0], $con);
                echo '<tbody class="tabladato r">';
                for ($i = 0; $i < $info["count"]; $i++) {

                    $count = $info[$i]['member']['count'];
                    //$lu = $info[$i]['usuariotelefono'][0];

                    for ($x = 0; $x < $count; $x++) {
                        //echo "The number is: $x <br>";
                        echo '<tr>';
                        echo '<td>' . $info[$i]['member'][$x] . '</td>';
                        echo '</tr>';
                    }
                }
            } else {
                $srch = ldap_search($con, "ou=PaloAltoPerms,ou=groups,dc=transportespitic,dc=com", "(cn=" . $_POST['nivel'] . ")");
                $contar = ldap_count_entries($con, $srch);
                $info = ldap_get_entries($con, $srch);
                //$arr = GetDevUsersFromLDAPCells("array", $info[$i]['usuariotelefono'][0], $con);
                echo '<tbody class="tabladato r">';
                for ($i = 0; $i < $info["count"]; $i++) {

                    $count = $info[$i]['member']['count'];
                    //$lu = $info[$i]['usuariotelefono'][0];

                    for ($x = 0; $x < $count; $x++) {
                        //echo "The number is: $x <br>";
                        echo '<tr>';
                        echo '<td>' . $info[$i]['member'][$x] . '</td>';
                        echo '</tr>';
                    }
                }
            }

            echo '</tbody>';
            echo '<script>window.history.replaceState(null, null, window.location.href);</script>';
            ldap_close($con);
        }
        echo '</table>';
        //    echo $info["count"];
        //   echo $count;
        ?>
    </article>


    <footer>
        <p class="copyright">© 2022 - Desarrollo y Mantenimiento: Andres Salazar (gsalazar) - Versión del sitio 1.5</p>
    </footer>
</body>

</html>