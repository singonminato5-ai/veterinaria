<?php
require_once "config/Conexion.php";

class InicioController {
    public function listar() {
        $pdo = Conexion::conectar();
        $total = function ($sql) use ($pdo) { return (int)$pdo->query($sql)->fetchColumn(); };
        $resumen = [
            'mascotas'    => $total("SELECT COUNT(*) FROM mascotas"),
            'en_curso'    => $total("SELECT COUNT(*) FROM tratamientos WHERE estado = 'En curso'"),
            'pendientes'  => $total("SELECT COUNT(*) FROM tratamientos WHERE pagado = 0 AND estado <> 'Cancelado'"),
            'servicios'   => $total("SELECT COUNT(*) FROM servicios WHERE activo = 1"),
        ];
        require "views/inicio/panel.php";
    }
}
?>
