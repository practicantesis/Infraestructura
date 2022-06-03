<?php
$lanip = $_POST['lanip'];
$lanmac = $_POST['lanmac'];
$wip = $_POST['wip'];
$wmac = $_POST['wmac'];
$nivel = $_POST['nivel'];
$usuario = $_POST['usuario'];
$puesto = $_POST['puesto'];
$oficina = $_POST['oficina'];
//echo "este es $extension  tambien  $usuario  y  $oficina"
?>

<div>
    <form  id="miform" action="index.php" method="POST">
        <div class="editar formulario">
            <div class="solicitud1">
                <div>
                    <label for="extension">Usuario:</label>
                </div>
                <div>
                    <input class="ext" id="extension" type="text" name="busuario" readonly value="<?php echo $usuario; ?>">
                </div>
            </div>
            <div class="solicitud1">
                <div>
                    <label for="extension">Puesto:</label>
                </div>
                <div>
                    <input class="ext" id="extension" type="text" name="bpuesto" readonly value="<?php echo $puesto; ?>">
                </div>
            </div>
            <div class="solicitud1">
                <div>
                    <label for="usuario">Ip Lan:</label>
                </div>
                <div>
                    <input class="usuario" id="usuario" type="text" name="blanip" value="<?php echo $lanip; ?>">
                </div>

            </div>
            <div class="solicitud1">
                <div>
                    <label for="usuario">Wifi ip:</label>
                </div>
                <div>
                    <input class="usuario" id="usuario" type="text" name="bwip" value="<?php echo $wip; ?>">
                </div>

            </div>
            <div class="solicitud1">
                <div>
                    <label for="ofi">Niveles de red:</label>
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
                    <label for="extension">oficina:</label>
                </div>
                <div>
                    <input class="ext" id="extension" type="text" name="boficina" readonly value="<?php echo $oficina; ?>">
                </div>
            </div>
            <div class="solicitud1">
                <div>
                    <label for="usuario">Mac Lan:</label>
                </div>
                <div>
                    <input class="usuario" id="usuario" type="text" name="blanmac" value="<?php echo $lanmac; ?>">
                </div>

            </div>
            <div class="solicitud1">
                <div>
                    <label for="usuario">Wifi Mac:</label>
                </div>
                <div>
                    <input class="usuario" id="usuario" type="text" name="bwmac" value="<?php echo $wmac; ?>">
                </div>

            </div>
        </div>


        <div class="area-boton">
            <div>
                <input type="submit" name="editar" value="editar">
            </div>
            <!--
               <input type="submit" name="eliminar" value="eliminar">
                <input type="submit" name="agregar" value="Agregar">
           -->
        </div>

    </form>
</div>