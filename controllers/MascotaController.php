<?php
require_once "models/Mascota.php";
require_once "models/Historial.php";

class MascotaController {
    public function listar() {
        $buscar   = trim($_GET['q'] ?? '');
        $mascotas = Mascota::obtenerTodas($buscar);
        require "views/mascota/lista.php";
    }

    public function crear() {
        $mascota = null;
        require "views/mascota/formulario.php";
    }

    public function guardar() {
        $d  = $this->datos();
        $id = Mascota::agregar($d);
        // La mascota queda en el historial clínico automáticamente
        Historial::registrarAlta($id, Sesion::usuario()['id'], $d);
        Sesion::flash('ok', 'Mascota registrada correctamente y agregada al historial.');
        redirigir("index.php?c=mascota");
    }

    public function editar() {
        $mascota = Mascota::obtenerPorId((int)($_GET['id'] ?? 0));
        if (!$mascota) {
            Sesion::flash('error', 'La mascota no existe.');
            redirigir("index.php?c=mascota");
        }
        require "views/mascota/formulario.php";
    }

    public function actualizar() {
        Mascota::actualizar((int)$_POST['id'], $this->datos());
        Sesion::flash('ok', 'Información de la mascota actualizada.');
        redirigir("index.php?c=mascota");
    }

    private function datos() {
        return [
            'nombre'   => trim($_POST['nombre'] ?? ''),
            'especie'  => trim($_POST['especie'] ?? ''),
            'raza'     => trim($_POST['raza'] ?? ''),
            'sexo'     => in_array($_POST['sexo'] ?? '', ['Macho', 'Hembra']) ? $_POST['sexo'] : 'Macho',
            'edad'     => max(0, (float)($_POST['edad'] ?? 0)),
            'peso'     => max(0, (float)($_POST['peso'] ?? 0)),
            'dueno'    => trim($_POST['dueno'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
        ];
    }
}
?>
