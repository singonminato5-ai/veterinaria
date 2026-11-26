<?php
require_once "models/Historial.php";
require_once "models/Mascota.php";
require_once "models/Servicio.php";

// Ver: veterinario y secretaria. Registrar/editar: solo veterinario.
class HistorialController {
    private $unidades = ['Días' => 'day', 'Semanas' => 'week', 'Meses' => 'month'];

    public function listar() {
        $mascotaId = (int)($_GET['mascota_id'] ?? 0);
        $mascotaFiltro = $mascotaId > 0 ? Mascota::obtenerPorId($mascotaId) : null;
        $historial = Historial::obtenerTodos($mascotaFiltro ? $mascotaId : 0);
        require "views/historial/lista.php";
    }

    public function crear() {
        Sesion::requerirRol(['veterinario']);
        $registro = null;
        $mascotaSel = (int)($_GET['mascota_id'] ?? 0);
        $this->cargarListas($mascotas, $servicios);
        $unidades = array_keys($this->unidades);
        require "views/historial/formulario.php";
    }

    public function guardar() {
        Sesion::requerirRol(['veterinario']);
        $d = $this->datos(date('Y-m-d'));
        Historial::agregar($d, Sesion::usuario()['id']);
        Sesion::flash('ok', 'Historial registrado. Próximo retorno: ' . date('d/m/Y', strtotime($d['fecha_retorno'])));
        redirigir("index.php?c=historial&mascota_id=" . $d['mascota_id']);
    }

    public function editar() {
        Sesion::requerirRol(['veterinario']);
        $registro = Historial::obtenerPorId((int)($_GET['id'] ?? 0));
        if (!$registro) {
            Sesion::flash('error', 'El registro no existe.');
            redirigir("index.php?c=historial");
        }
        $mascotaSel = (int)$registro['mascota_id'];
        $this->cargarListas($mascotas, $servicios);
        $unidades = array_keys($this->unidades);
        require "views/historial/formulario.php";
    }

    public function actualizar() {
        Sesion::requerirRol(['veterinario']);
        $id = (int)$_POST['id'];
        $actual = Historial::obtenerPorId($id);
        if (!$actual) redirigir("index.php?c=historial");

        // El retorno se calcula desde la fecha original de la visita
        $d = $this->datos(substr($actual['fecha'], 0, 10));
        Historial::actualizar($id, $d);
        Sesion::flash('ok', 'Historial actualizado.');
        redirigir("index.php?c=historial&mascota_id=" . $d['mascota_id']);
    }

    private function cargarListas(&$mascotas, &$servicios) {
        $mascotas  = Mascota::obtenerTodas();
        $servicios = Servicio::obtenerTodos();
    }

    private function datos($fechaBase) {
        $cant     = max(1, (int)($_POST['volver_cantidad'] ?? 1));
        $unidad   = $_POST['volver_unidad'] ?? 'Días';
        if (!isset($this->unidades[$unidad])) $unidad = 'Días';
        $servicio = (int)($_POST['servicio_id'] ?? 0);

        $retorno = (new DateTime($fechaBase))
                       ->modify("+$cant " . $this->unidades[$unidad])
                       ->format('Y-m-d');
        return [
            'mascota_id'      => (int)($_POST['mascota_id'] ?? 0),
            'servicio_id'     => $servicio > 0 ? $servicio : null,
            'tratamiento'     => trim($_POST['tratamiento'] ?? ''),
            'volver_cantidad' => $cant,
            'volver_unidad'   => $unidad,
            'fecha_retorno'   => $retorno,
            'descripcion'     => trim($_POST['descripcion'] ?? ''),
        ];
    }
}
?>
