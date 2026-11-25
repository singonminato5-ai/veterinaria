<?php require "views/plantilla/encabezado.php"; ?>
<h2>Mascotas</h2>
<form class="buscador" method="get">
  <input type="hidden" name="c" value="mascota">
  <input name="q" placeholder="Buscar por mascota, dueño o especie..." value="<?= e($buscar) ?>">
  <button type="submit">Buscar</button>
</form>
<div class="tabla-scroll">
<table>
  <tr><th>ID</th><th>Nombre</th><th>Especie</th><th>Raza</th><th>Sexo</th>
      <th>Edad (años)</th><th>Peso (kg)</th><th>Dueño</th><th>Teléfono</th><th>Acciones</th></tr>
  <?php foreach ($mascotas as $m): ?>
  <tr>
    <td><?= $m['id'] ?></td>
    <td><?= e($m['nombre']) ?></td>
    <td><?= e($m['especie']) ?></td>
    <td><?= e($m['raza']) ?></td>
    <td><?= e($m['sexo']) ?></td>
    <td><?= e($m['edad']) ?></td>
    <td><?= e($m['peso']) ?></td>
    <td><?= e($m['dueno']) ?></td>
    <td><?= e($m['telefono']) ?></td>
    <td><a class="btn btn-peq" href="?c=mascota&a=editar&id=<?= $m['id'] ?>">Editar</a>
        <a class="btn btn-peq" style="background:#0f766e" href="?c=historial&mascota_id=<?= $m['id'] ?>">Historial</a></td>
  </tr>
  <?php endforeach; ?>
  <?php if (!$mascotas): ?><tr><td colspan="10" class="vacio">No hay mascotas registradas.</td></tr><?php endif; ?>
</table>
</div>
<p><a class="btn" href="?c=mascota&a=crear">Registrar Mascota</a></p>
<?php require "views/plantilla/pie.php"; ?>
