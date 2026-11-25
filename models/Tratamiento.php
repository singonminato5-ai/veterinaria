<?php
require_once "config/Conexion.php";

class Tratamiento {
    public static function obtenerTodos() {
        $pdo = Conexion::conectar();
        $sql = "SELECT t.*, m.nombre AS mascota, m.dueno, s.nombre AS servicio,
                       mp.nombre AS metodo_pago
                FROM tratamientos t
                JOIN mascotas m  ON m.id = t.mascota_id
                JOIN servicios s ON s.id = t.servicio_id
                LEFT JOIN metodos_pago mp ON mp.id = t.metodo_pago_id
                ORDER BY t.id DESC";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerPorId($id) {
        $pdo = Conexion::conectar();
        $st = $pdo->prepare("SELECT * FROM tratamientos WHERE id = ?");
        $st->execute([$id]);
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    public static function agregar($d, $usuarioId) {
        $pdo = Conexion::conectar();
        $pdo->prepare("INSERT INTO tratamientos
            (mascota_id, servicio_id, metodo_pago_id, usuario_id, diagnostico,
             indicaciones, costo, estado, pagado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)")
            ->execute([$d['mascota_id'], $d['servicio_id'], $d['metodo_pago_id'], $usuarioId,
                       $d['diagnostico'], $d['indicaciones'], $d['costo'],
                       $d['estado'], $d['pagado']]);
    }

    public static function actualizar($id, $d) {
        $pdo = Conexion::conectar();
        $pdo->prepare("UPDATE tratamientos SET mascota_id = ?, servicio_id = ?, metodo_pago_id = ?,
            diagnostico = ?, indicaciones = ?, costo = ?, estado = ?, pagado = ? WHERE id = ?")
            ->execute([$d['mascota_id'], $d['servicio_id'], $d['metodo_pago_id'],
                       $d['diagnostico'], $d['indicaciones'], $d['costo'],
                       $d['estado'], $d['pagado'], $id]);
    }
}
?>
