<?php
class Conexion {
    public static function conectar() {
        $host     = "localhost";
        $bd       = "veterinaria_db";
        $usuario  = "root";
        $clave    = "";

        try {
            $pdo = new PDO(
                "mysql:host=$host;dbname=$bd;charset=utf8mb4",
                $usuario, $clave
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
?>
