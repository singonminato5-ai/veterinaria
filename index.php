<?php
require_once "config/Sesion.php";
Sesion::iniciar();

$controladores = [
    'auth'        => 'AuthController',
    'inicio'      => 'InicioController',
    'mascota'     => 'MascotaController',
    'tratamiento' => 'TratamientoController',
    'historial'   => 'HistorialController',
    'metodopago'  => 'MetodoPagoController',
    'servicio'    => 'ServicioController',
    'secretaria'  => 'SecretariaController',
];

$c = $_GET['c'] ?? 'inicio';
$a = $_GET['a'] ?? 'listar';

if (!isset($controladores[$c])) $c = 'inicio';

// Todo requiere sesión, excepto el módulo de autenticación
if ($c !== 'auth') Sesion::requerirLogin();

$clase = $controladores[$c];
require_once "controllers/$clase.php";
$controlador = new $clase();

// Las acciones que modifican datos solo se aceptan por POST
$soloPost = ['guardar', 'actualizar', 'entrar'];
if (in_array($a, $soloPost, true) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $a = 'listar';
}
if (!is_callable([$controlador, $a])) {
    $a = ($c === 'auth') ? 'login' : 'listar';
}
$controlador->$a();
?>
