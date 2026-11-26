<?php require "views/plantilla/encabezado.php"; ?>
<h2>Métodos de pago</h2>
<table>
  <tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Estado</th>
      <?php if (Sesion::esVeterinario()): ?><th>Acciones</th><?php endif; ?></tr>
  <?php foreach ($metodos as $m): ?>
  <tr>
    <td><?= $m['id'] ?></td>
    <td><?= e($m['nombre']) ?></td>
    <td><?= e($m['descripcion']) ?></td>
    <td><span class="tag <?= $m['activo'] ? 'verde' : 'gris' ?>"><?= $m['activo'] ? 'Activo' : 'Inactivo' ?></span></td>
    <?php if (Sesion::esVeterinario()): ?>
      <td><a class="btn btn-peq" href="?c=metodopago&a=editar&id=<?= $m['id'] ?>">Editar</a></td>
    <?php endif; ?>
  </tr>
  <?php endforeach; ?>
  <?php if (!$metodos): ?><tr><td colspan="5" class="vacio">No hay métodos de pago.</td></tr><?php endif; ?>
</table>
<?php if (Sesion::esVeterinario()): ?>
  <p><a class="btn" href="?c=metodopago&a=crear">Agregar Método de Pago</a></p>
<?php else: ?>
  <p class="vacio">Solo el veterinario puede modificar los métodos de pago.</p>
<?php endif; ?>
<?php require "views/plantilla/pie.php"; ?>
