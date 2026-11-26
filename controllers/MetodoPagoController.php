<?php
require_once "models/MetodoPago.php";

class MetodoPagoController {
    // Ver: veterinario y secretaria. Crear/editar: solo veterinario.
    public function listar() {
        $metodos = MetodoPago::obtenerTodos();
        require "views/metodopago/lista.php";
    }

    public function crear() {
        Sesion::requerirRol(['veterinario']);
        $metodo = null;
        require "views/metodopago/formulario.php";
    }

    public function guardar() {
        Sesion::requerirRol(['veterinario']);
        MetodoPago::agregar($this->datos());
        Sesion::flash('ok', 'Método de pago agregado.');
        redirigir("index.php?c=metodopago");
    }

    public function editar() {
        Sesion::requerirRol(['veterinario']);
        $metodo = MetodoPago::obtenerPorId((int)($_GET['id'] ?? 0));
        if (!$metodo) {
            Sesion::flash('error', 'El método de pago no existe.');
            redirigir("index.php?c=metodopago");
        }
        require "views/metodopago/formulario.php";
    }

    public function actualizar() {
        Sesion::requerirRol(['veterinario']);
        MetodoPago::actualizar((int)$_POST['id'], $this->datos());
        Sesion::flash('ok', 'Método de pago actualizado.');
        redirigir("index.php?c=metodopago");
    }

    private function datos() {
        return [
            'nombre'      => trim($_POST['nombre'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'activo'      => isset($_POST['activo']) ? 1 : 0,
        ];
    }
}
?>
