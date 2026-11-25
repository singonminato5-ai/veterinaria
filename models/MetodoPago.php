<?php
require_once "config/Conexion.php";

class MetodoPago {
    public static function obtenerTodos() {
        $pdo = Conexion::conectar();
        return $pdo->query("SELECT * FROM metodos_pago ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerPorId($id) {
        $pdo = Conexion::conectar();
        $st = $pdo->prepare("SELECT * FROM metodos_pago WHERE id = ?");
        $st->execute([$id]);
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    public static function agregar($d) {
        $pdo = Conexion::conectar();
        $pdo->prepare("INSERT INTO metodos_pago (nombre, descripcion, activo) VALUES (?, ?, ?)")
            ->execute([$d['nombre'], $d['descripcion'], $d['activo']]);
    }

    public static function actualizar($id, $d) {
        $pdo = Conexion::conectar();
        $pdo->prepare("UPDATE metodos_pago SET nombre = ?, descripcion = ?, activo = ? WHERE id = ?")
            ->execute([$d['nombre'], $d['descripcion'], $d['activo'], $id]);
    }
}
?>
