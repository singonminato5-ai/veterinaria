<?php require "views/plantilla/encabezado.php"; $edit = $mascota !== null; ?>
<h2><?= $edit ? 'Editar Mascota' : 'Registro de Mascota' ?></h2>
<form method="post" action="?c=mascota&a=<?= $edit ? 'actualizar' : 'guardar' ?>">
  <?php if ($edit): ?><input type="hidden" name="id" value="<?= $mascota['id'] ?>"><?php endif; ?>

  <label>Nombre de la mascota:</label>
  <input name="nombre" required value="<?= e($mascota['nombre'] ?? '') ?>">

  <label>Especie:</label>
  <input name="especie" required placeholder="Perro, Gato, Ave..." value="<?= e($mascota['especie'] ?? '') ?>">

  <label>Raza:</label>
  <input name="raza" value="<?= e($mascota['raza'] ?? '') ?>">

  <label>Sexo:</label>
  <select name="sexo" required>
    <?php foreach (['Macho', 'Hembra'] as $s): ?>
      <option <?= ($mascota['sexo'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
    <?php endforeach; ?>
  </select>

  <label>Edad (años):</label>
  <input type="number" name="edad" step="0.1" min="0" required value="<?= e($mascota['edad'] ?? '') ?>">

  <label>Peso (kg):</label>
  <input type="number" name="peso" step="0.01" min="0" required value="<?= e($mascota['peso'] ?? '') ?>">

  <label>Nombre del dueño:</label>
  <input name="dueno" required value="<?= e($mascota['dueno'] ?? '') ?>">

  <label>Teléfono del dueño:</label>
  <input name="telefono" value="<?= e($mascota['telefono'] ?? '') ?>">

  <div class="acciones">
    <button type="submit"><?= $edit ? 'Guardar cambios' : 'Guardar' ?></button>
    <a class="btn btn-sec" href="?c=mascota">Cancelar</a>
  </div>
</form>
<?php require "views/plantilla/pie.php"; ?>
