<?php
$brand = $_POST['brand'];
$ip=$_POST['ip'];
$mac=$_POST['mac'];
$office=$_POST['office'];
$serial=$_POST['serial'];
$tag=$_POST['tag'];
//echo "este es $extension  tambien  $usuario  y  $oficina"
?>

<div>
    <form id="miform" action="index.php" method="POST">
        <div class="editar formulario">
            <div class="solicitud1">
                <div>
                    <label for="tag">Tag:</label>
                </div>
                <div>
                    <input class="ext" id="tag" type="text" name="busuario" readonly value="<?php echo $usuario; ?>">
                </div>
            </div>
            <div class="solicitud1">
                <div>
                    <label for="puesto">Marca:</label>
                </div>
                <div>
                    <input class="ext" id="puesto" type="text" name="bpuesto" readonly value="<?php echo $puesto; ?>">
                </div>
            </div>
            <div class="solicitud1">
                <div>
                    <label for="iplan">Oficina:</label>
                </div>
                <div>
                    <select id="oficina" name="boficina" class="ofi">
                        <option hidden selected><?php echo $oficina; ?></option>
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
            <div class="solicitud1">
                <div>
                    <label for="wip">Serial:</label>
                </div>
                <div>
                    <input class="usuario" id="wip" type="text" name="bwip" pattern="^((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)|NO$" value="<?php echo $wip; ?>">
                </div>

            </div>
            <div class="solicitud1">
                <div>
                    <label for="nivel">Ip:</label>
                </div>
                <div>
                    <select id="nivel" name="bnivel" class="ofi" required>
                        <option hidden selected><?php echo $nivel; ?></option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                    </select>
                </div>
            </div>
            <div class="solicitud1">
                <div>
                    <label for="oficina">Mac:</label>
                </div>
                <div>
                    <select id="oficina" name="boficina" class="ofi">
                        <option hidden selected><?php echo $oficina; ?></option>
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
            <div class="solicitud1">
                <div>
                    <label for="mlan">Lan Mac:</label>
                </div>
                <div>
                    <input class="usuario" id="mlan" type="text" name="blanmac" pattern="^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})|NO$" value="<?php echo $lanmac; ?>">
                </div>

            </div>
            <div class="solicitud1">
                <div>
                    <label for="bmac">Wifi Mac:</label>
                </div>
                <div>
                    <input class="usuario" id="bmac" type="text" name="bwmac" pattern="^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})|NO$" value="<?php echo $wmac; ?>">
                </div>

            </div>
        </div>


        <div class="area-boton">
            <div>
                <input type="submit" name="editar" value="Guardar">
            </div>
            <div>
                <input type="button" name="editar" value="Ocultar" onclick="cerrar()">
            </div>
            <!--
               <input type="submit" name="eliminar" value="eliminar">
                <input type="submit" name="agregar" value="Agregar">
           -->
        </div>

    </form>
</div>