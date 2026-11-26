<?php
require_once "config/Conexion.php";

class Mascota {
    public static function obtenerTodas($buscar = '') {
        $pdo = Conexion::conectar();
        if ($buscar !== '') {
            $st = $pdo->prepare("SELECT * FROM mascotas
                                 WHERE nombre LIKE ? OR dueno LIKE ? OR especie LIKE ?
                                 ORDER BY id DESC");
            $like = "%$buscar%";
            $st->execute([$like, $like, $like]);
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }
        return $pdo->query("SELECT * FROM mascotas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerPorId($id) {
        $pdo = Conexion::conectar();
        $st = $pdo->prepare("SELECT * FROM mascotas WHERE id = ?");
        $st->execute([$id]);
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    public static function agregar($d) {
        $pdo = Conexion::conectar();
        $st = $pdo->prepare("INSERT INTO mascotas
            (nombre, especie, raza, sexo, edad, peso, dueno, telefono)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $st->execute([$d['nombre'], $d['especie'], $d['raza'], $d['sexo'],
                      $d['edad'], $d['peso'], $d['dueno'], $d['telefono']]);
        return (int)$pdo->lastInsertId();   // id de la mascota recién creada
    }

    public static function actualizar($id, $d) {
        $pdo = Conexion::conectar();
        $st = $pdo->prepare("UPDATE mascotas SET nombre = ?, especie = ?, raza = ?, sexo = ?,
            edad = ?, peso = ?, dueno = ?, telefono = ? WHERE id = ?");
        $st->execute([$d['nombre'], $d['especie'], $d['raza'], $d['sexo'],
                      $d['edad'], $d['peso'], $d['dueno'], $d['telefono'], $id]);
    }
}
?>
