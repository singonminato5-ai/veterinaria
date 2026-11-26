<?php
require_once "config/Conexion.php";

class Usuario {
    public static function autenticar($usuario, $clave, $rol) {
        $pdo = Conexion::conectar();
        $st = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ? AND rol = ? AND activo = 1");
        $st->execute([$usuario, $rol]);
        $u = $st->fetch(PDO::FETCH_ASSOC);
        if ($u && password_verify($clave, $u['clave'])) return $u;
        return false;
    }

    public static function secretarias() {
        $pdo = Conexion::conectar();
        return $pdo->query("SELECT id, nombre, usuario, telefono, activo FROM usuarios
                            WHERE rol = 'secretaria' ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function secretariaPorId($id) {
        $pdo = Conexion::conectar();
        $st = $pdo->prepare("SELECT id, nombre, usuario, telefono, activo FROM usuarios
                             WHERE id = ? AND rol = 'secretaria'");
        $st->execute([$id]);
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    public static function agregarSecretaria($d) {
        $pdo = Conexion::conectar();
        $st = $pdo->prepare("INSERT INTO usuarios (nombre, usuario, clave, rol, telefono, activo)
                             VALUES (?, ?, ?, 'secretaria', ?, ?)");
        $st->execute([$d['nombre'], $d['usuario'],
                      password_hash($d['clave'], PASSWORD_DEFAULT),
                      $d['telefono'], $d['activo']]);
    }

    public static function actualizarSecretaria($id, $d) {
        $pdo = Conexion::conectar();
        $st = $pdo->prepare("UPDATE usuarios SET nombre = ?, usuario = ?, telefono = ?, activo = ?
                             WHERE id = ? AND rol = 'secretaria'");
        $st->execute([$d['nombre'], $d['usuario'], $d['telefono'], $d['activo'], $id]);

        // La contraseña solo se cambia si se escribió una nueva
        if ($d['clave'] !== '') {
            $pdo->prepare("UPDATE usuarios SET clave = ? WHERE id = ? AND rol = 'secretaria'")
                ->execute([password_hash($d['clave'], PASSWORD_DEFAULT), $id]);
        }
    }
}
?>
