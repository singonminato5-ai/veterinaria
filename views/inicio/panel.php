<?php require "views/plantilla/encabezado.php"; ?>
<h2>Bienvenido/a, <?= e(Sesion::usuario()['nombre']) ?></h2>
<p>Resumen de la clínica:</p>

<div class="tarjetas" style="margin-bottom:24px">
  <div class="tarjeta"><strong><?= $resumen['mascotas'] ?></strong>Mascotas registradas</div>
  <div class="tarjeta"><strong><?= $resumen['en_curso'] ?></strong>Tratamientos en curso</div>
  <div class="tarjeta"><strong><?= $resumen['pendientes'] ?></strong>Pagos pendientes</div>
  <div class="tarjeta"><strong><?= $resumen['servicios'] ?></strong>Servicios activos</div>
</div>

<h2>Accesos rápidos</h2>
<div class="tarjetas">
  <a class="tarjeta link" href="?c=mascota&a=crear">🐶 Registrar mascota</a>
  <a class="tarjeta link" href="?c=tratamiento&a=crear">💊 Nuevo tratamiento</a>
  <a class="tarjeta link" href="?c=historial">📋 Historial clínico</a>
  <a class="tarjeta link" href="?c=servicio">🩺 Servicios</a>
  <a class="tarjeta link" href="?c=metodopago">💳 Métodos de pago</a>
  <?php if (Sesion::esVeterinario()): ?>
    <a class="tarjeta link" href="?c=secretaria">🧑‍💼 Secretaria</a>
  <?php endif; ?>
</div>
<?php require "views/plantilla/pie.php"; ?>
