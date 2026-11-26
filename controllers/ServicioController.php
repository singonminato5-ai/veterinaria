<?php
require_once "models/Servicio.php";

class ServicioController {
    // Ver: veterinario y secretaria. Crear/editar: solo veterinario.
    public function listar() {
        $servicios = Servicio::obtenerTodos();
        require "views/servicio/lista.php";
    }

    public function crear() {
        Sesion::requerirRol(['veterinario']);
        $servicio = null;
        require "views/servicio/formulario.php";
    }

    public function guardar() {
        Sesion::requerirRol(['veterinario']);
        Servicio::agregar($this->datos());
        Sesion::flash('ok', 'Servicio agregado correctamente.');
        redirigir("index.php?c=servicio");
    }

    public function editar() {
        Sesion::requerirRol(['veterinario']);
        $servicio = Servicio::obtenerPorId((int)($_GET['id'] ?? 0));
        if (!$servicio) {
            Sesion::flash('error', 'El servicio no existe.');
            redirigir("index.php?c=servicio");
        }
        require "views/servicio/formulario.php";
    }

    public function actualizar() {
        Sesion::requerirRol(['veterinario']);
        Servicio::actualizar((int)$_POST['id'], $this->datos());
        Sesion::flash('ok', 'Servicio actualizado.');
        redirigir("index.php?c=servicio");
    }

    private function datos() {
        return [
            'nombre'      => trim($_POST['nombre'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'precio'      => max(0, (float)($_POST['precio'] ?? 0)),
            'activo'      => isset($_POST['activo']) ? 1 : 0,
        ];
    }
}
?>
