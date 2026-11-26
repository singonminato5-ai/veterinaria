<?php
class Sesion {
    public static function iniciar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public static function usuario()      { return $_SESSION['usuario'] ?? null; }
    public static function logueado()     { return self::usuario() !== null; }
    public static function rol()          { return self::usuario()['rol'] ?? null; }
    public static function esVeterinario(){ return self::rol() === 'veterinario'; }
    public static function esSecretaria() { return self::rol() === 'secretaria'; }

    public static function login(array $u) {
        session_regenerate_id(true);
        $_SESSION['usuario'] = [
            'id' => $u['id'], 'nombre' => $u['nombre'],
            'usuario' => $u['usuario'], 'rol' => $u['rol'],
        ];
    }

    public static function logout() {
        $_SESSION = [];
        session_destroy();
    }

    public static function requerirLogin() {
        if (!self::logueado()) {
            header("Location: index.php?c=auth&a=login");
            exit;
        }
    }

    /** Solo permite el acceso a los roles indicados. */
    public static function requerirRol(array $roles) {
        self::requerirLogin();
        if (!in_array(self::rol(), $roles, true)) {
            self::flash('error', 'No tienes permiso para realizar esa acción.');
            header("Location: index.php?c=inicio");
            exit;
        }
    }

    public static function flash($tipo, $mensaje) {
        $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
    }

    public static function leerFlash() {
        $f = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $f;
    }
}

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

function redirigir($url) { header("Location: $url"); exit; }
?>
