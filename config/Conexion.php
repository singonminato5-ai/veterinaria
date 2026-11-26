<?php
/**
 * Conexión a MySQL.
 * Lee los datos de variables de entorno (Render / Docker). Si no existen,
 * usa los valores de XAMPP/WAMP, así sigue funcionando en local sin cambios.
 *
 *   DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS
 *   DB_SSL=1          -> conexión cifrada (obligatoria en TiDB Cloud, PlanetScale, etc.)
 *   DB_SSL_CA=ruta    -> certificado CA (por defecto el del sistema)
 *   DB_SSL_VERIFY=0   -> no verificar el certificado del servidor (solo si el proveedor lo exige)
 */
class Conexion {
    private static $pdo = null;

    /** Crea la conexión y lanza excepción si falla (la usa también el script de inicialización). */
    public static function crear() {
        $host    = getenv('DB_HOST') ?: 'localhost';
        $puerto  = getenv('DB_PORT') ?: '3306';
        $bd      = getenv('DB_NAME') ?: 'veterinaria_db';
        $usuario = getenv('DB_USER') !== false ? getenv('DB_USER') : 'root';
        $clave   = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';

        $opciones = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 15,
        ];
        if (getenv('DB_SSL') === '1') {
            $opciones[PDO::MYSQL_ATTR_SSL_CA] = getenv('DB_SSL_CA') ?: '/etc/ssl/certs/ca-certificates.crt';
            if (getenv('DB_SSL_VERIFY') === '0') {
                $opciones[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
            }
        }

        return new PDO("mysql:host=$host;port=$puerto;dbname=$bd;charset=utf8mb4", $usuario, $clave, $opciones);
    }

    public static function conectar() {
        if (self::$pdo === null) {
            try {
                self::$pdo = self::crear();
            } catch (PDOException $e) {
                error_log('Error de conexión a la base de datos: ' . $e->getMessage());
                http_response_code(500);
                die('Error de conexión con la base de datos. Revisa las variables DB_* y los registros del servidor.');
            }
        }
        return self::$pdo;
    }
}
?>
