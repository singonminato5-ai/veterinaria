<?php require "views/plantilla/encabezado.php"; ?>
<h2>Tratamientos</h2>
<div class="tabla-scroll">
<table>
  <tr><th>N°</th><th>Fecha</th><th>Mascota</th><th>Servicio</th><th>Diagnóstico</th>
      <th>Costo (Bs)</th><th>Estado</th><th>Pago</th><th>Acciones</th></tr>
  <?php foreach ($tratamientos as $t):
        $claseEstado = ['En curso' => 'azul', 'Finalizado' => 'verde', 'Cancelado' => 'gris'][$t['estado']] ?? 'gris'; ?>
  <tr>
    <td><?= $t['id'] ?></td>
    <td><?= e(substr($t['fecha'], 0, 10)) ?></td>
    <td><?= e($t['mascota']) ?><br><small><?= e($t['dueno']) ?></small></td>
    <td><?= e($t['servicio']) ?></td>
    <td><?= e($t['diagnostico']) ?></td>
    <td><?= number_format($t['costo'], 2) ?></td>
    <td><span class="tag <?= $claseEstado ?>"><?= e($t['estado']) ?></span></td>
    <td>
      <span class="tag <?= $t['pagado'] ? 'verde' : 'rojo' ?>"><?= $t['pagado'] ? 'Pagado' : 'Pendiente' ?></span>
      <?php if ($t['metodo_pago']): ?><br><small><?= e($t['metodo_pago']) ?></small><?php endif; ?>
    </td>
    <td><a class="btn btn-peq" href="?c=tratamiento&a=editar&id=<?= $t['id'] ?>">Editar</a></td>
  </tr>
  <?php endforeach; ?>
  <?php if (!$tratamientos): ?><tr><td colspan="9" class="vacio">No hay tratamientos registrados.</td></tr><?php endif; ?>
</table>
</div>
<p><a class="btn" href="?c=tratamiento&a=crear">Nuevo Tratamiento</a></p>
<?php require "views/plantilla/pie.php"; ?>
