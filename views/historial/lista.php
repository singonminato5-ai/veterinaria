<?php require "views/plantilla/encabezado.php"; $hoy = date('Y-m-d'); ?>
<h2>Historial clínico<?= $mascotaFiltro ? ' de ' . e($mascotaFiltro['nombre']) : '' ?></h2>
<?php if ($mascotaFiltro): ?>
  <p>Dueño: <strong><?= e($mascotaFiltro['dueno']) ?></strong> · Tel: <?= e($mascotaFiltro['telefono']) ?>
     · <a href="?c=historial">Ver todos los historiales</a></p>
<?php endif; ?>

<div class="tabla-scroll">
<table>
  <tr><th>N°</th><th>Fecha</th><?php if (!$mascotaFiltro): ?><th>Mascota</th><?php endif; ?>
      <th>Tratamiento / Servicio</th><th>Debe volver en</th><th>Fecha de retorno</th>
      <th>Descripción</th><?php if (Sesion::esVeterinario()): ?><th>Acciones</th><?php endif; ?></tr>
  <?php foreach ($historial as $h):
        $sinRetorno = empty($h['fecha_retorno']);   // registro automático de alta
        $vencido  = !$sinRetorno && $h['fecha_retorno'] < $hoy;
        $hoyMismo = !$sinRetorno && $h['fecha_retorno'] === $hoy; ?>
  <tr>
    <td><?= $h['id'] ?></td>
    <td><?= e(substr($h['fecha'], 0, 10)) ?></td>
    <?php if (!$mascotaFiltro): ?>
      <td><a href="?c=historial&mascota_id=<?= $h['mascota_id'] ?>"><?= e($h['mascota']) ?></a><br><small><?= e($h['dueno']) ?></small></td>
    <?php endif; ?>
    <td><?= e($h['tratamiento']) ?><?php if ($h['servicio']): ?><br><small>Servicio: <?= e($h['servicio']) ?></small><?php endif; ?></td>
    <td><?= $sinRetorno ? '—' : (int)$h['volver_cantidad'] . ' ' . e($h['volver_unidad']) ?></td>
    <td>
      <?php if ($sinRetorno): ?>—
      <?php else: ?>
        <?= e(date('d/m/Y', strtotime($h['fecha_retorno']))) ?><br>
        <?php if ($hoyMismo): ?><span class="tag azul">Es hoy</span>
        <?php elseif ($vencido): ?><span class="tag rojo">Vencido</span>
        <?php else: ?><span class="tag verde">Próximo</span><?php endif; ?>
      <?php endif; ?>
    </td>
    <td style="text-align:left"><?= nl2br(e($h['descripcion'])) ?></td>
    <?php if (Sesion::esVeterinario()): ?>
      <td><a class="btn btn-peq" href="?c=historial&a=editar&id=<?= $h['id'] ?>">Editar</a></td>
    <?php endif; ?>
  </tr>
  <?php endforeach; ?>
  <?php if (!$historial): ?><tr><td colspan="8" class="vacio">No hay registros en el historial.</td></tr><?php endif; ?>
</table>
</div>

<?php if (Sesion::esVeterinario()): ?>
  <p><a class="btn" href="?c=historial&a=crear<?= $mascotaFiltro ? '&mascota_id=' . $mascotaFiltro['id'] : '' ?>">Nuevo Registro de Historial</a></p>
<?php else: ?>
  <p class="vacio">Solo el veterinario puede registrar o editar el historial.</p>
<?php endif; ?>
<?php require "views/plantilla/pie.php"; ?>
