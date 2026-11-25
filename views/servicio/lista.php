<?php require "views/plantilla/encabezado.php"; ?>
<h2>Servicios</h2>
<table>
  <tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Precio (Bs)</th><th>Estado</th>
      <?php if (Sesion::esVeterinario()): ?><th>Acciones</th><?php endif; ?></tr>
  <?php foreach ($servicios as $s): ?>
  <tr>
    <td><?= $s['id'] ?></td>
    <td><?= e($s['nombre']) ?></td>
    <td><?= e($s['descripcion']) ?></td>
    <td><?= number_format($s['precio'], 2) ?></td>
    <td><span class="tag <?= $s['activo'] ? 'verde' : 'gris' ?>"><?= $s['activo'] ? 'Activo' : 'Inactivo' ?></span></td>
    <?php if (Sesion::esVeterinario()): ?>
      <td><a class="btn btn-peq" href="?c=servicio&a=editar&id=<?= $s['id'] ?>">Editar</a></td>
    <?php endif; ?>
  </tr>
  <?php endforeach; ?>
  <?php if (!$servicios): ?><tr><td colspan="6" class="vacio">No hay servicios.</td></tr><?php endif; ?>
</table>
<?php if (Sesion::esVeterinario()): ?>
  <p><a class="btn" href="?c=servicio&a=crear">Agregar Servicio</a></p>
<?php else: ?>
  <p class="vacio">Solo el veterinario puede modificar los servicios.</p>
<?php endif; ?>
<?php require "views/plantilla/pie.php"; ?>
