<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Moviles</title>
	<link rel="shortcut icon" href="img/moviles.png">
	<link rel="stylesheet" href="estilo.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
</head>

<script>
	function doSearch() {
		const tableReg = document.getElementById('datos');
		const searchText = document.getElementById('officinas').value.toLowerCase();
		let total = 0;

		// Recorremos todas las filas con contenido de la tabla
		for (let i = 1; i < tableReg.rows.length; i++) {
			// Si el td tiene la clase "noSearch" no se busca en su cntenido
			if (tableReg.rows[i].classList.contains("officinas")) {
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


	function doSearch2() {
		const tableReg = document.getElementById('datos');
		const searchText = document.getElementById('searchTerm').value.toLowerCase();
		let total = 0;

		// Recorremos todas las filas con contenido de la tabla
		for (let i = 1; i < tableReg.rows.length; i++) {
			// Si el td tiene la clase "noSearch" no se busca en su cntenido
			if (tableReg.rows[i].classList.contains("searchTerm")) {
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

<script>
	function abririnfo() {
		document.getElementById("flotante").style.display = "block";
	}

	function cerrarinfo() {
		document.getElementById("flotante").style.display = "none";
	}

	function enviarformulario() {
		document.formulario.submit();

	}

	function abrirdirectorio() {
		document.getElementById("directorio").style.display = "block";
	}

	function cerrardirectorio() {
		document.getElementById("directorio").style.display = "none";
	}
</script>
<!--<label for="">Buscador: <input id="searchTerm" onkeyup="doSearch()" type="text" name="buscador"></label>-->
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
	busqueda();
</script>

<body>


	<!--<a href="javascript:abririnfo()">abrir</a>-->
	<!--	<a href="http://192.168.120.179/INFRAESTRUCTURA-DESARROLLO/jferia/Infraestructura/airwatchdos.php">Airwatch Funcional</a> <br>
	<a href="http://192.168.120.179/INFRAESTRUCTURA-DESARROLLO/gsalazar/tabla/airwatchdos.php">Airwatch No funcional</a>  -->
	<div class="titulo">
		<div class="cabecera">
			<img class="pitic" src="img/pitic.png" alt="">
			<h1 class="dm">Dispositivos Moviles <img class="movil" src="img/moviles.png" alt="celular"></h1>

		</div>
		<br>
		<div id="obsiones_officina" class="form-group oficina">


			<label>Oficina:</label>

			<select id="officinas" name="oficinas" onchange="doSearch()">
				<option value="">Todas</option>
				<option value="BAJA_CEL_TRA">TRANSPORTE</option>
				<option value="CELMER">MERIDA</option>
				<option value="CELCCN">CANCUN</option>
				<option value="CELVIL">VILLA HERMOSA</option>
				<option value="CELCHI">CHIHUAHUA</option>
				<option value="CELJUA">JUAREZ</option>
				<option value="CELMTY">MONTERREY</option>
				<option value="CELMT1">MONTERREY1</option>
				<option value="CELCUL">CULIACAN</option>
				<option value="CELGDL">GUADALAJARA</option>
				<option value="CELLGT">LEON</option>
				<option value="CELMAZ">MAZATLAN</option>
				<option value="CELTEP">TEPIC</option>
				<option value="CELZAP">ZAPOPAN</option>
				<option value="CELMCH">MOCHIS</option>
				<option value="CELHLO">HERMOSILLO</option>
				<option value="CELMXL">MEXICALI</option>
				<option value="CELOBR">OBREGON</option>
				<option value="CELSTA">SANTA ANA</option>
				<option value="CELTIJ">TIJUANA</option>
				<option value="CELNOG">NOGALES</option>
				<option value="CPHSIS">SISTEMAS</option>
				<option value="CELIZT">IZTAPALAPA</option>
				<option value="CELMEX">MEXICO</option>
				<option value="CELPUE">PUEBLA</option>
				<option value="CELQUE">QUERETARO</option>
				<option value="CELTOL">TOLUCA</option>
				<option value="CELTPZ">TEPOZOTLAN</option>
			</select>


		</div>
		<label for="">Buscador: <input id="searchTerm" onkeyup="doSearch2()" type="text" name="buscador"></label>


	</div>
	<div class="menu-grid">
		<div class="enlaces-grid">
			<div><a class='link' href='http://ti.tpitic.com.mx/Infraestructura/informe/index.php?666U2lzdGVtYXM=897'>Inicio</a></div>
			<div><a class='link' href='celular.php'>Celulares</a></div>
			<div><a class='link' href='usuarios.php'>Usuarios</a></div>

		</div>
		<div class="actualizar-grid">
			<a class='link' href='bajas2.php'>actualizar</a>
		</div>

	</div>

	<div class="ventana" id="directorio" style="display: none; height: 490px; overflow: scroll;">
		<div id="cerrar">
			<a href="javascript:cerrardirectorio()"><img src="img/cancel.png" alt="cerrar"></a>
		</div>


	</div>
	<center>
		<?php
		include('conexion.php');
		include('../php/funciones.php');
		$objConLDAP = new Conexion();
		$con = $objConLDAP->conectarLDAP();
		if ($con) {
			//$filter = "(duusernname=*)";duoficina
			$filter = "(deviceoffice=*)";
			$srch = ldap_search($con, "ou=Celulares,ou=Devices,dc=transportespitic,dc=com", $filter);
			$count = ldap_count_entries($con, $srch);
			$info = ldap_get_entries($con, $srch);
			//print_r($arr);


			echo '<table id="datos" class="table table-hover">';
			echo '<thead class="encabezado2"><tr><th>Oficina</th><th>Usuario</th><th>Telefono</th><th>Marca</th><th>TAG</th></tr></thead>';

			for ($i = 0; $i < $count; $i++) {
				if (
					$info[$i]['deviceoffice'][0] == 'BAJA_CEL_NOR' || $info[$i]['deviceoffice'][0] == ' BAJA_CEL_NOR' || $info[$i]['deviceoffice'][0] == 'BAJA_CEL_NOR ' || $info[$i]['deviceoffice'][0] == ' BAJA_CEL_NOR '
					|| $info[$i]['deviceoffice'][0] == 'BAJA_CEL_NST' || $info[$i]['deviceoffice'][0] == ' BAJA_CEL_NST' || $info[$i]['deviceoffice'][0] == 'BAJA_CEL_NST ' || $info[$i]['deviceoffice'][0] == ' BAJA_CEL_NST '
					|| $info[$i]['deviceoffice'][0] == 'BAJA_CEL_SUR' || $info[$i]['deviceoffice'][0] == ' BAJA_CEL_SUR' || $info[$i]['deviceoffice'][0] == 'BAJA_CEL_SUR ' || $info[$i]['deviceoffice'][0] == ' BAJA_CEL_SUR '
					|| $info[$i]['deviceoffice'][0] == 'BAJA_CEL_OCT' || $info[$i]['deviceoffice'][0] == ' BAJA_CEL_OCT' || $info[$i]['deviceoffice'][0] == 'BAJA_CEL_OCT ' || $info[$i]['deviceoffice'][0] == ' BAJA_CEL_OCT '
					|| $info[$i]['deviceoffice'][0] == 'BAJA_CEL_CNT' || $info[$i]['deviceoffice'][0] == ' BAJA_CEL_CNT' || $info[$i]['deviceoffice'][0] == 'BAJA_CEL_CNT ' || $info[$i]['deviceoffice'][0] == ' BAJA_CEL_CNT '
					|| $info[$i]['deviceoffice'][0] == 'BAJA_CEL_TRA' || $info[$i]['deviceoffice'][0] == ' BAJA_CEL_TRA' || $info[$i]['deviceoffice'][0] == 'BAJA_CEL_TRA ' || $info[$i]['deviceoffice'][0] == ' BAJA_CEL_TRA '
				) {
					echo '<tbody class="tabladato"><tr>';
					echo '<td>' . $info[$i]['deviceoffice'][0] . '</td>';
					echo '<td>' . $info[$i]['deviceassignedto'][0] . '</td>';
					echo '<td>' . $info[$i]['devicenumber'][0] . '</td>';
					echo '<td>' . $info[$i]['devicebrand'][0] . '</td>';
					echo '<td>' . $info[$i]['devicetag'][0] . '</td>';
					echo '</tr></tbody>';
				}
			}
			echo '</table>';
			ldap_close($con);
		}
		?>
		<div class="ventana" id="flotante">
			<div id="cerrar">
				<a href="javascript:cerrarinfo()"><img src="img/cancel.png" alt="cerrar"></a>
			</div>
			<h4>Ldap</h4>
			<div id="flotantedos">

			</div>
		</div>

	</center>
	<!--Scrip para odernar tablas--->


	<?php
	/*foreach ($lasof as &$vf) {
    //echo "Ofi: ".$vf."<br>";
    //echo 'URL: https://ti.tpitic.com.mx/INFRAESTRUCTURA-DESARROLLO/jferia/Infraestructura/tabla/index.php?666'.base64_encode($vf)."897<br>-------------------------------<br>";
}*/
	?>

	<footer>
		<p class="copyright">© 2021 Desarrollo y Mantenimiento: Juan Feria & Andres Salazar</p>
	</footer>
</body>

</html>