<?php
require_once "models/Usuario.php";

// Módulo exclusivo del veterinario: administra las cuentas de secretaria.
class SecretariaController {
    public function __construct() {
        Sesion::requerirRol(['veterinario']);
    }

    public function listar() {
        $secretarias = Usuario::secretarias();
        require "views/secretaria/lista.php";
    }

    public function crear() {
        $secretaria = null;
        require "views/secretaria/formulario.php";
    }

    public function guardar() {
        $d = $this->datos();
        if ($d['clave'] === '') {
            Sesion::flash('error', 'La contraseña es obligatoria para una secretaria nueva.');
            redirigir("index.php?c=secretaria&a=crear");
        }
        try {
            Usuario::agregarSecretaria($d);
            Sesion::flash('ok', 'Secretaria registrada correctamente.');
            redirigir("index.php?c=secretaria");
        } catch (PDOException $e) {
            Sesion::flash('error', 'Ese nombre de usuario ya existe. Elige otro.');
            redirigir("index.php?c=secretaria&a=crear");
        }
    }

    public function editar() {
        $secretaria = Usuario::secretariaPorId((int)($_GET['id'] ?? 0));
        if (!$secretaria) {
            Sesion::flash('error', 'La secretaria no existe.');
            redirigir("index.php?c=secretaria");
        }
        require "views/secretaria/formulario.php";
    }

    public function actualizar() {
        $id = (int)$_POST['id'];
        try {
            Usuario::actualizarSecretaria($id, $this->datos());
            Sesion::flash('ok', 'Datos de la secretaria actualizados.');
            redirigir("index.php?c=secretaria");
        } catch (PDOException $e) {
            Sesion::flash('error', 'Ese nombre de usuario ya existe. Elige otro.');
            redirigir("index.php?c=secretaria&a=editar&id=$id");
        }
    }

    private function datos() {
        return [
            'nombre'   => trim($_POST['nombre'] ?? ''),
            'usuario'  => trim($_POST['usuario'] ?? ''),
            'clave'    => $_POST['clave'] ?? '',
            'telefono' => trim($_POST['telefono'] ?? ''),
            'activo'   => isset($_POST['activo']) ? 1 : 0,
        ];
    }
}
?>
