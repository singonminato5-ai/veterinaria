<?php require "views/plantilla/encabezado.php"; ?>
<h2>Secretaria</h2>
<table>
  <tr><th>ID</th><th>Nombre</th><th>Usuario</th><th>Teléfono</th><th>Estado</th><th>Acciones</th></tr>
  <?php foreach ($secretarias as $s): ?>
  <tr>
    <td><?= $s['id'] ?></td>
    <td><?= e($s['nombre']) ?></td>
    <td><?= e($s['usuario']) ?></td>
    <td><?= e($s['telefono']) ?></td>
    <td><span class="tag <?= $s['activo'] ? 'verde' : 'gris' ?>"><?= $s['activo'] ? 'Activa' : 'Inactiva' ?></span></td>
    <td><a class="btn btn-peq" href="?c=secretaria&a=editar&id=<?= $s['id'] ?>">Editar</a></td>
  </tr>
  <?php endforeach; ?>
  <?php if (!$secretarias): ?><tr><td colspan="6" class="vacio">No hay secretarias registradas.</td></tr><?php endif; ?>
</table>
<p><a class="btn" href="?c=secretaria&a=crear">Agregar Secretaria</a></p>
<?php require "views/plantilla/pie.php"; ?>
