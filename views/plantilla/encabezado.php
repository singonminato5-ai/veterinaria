<?php $u = Sesion::usuario(); $flash = Sesion::leerFlash(); ?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sistema de Veterinaria</title>
<style>
  * { box-sizing: border-box; }
  body { font-family: Arial, sans-serif; background: #eef5f4; margin: 0; color: #1f2d2b; }
  nav { background: #0f766e; padding: 12px 30px; display: flex; flex-wrap: wrap;
        align-items: center; gap: 6px 20px; }
  nav .marca { color: #fff; font-weight: bold; font-size: 18px; margin-right: 10px; }
  nav a { color: #d7f3ef; text-decoration: none; }
  nav a:hover { color: #fff; text-decoration: underline; }
  nav .usuario { margin-left: auto; color: #d7f3ef; font-size: 14px; }
  nav .usuario a { color: #fff; font-weight: bold; margin-left: 10px; }
  .caja { width: 90%; max-width: 1000px; margin: 25px auto; background: #fff;
          padding: 24px; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
  h2 { margin-top: 0; color: #0f766e; }
  table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
  th, td { border: 1px solid #d5dcdb; padding: 8px; text-align: center; font-size: 14px; }
  th { background: #0f766e; color: #fff; }
  tr:nth-child(even) td { background: #f6faf9; }
  .tabla-scroll { overflow-x: auto; }
  label { font-weight: bold; font-size: 14px; }
  input, select, textarea { width: 100%; padding: 8px; margin: 5px 0 15px;
        border: 1px solid #b9c5c3; border-radius: 4px; font-family: inherit; font-size: 14px; }
  input[type=checkbox] { width: auto; margin: 0 6px 0 0; }
  .check { display: flex; align-items: center; margin-bottom: 15px; }
  .check label { font-weight: normal; }
  button, .btn { background: #0f766e; color: #fff; border: 0; padding: 10px 16px;
        border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;
        font-size: 14px; }
  .btn-sec { background: #64748b; }
  .btn-peq { padding: 5px 12px; font-size: 13px; background: #2563eb; }
  .acciones { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
  .alerta { padding: 10px 14px; border-radius: 4px; margin-bottom: 16px; font-size: 14px; }
  .alerta.ok { background: #d1fae5; color: #065f46; }
  .alerta.error { background: #fee2e2; color: #991b1b; }
  .tag { padding: 2px 8px; border-radius: 10px; font-size: 12px; color: #fff; }
  .tag.verde { background: #16a34a; } .tag.rojo { background: #dc2626; }
  .tag.azul { background: #2563eb; } .tag.gris { background: #6b7280; }
  .tarjetas { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 14px; }
  .tarjeta { background: #f0fdfa; border: 1px solid #99d5cd; border-radius: 8px;
             padding: 16px; text-align: center; text-decoration: none; color: #0f766e; }
  .tarjeta strong { display: block; font-size: 26px; }
  .tarjeta.link:hover { background: #ccfbf1; }
  .buscador { display: flex; gap: 10px; margin-bottom: 15px; }
  .buscador input { margin: 0; }
  .vacio { color: #6b7280; text-align: center; padding: 20px; }
</style></head><body>
<nav>
  <span class="marca">🐾 Veterinaria</span>
  <a href="?c=inicio">Inicio</a>
  <a href="?c=mascota">Mascotas</a>
  <a href="?c=tratamiento">Tratamientos</a>
  <a href="?c=historial">Historial</a>
  <a href="?c=servicio">Servicios</a>
  <a href="?c=metodopago">Métodos de pago</a>
  <?php if (Sesion::esVeterinario()): ?>
    <a href="?c=secretaria">Secretaria</a>
  <?php endif; ?>
  <span class="usuario">
    <?= e($u['nombre']) ?> (<?= e(ucfirst($u['rol'])) ?>)
    <a href="?c=auth&a=salir">Salir</a>
  </span>
</nav>
<div class="caja">
<?php if ($flash): ?>
  <div class="alerta <?= e($flash['tipo']) ?>"><?= e($flash['mensaje']) ?></div>
<?php endif; ?>
