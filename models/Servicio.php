<?php
require_once "config/Conexion.php";

class Servicio {
    public static function obtenerTodos() {
        $pdo = Conexion::conectar();
        return $pdo->query("SELECT * FROM servicios ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerPorId($id) {
        $pdo = Conexion::conectar();
        $st = $pdo->prepare("SELECT * FROM servicios WHERE id = ?");
        $st->execute([$id]);
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    public static function agregar($d) {
        $pdo = Conexion::conectar();
        $pdo->prepare("INSERT INTO servicios (nombre, descripcion, precio, activo) VALUES (?, ?, ?, ?)")
            ->execute([$d['nombre'], $d['descripcion'], $d['precio'], $d['activo']]);
    }

    public static function actualizar($id, $d) {
        $pdo = Conexion::conectar();
        $pdo->prepare("UPDATE servicios SET nombre = ?, descripcion = ?, precio = ?, activo = ? WHERE id = ?")
            ->execute([$d['nombre'], $d['descripcion'], $d['precio'], $d['activo'], $id]);
    }
}
?>
