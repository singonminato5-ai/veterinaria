<?php
/**
 * Se ejecuta al arrancar el contenedor.
 * - Si la tabla "usuarios" NO existe, importa database.sql (tablas + datos de ejemplo).
 * - Si ya existe, no toca nada (así nunca se borran datos al redesplegar).
 *
 * Opcional: ADMIN_CLAVE y SECRETARIA_CLAVE cambian las contraseñas iniciales
 * (solo en esta primera inicialización).
 */
require '/var/www/html/config/Conexion.php';

$pdo = null;
for ($i = 1; $i <= 10; $i++) {
    try { $pdo = Conexion::crear(); break; }
    catch (Throwable $e) {
        fwrite(STDERR, "[init_db] Intento $i/10 sin conexión: " . $e->getMessage() . "\n");
        sleep(3); // bases serverless pueden tardar unos segundos en despertar
    }
}
if (!$pdo) { fwrite(STDERR, "[init_db] No se pudo conectar a la base de datos.\n"); exit(1); }

if ($pdo->query("SHOW TABLES LIKE 'usuarios'")->fetchColumn()) {
    echo "[init_db] La base de datos ya está inicializada. No se hace nada.\n";
    exit(0);
}

echo "[init_db] Base vacía: creando tablas y datos iniciales...\n";
$sql = file_get_contents('/opt/app/database.sql');
$sql = preg_replace('/(^|\s)--\s.*$/m', '', $sql);                 // quita comentarios
$sql = preg_replace('/^\s*(CREATE DATABASE|USE)\b[^;]*;/mi', '', $sql); // la base ya existe en el proveedor

foreach (preg_split('/;\s*\n/', $sql) as $sentencia) {
    $sentencia = trim($sentencia, " \t\r\n;");
    if ($sentencia === '') continue;
    try { $pdo->exec($sentencia); }
    catch (Throwable $e) {
        fwrite(STDERR, "[init_db] ERROR: " . $e->getMessage() . "\nSentencia: " . substr($sentencia, 0, 120) . "...\n");
        exit(1);
    }
}

// Contraseñas iniciales personalizadas (recomendado si el repositorio es público)
foreach (['admin' => 'ADMIN_CLAVE', 'secretaria' => 'SECRETARIA_CLAVE'] as $usuario => $var) {
    $nueva = getenv($var);
    if ($nueva !== false && $nueva !== '') {
        $pdo->prepare("UPDATE usuarios SET clave = ? WHERE usuario = ?")
            ->execute([password_hash($nueva, PASSWORD_DEFAULT), $usuario]);
        echo "[init_db] Contraseña de '$usuario' personalizada.\n";
    }
}
echo "[init_db] Listo.\n";
