

<?php
class conexion
{
    public function conectar()
    {
        $conexion = oci_connect("usuario_plight", "u5uar10116ht", "192.168.100.132/tpitic");
        return $conexion;
    }
}

?>
