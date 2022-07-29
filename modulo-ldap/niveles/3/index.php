<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/estilos2.css">
    <link rel="shortcut icon" href="../../img/ipIcon.png" type="image/x-icon">
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
            <img src="../../img/logo_adobe.png" alt="">
        </div>

        <div class="titulo">


            <div class="titulo-nombre">
                <h1 class="dm">Equipos Asignados</h1>
            </div>
            <div class="titulo-imagen"> <img class="pitic" src="../../img/ip.png" alt=""></div>
        </div>

        <div class="sup" >
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
            div = document.getElementById('edit');
            div.style.display = 'none';
        }

        function mostrar() {
            div = document.getElementById('edit');
            div.style.display = '';
        }
    </script>

    <?php
    $lanip = $_POST["blanip"];
    $lanmac = $_POST["blanmac"];
    $wip = $_POST["bwip"];
    $wmac = $_POST["bwmac"];
    $nivel = $_POST["bnivel"];
    $usuario = $_POST["busuario"];
    ?>

    <div id="edit">

    </div>
    <?php
    //PROCESO PARA EDITAR UN DATOS
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
    // window.history.back();
    ?>






    <article class="consultas">
        <div class="combobox">
            <div class="label-oficina">
                <label>Oficina:</label>
            </div>

            <div class="select">
                <select id="officinas" name="oficinas" onchange="busquedaPorOfficina()">
                    <option value="">Todos</option>
                    <option value="TRA">Transportes</option>
                    <option value="VHL">Volvo Hermosillo</option>
                    <option value="TDI">Tecnologia Diesel</option>
                    <option value="DO">Direccion de operaciones</option>
                    <option value="DC">Direccion comercial</option>
                    <option value="DG">Direccion general</option>
                    <option value="RH">Recursos humanos</option>
                    <option value="DAF">Administracion (DAF)</option>
                    <option value="SIS">Sistemas</option>
                    <option value="MT1">Monterrey 1</option>
                    <option value="MER">Merida</option>
                    <option value="CUL">Culiacan</option>
                    <option value="MCH">Mochis</option>
                    <option value="NOG">Nogales</option>
                    <option value="CCN">Cancun</option>
                    <option value="MAZ">Mazatlan</option>
                    <option value="MXL">Mexicali</option>
                    <option value="PUE">Puebla</option>
                    <option value="QUE">Queretaro</option>
                    <option value="TEP">Tepic</option>
                    <option value="LGT">Leon</option>
                    <option value="IZT">Iztapalapa</option>
                    <option value="ZAP">Zapopan</option>
                    <option value="CHI">Chihuahua</option>
                    <option value="STA">Santa ana</option>
                    <option value="TOL">Toluca</option>
                    <option value="JUA">Juarez</option>
                    <option value="TPZ">Tepozotlan</option>
                    <option value="GDL">Guadalajara</option>
                    <option value="HLO">Hermosillo</option>
                    <option value="MEX">Mexico</option>
                    <option value="VIL">Villahermosa</option>
                    <option value="TIJ">Tijuana</option>
                    <option value="COB">Ciudad obregon</option>
                    <option value="MTY">Monterrey</option>
                </select>
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
        <div class="boton-actualizar">
            <a class="boton-a" href="">Actualizar</a>
        </div>
    </article>



    <article class="tabla">
        <?php
        $objConLDAP = new Conexion();
        $con = $objConLDAP->conectarLDAP();



        if ($con) {
            echo '<table id="datos" class="table table-hover">';
            echo '<thead class="encabezado2"><th>Usuario</th></tr></thead>';
            $filter2 = "member=uid=kpartida,ou=People,dc=transportespitic,dc=com";
            $filter = "member=*";
            //$filter = "(duusernname=*)";duoficina

            $srch = ldap_search($con, "cn=Nivel3,ou=Niveles,ou=groups,dc=transportespitic,dc=com", $filter);
            $count = ldap_count_entries($con, $srch);
            $info = ldap_get_entries($con, $srch);
            //$arr = GetDevUsersFromLDAPCells("array", $info[$i]['usuariotelefono'][0], $con);
            echo '<tbody class="tabladato r">';
            for ($i = 0; $i < $count; $i++) {
                //$lu = $info[$i]['usuariotelefono'][0];
                echo '<tr>';
                echo '<td>' . $info[$i]['member'][0] . '</td>';
                echo '</tr>';
            }
            

            echo '</tbody></table>';
            ldap_close($con);
        }
        echo $count;
        echo $info["count"];
        echo $info;
        ?>
    </article>


    <footer>
        <p class="copyright">© 2022 Desarrollo y Mantenimiento por: Andres Salazar</p>
    </footer>
</body>

</html>