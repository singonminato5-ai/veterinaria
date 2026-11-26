<?php require "views/plantilla/encabezado.php"; $edit = $registro !== null; ?>
<h2><?= $edit ? 'Editar Historial' : 'Nuevo Registro de Historial' ?></h2>
<form method="post" action="?c=historial&a=<?= $edit ? 'actualizar' : 'guardar' ?>">
  <?php if ($edit): ?><input type="hidden" name="id" value="<?= $registro['id'] ?>"><?php endif; ?>

  <label>Mascota:</label>
  <select name="mascota_id" required>
    <option value="">-- Seleccione --</option>
    <?php foreach ($mascotas as $m): ?>
      <option value="<?= $m['id'] ?>" <?= $mascotaSel == $m['id'] ? 'selected' : '' ?>>
        <?= e($m['nombre']) ?> (<?= e($m['especie']) ?>) - <?= e($m['dueno']) ?></option>
    <?php endforeach; ?>
  </select>

  <label>Servicio (opcional):</label>
  <select name="servicio_id">
    <option value="">-- Ninguno --</option>
    <?php foreach ($servicios as $s):
          $actual = ($registro['servicio_id'] ?? '') == $s['id'];
          if (!$s['activo'] && !$actual) continue; ?>
      <option value="<?= $s['id'] ?>" <?= $actual ? 'selected' : '' ?>><?= e($s['nombre']) ?></option>
    <?php endforeach; ?>
  </select>

  <label>Tratamiento que se le dará:</label>
  <input name="tratamiento" required placeholder="Ej: Antibiótico cada 12 h por 7 días"
         value="<?= e($registro['tratamiento'] ?? '') ?>">

  <label>¿En cuánto tiempo debe volver?</label>
  <div style="display:flex; gap:10px;">
    <input type="number" name="volver_cantidad" min="1" required style="flex:1"
           value="<?= e($registro['volver_cantidad'] ?? 7) ?>">
    <select name="volver_unidad" style="flex:2">
      <?php foreach ($unidades as $u): ?>
        <option <?= ($registro['volver_unidad'] ?? 'Días') === $u ? 'selected' : '' ?>><?= $u ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <?php if ($edit && !empty($registro['fecha_retorno'])): ?>
    <small>Fecha de retorno actual: <strong><?= e(date('d/m/Y', strtotime($registro['fecha_retorno']))) ?></strong>
    (se recalcula desde la fecha de la visita: <?= e(substr($registro['fecha'], 0, 10)) ?>)</small><br><br>
  <?php endif; ?>

  <label>Descripción:</label>
  <textarea name="descripcion" rows="4" placeholder="Observaciones, evolución, recomendaciones al dueño..."><?= e($registro['descripcion'] ?? '') ?></textarea>

  <div class="acciones">
    <button type="submit"><?= $edit ? 'Guardar cambios' : 'Guardar' ?></button>
    <a class="btn btn-sec" href="?c=historial<?= $mascotaSel ? '&mascota_id=' . $mascotaSel : '' ?>">Cancelar</a>
  </div>
</form>
<?php require "views/plantilla/pie.php"; ?>
