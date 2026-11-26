<?php
require_once "models/Tratamiento.php";
require_once "models/Mascota.php";
require_once "models/Servicio.php";
require_once "models/MetodoPago.php";

class TratamientoController {
    public function listar() {
        $tratamientos = Tratamiento::obtenerTodos();
        require "views/tratamiento/lista.php";
    }

    public function crear() {
        $tratamiento = null;
        $this->cargarListas($mascotas, $servicios, $metodos);
        require "views/tratamiento/formulario.php";
    }

    public function guardar() {
        Tratamiento::agregar($this->datos(), Sesion::usuario()['id']);
        Sesion::flash('ok', 'Tratamiento registrado correctamente.');
        redirigir("index.php?c=tratamiento");
    }

    public function editar() {
        $tratamiento = Tratamiento::obtenerPorId((int)($_GET['id'] ?? 0));
        if (!$tratamiento) {
            Sesion::flash('error', 'El tratamiento no existe.');
            redirigir("index.php?c=tratamiento");
        }
        $this->cargarListas($mascotas, $servicios, $metodos);
        require "views/tratamiento/formulario.php";
    }

    public function actualizar() {
        Tratamiento::actualizar((int)$_POST['id'], $this->datos());
        Sesion::flash('ok', 'Tratamiento actualizado.');
        redirigir("index.php?c=tratamiento");
    }

    private function cargarListas(&$mascotas, &$servicios, &$metodos) {
        $mascotas  = Mascota::obtenerTodas();
        $servicios = Servicio::obtenerTodos();
        $metodos   = MetodoPago::obtenerTodos();
    }

    private function datos() {
        $estados = ['En curso', 'Finalizado', 'Cancelado'];
        $metodo  = (int)($_POST['metodo_pago_id'] ?? 0);
        return [
            'mascota_id'     => (int)($_POST['mascota_id'] ?? 0),
            'servicio_id'    => (int)($_POST['servicio_id'] ?? 0),
            'metodo_pago_id' => $metodo > 0 ? $metodo : null,
            'diagnostico'    => trim($_POST['diagnostico'] ?? ''),
            'indicaciones'   => trim($_POST['indicaciones'] ?? ''),
            'costo'          => max(0, (float)($_POST['costo'] ?? 0)),
            'estado'         => in_array($_POST['estado'] ?? '', $estados) ? $_POST['estado'] : 'En curso',
            'pagado'         => isset($_POST['pagado']) ? 1 : 0,
        ];
    }
}
?>
