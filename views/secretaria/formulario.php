<?php require "views/plantilla/encabezado.php"; $edit = $secretaria !== null; ?>
<h2><?= $edit ? 'Editar Secretaria' : 'Nueva Secretaria' ?></h2>
<form method="post" action="?c=secretaria&a=<?= $edit ? 'actualizar' : 'guardar' ?>">
  <?php if ($edit): ?><input type="hidden" name="id" value="<?= $secretaria['id'] ?>"><?php endif; ?>

  <label>Nombre completo:</label>
  <input name="nombre" required value="<?= e($secretaria['nombre'] ?? '') ?>">

  <label>Usuario (para el login):</label>
  <input name="usuario" required autocomplete="off" value="<?= e($secretaria['usuario'] ?? '') ?>">

  <label>Contraseña<?= $edit ? ' (déjala vacía para no cambiarla)' : '' ?>:</label>
  <input type="password" name="clave" autocomplete="new-password" <?= $edit ? '' : 'required' ?>>

  <label>Teléfono:</label>
  <input name="telefono" value="<?= e($secretaria['telefono'] ?? '') ?>">

  <div class="check">
    <input type="checkbox" id="activo" name="activo" <?= (!$edit || $secretaria['activo']) ? 'checked' : '' ?>>
    <label for="activo">Cuenta activa (puede iniciar sesión)</label>
  </div>

  <div class="acciones">
    <button type="submit"><?= $edit ? 'Guardar cambios' : 'Guardar' ?></button>
    <a class="btn btn-sec" href="?c=secretaria">Cancelar</a>
  </div>
</form>
<?php require "views/plantilla/pie.php"; ?>
