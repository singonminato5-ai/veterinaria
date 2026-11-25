<?php
require_once "models/Usuario.php";

class AuthController {
    private $roles = [
        'veterinario' => 'Veterinario',
        'secretaria'  => 'Secretaria',
    ];

    // Muestra el login (uno por cada rol): ?c=auth&a=login&rol=secretaria
    public function login() {
        if (Sesion::logueado()) redirigir("index.php?c=inicio");
        $rol = $_GET['rol'] ?? 'veterinario';
        if (!isset($this->roles[$rol])) $rol = 'veterinario';
        $rolNombre = $this->roles[$rol];
        require "views/auth/login.php";
    }

    public function entrar() {
        $rol = $_POST['rol'] ?? 'veterinario';
        if (!isset($this->roles[$rol])) $rol = 'veterinario';

        $u = Usuario::autenticar(trim($_POST['usuario'] ?? ''), $_POST['clave'] ?? '', $rol);
        if ($u) {
            Sesion::login($u);
            redirigir("index.php?c=inicio");
        }
        Sesion::flash('error', 'Usuario o contraseña incorrectos.');
        redirigir("index.php?c=auth&a=login&rol=$rol");
    }

    public function salir() {
        Sesion::logout();
        Sesion::iniciar();
        Sesion::flash('ok', 'Sesión cerrada correctamente.');
        redirigir("index.php?c=auth&a=login");
    }
}
?>
