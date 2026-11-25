<?php require "views/plantilla/encabezado.php"; $edit = $servicio !== null; ?>
<h2><?= $edit ? 'Editar Servicio' : 'Nuevo Servicio' ?></h2>
<form method="post" action="?c=servicio&a=<?= $edit ? 'actualizar' : 'guardar' ?>">
  <?php if ($edit): ?><input type="hidden" name="id" value="<?= $servicio['id'] ?>"><?php endif; ?>

  <label>Nombre:</label>
  <input name="nombre" required value="<?= e($servicio['nombre'] ?? '') ?>">

  <label>Descripción:</label>
  <input name="descripcion" value="<?= e($servicio['descripcion'] ?? '') ?>">

  <label>Precio (Bs):</label>
  <input type="number" name="precio" step="0.01" min="0" required value="<?= e($servicio['precio'] ?? '') ?>">

  <div class="check">
    <input type="checkbox" id="activo" name="activo" <?= (!$edit || $servicio['activo']) ? 'checked' : '' ?>>
    <label for="activo">Servicio activo (disponible para nuevos tratamientos)</label>
  </div>

  <div class="acciones">
    <button type="submit"><?= $edit ? 'Guardar cambios' : 'Guardar' ?></button>
    <a class="btn btn-sec" href="?c=servicio">Cancelar</a>
  </div>
</form>
<?php require "views/plantilla/pie.php"; ?>
