<?php
require_once "config/Conexion.php";

class Historial {
    public static function obtenerTodos($mascotaId = 0) {
        $pdo = Conexion::conectar();
        $sql = "SELECT h.*, m.nombre AS mascota, m.dueno, m.telefono, s.nombre AS servicio
                FROM historial h
                JOIN mascotas m ON m.id = h.mascota_id
                LEFT JOIN servicios s ON s.id = h.servicio_id";
        if ($mascotaId > 0) {
            $st = $pdo->prepare("$sql WHERE h.mascota_id = ? ORDER BY h.id DESC");
            $st->execute([$mascotaId]);
        } else {
            $st = $pdo->query("$sql ORDER BY h.id DESC");
        }
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerPorId($id) {
        $pdo = Conexion::conectar();
        $st = $pdo->prepare("SELECT * FROM historial WHERE id = ?");
        $st->execute([$id]);
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    public static function agregar($d, $usuarioId) {
        $pdo = Conexion::conectar();
        $pdo->prepare("INSERT INTO historial
            (mascota_id, servicio_id, usuario_id, tratamiento, volver_cantidad,
             volver_unidad, fecha_retorno, descripcion)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)")
            ->execute([$d['mascota_id'], $d['servicio_id'], $usuarioId, $d['tratamiento'],
                       $d['volver_cantidad'], $d['volver_unidad'],
                       $d['fecha_retorno'], $d['descripcion']]);
    }

    /** Primera entrada del historial, creada sola cuando se registra una mascota. */
    public static function registrarAlta($mascotaId, $usuarioId, array $m) {
        $pdo = Conexion::conectar();
        $detalle = 'Mascota registrada en el sistema. ' . $m['nombre'] . ' (' . $m['especie']
                 . ($m['raza'] !== '' ? ', ' . $m['raza'] : '') . ') - Dueño: ' . $m['dueno'] . '.';
        $pdo->prepare("INSERT INTO historial
            (mascota_id, servicio_id, usuario_id, tratamiento, volver_cantidad,
             volver_unidad, fecha_retorno, descripcion)
            VALUES (?, NULL, ?, 'Registro de la mascota', NULL, 'Días', NULL, ?)")
            ->execute([$mascotaId, $usuarioId, $detalle]);
    }

    public static function actualizar($id, $d) {
        $pdo = Conexion::conectar();
        $pdo->prepare("UPDATE historial SET mascota_id = ?, servicio_id = ?, tratamiento = ?,
            volver_cantidad = ?, volver_unidad = ?, fecha_retorno = ?, descripcion = ?
            WHERE id = ?")
            ->execute([$d['mascota_id'], $d['servicio_id'], $d['tratamiento'],
                       $d['volver_cantidad'], $d['volver_unidad'],
                       $d['fecha_retorno'], $d['descripcion'], $id]);
    }
}
?>
