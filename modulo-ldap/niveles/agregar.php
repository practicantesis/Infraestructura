<div>
    <form id="miform" action="index.php" method="POST">
        <div class="editar formulario">
            <div class="solicitud1">
                <div>
                    <label for="usuario">Usuario:</label>
                </div>
                <div>
                    <input class="ext" id="usuario" type="text" name="busuario" readonly value="<?php echo $usuario; ?>">
                </div>
            </div>
           
            <div class="solicitud1">
                <div>
                    <label for="nivel">Niveles de red:</label>
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