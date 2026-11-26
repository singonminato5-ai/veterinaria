<?php require "views/plantilla/encabezado.php"; $edit = $metodo !== null; ?>
<h2><?= $edit ? 'Editar Método de Pago' : 'Nuevo Método de Pago' ?></h2>
<form method="post" action="?c=metodopago&a=<?= $edit ? 'actualizar' : 'guardar' ?>">
  <?php if ($edit): ?><input type="hidden" name="id" value="<?= $metodo['id'] ?>"><?php endif; ?>

  <label>Nombre:</label>
  <input name="nombre" required value="<?= e($metodo['nombre'] ?? '') ?>">

  <label>Descripción:</label>
  <input name="descripcion" value="<?= e($metodo['descripcion'] ?? '') ?>">

  <div class="check">
    <input type="checkbox" id="activo" name="activo" <?= (!$edit || $metodo['activo']) ? 'checked' : '' ?>>
    <label for="activo">Método activo</label>
  </div>

  <div class="acciones">
    <button type="submit"><?= $edit ? 'Guardar cambios' : 'Guardar' ?></button>
    <a class="btn btn-sec" href="?c=metodopago">Cancelar</a>
  </div>
</form>
<?php require "views/plantilla/pie.php"; ?>
