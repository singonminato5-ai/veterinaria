<?php require "views/plantilla/encabezado.php"; require_once "config/Pago.php"; $edit = $tratamiento !== null; ?>
<h2><?= $edit ? 'Editar Tratamiento' : 'Nuevo Tratamiento' ?></h2>
<form method="post" action="?c=tratamiento&a=<?= $edit ? 'actualizar' : 'guardar' ?>">
  <?php if ($edit): ?><input type="hidden" name="id" value="<?= $tratamiento['id'] ?>"><?php endif; ?>

  <label>Mascota:</label>
  <select name="mascota_id" required>
    <option value="">-- Seleccione --</option>
    <?php foreach ($mascotas as $m): ?>
      <option value="<?= $m['id'] ?>" <?= ($tratamiento['mascota_id'] ?? '') == $m['id'] ? 'selected' : '' ?>>
        <?= e($m['nombre']) ?> (<?= e($m['especie']) ?>) - <?= e($m['dueno']) ?></option>
    <?php endforeach; ?>
  </select>

  <label>Servicio:</label>
  <select name="servicio_id" id="servicio" required>
    <option value="">-- Seleccione --</option>
    <?php foreach ($servicios as $s):
          $actual = ($tratamiento['servicio_id'] ?? '') == $s['id'];
          if (!$s['activo'] && !$actual) continue; ?>
      <option value="<?= $s['id'] ?>" data-precio="<?= e($s['precio']) ?>" <?= $actual ? 'selected' : '' ?>>
        <?= e($s['nombre']) ?> - Bs <?= number_format($s['precio'], 2) ?></option>
    <?php endforeach; ?>
  </select>

  <label>Diagnóstico:</label>
  <textarea name="diagnostico" rows="3" required><?= e($tratamiento['diagnostico'] ?? '') ?></textarea>

  <label>Indicaciones / medicación:</label>
  <textarea name="indicaciones" rows="3"><?= e($tratamiento['indicaciones'] ?? '') ?></textarea>

  <label>Costo (Bs):</label>
  <input type="number" name="costo" id="costo" step="0.01" min="0" required value="<?= e($tratamiento['costo'] ?? '') ?>">

  <label>Estado:</label>
  <select name="estado">
    <?php foreach (['En curso', 'Finalizado', 'Cancelado'] as $est): ?>
      <option <?= ($tratamiento['estado'] ?? 'En curso') === $est ? 'selected' : '' ?>><?= $est ?></option>
    <?php endforeach; ?>
  </select>

  <label>Método de pago:</label>
  <select name="metodo_pago_id">
    <option value="">-- Sin definir --</option>
    <?php foreach ($metodos as $mp):
          $actual = ($tratamiento['metodo_pago_id'] ?? '') == $mp['id'];
          if (!$mp['activo'] && !$actual) continue; ?>
      <option value="<?= $mp['id'] ?>" data-qr="<?= stripos($mp['nombre'], 'qr') !== false ? '1' : '0' ?>"
              <?= $actual ? 'selected' : '' ?>><?= e($mp['nombre']) ?></option>
    <?php endforeach; ?>
  </select>

  <!-- Se muestra solo si el método elegido es QR; el monto sale del campo "Costo" -->
  <div id="panelQR" style="display:none; text-align:center; border:1px solid #99d5cd; background:#f0fdfa;
       border-radius:8px; padding:16px; margin-bottom:15px;">
    <div style="font-size:14px; color:#0f766e;">Monto a pagar</div>
    <div id="qrMonto" style="font-size:30px; font-weight:bold; color:#0f766e; margin-bottom:10px;"></div>
    <div id="qrImagen" style="display:inline-block; background:#fff; padding:6px; border:1px solid #d5dcdb;"></div>
    <div id="qrNota" style="font-size:13px; color:#475569; margin-top:8px;"></div>
  </div>

  <div class="check">
    <input type="checkbox" id="pagado" name="pagado" <?= ($tratamiento['pagado'] ?? 0) ? 'checked' : '' ?>>
    <label for="pagado">Pago realizado</label>
  </div>

  <div class="acciones">
    <button type="submit"><?= $edit ? 'Guardar cambios' : 'Guardar' ?></button>
    <a class="btn btn-sec" href="?c=tratamiento">Cancelar</a>
  </div>
</form>

<script src="assets/qr.js"></script>
<script>
var CLINICA = <?= json_encode(Pago::CLINICA) ?>,
    CUENTA  = <?= json_encode(Pago::CUENTA) ?>,
    MONEDA  = <?= json_encode(Pago::MONEDA) ?>;

var selMascota = document.querySelector('[name=mascota_id]'),
    selServicio = document.getElementById('servicio'),
    selMetodo = document.querySelector('[name=metodo_pago_id]'),
    inpCosto = document.getElementById('costo'),
    panelQR = document.getElementById('panelQR');

function textoOpcion(sel) {
  return sel.selectedIndex > 0 ? sel.options[sel.selectedIndex].text.split(' - ')[0].replace(/\s*\(.*$/, '').trim() : '';
}

// Dibuja (o oculta) el QR según el método de pago y el costo escrito
function actualizarQR() {
  var opt = selMetodo.options[selMetodo.selectedIndex];
  var esQR = opt && opt.getAttribute('data-qr') === '1';
  panelQR.style.display = esQR ? 'block' : 'none';
  if (!esQR) return;

  var monto = parseFloat(inpCosto.value);
  var montoTxt = (isNaN(monto) || monto < 0 ? 0 : monto).toFixed(2);
  document.getElementById('qrMonto').textContent = MONEDA + ' ' + montoTxt;

  var imagen = document.getElementById('qrImagen'), nota = document.getElementById('qrNota');
  if (!(monto > 0)) {
    imagen.style.display = 'none';
    nota.textContent = 'Ingresa un costo mayor a 0 para generar el QR.';
    return;
  }
  var lineas = ['PAGO ' + CLINICA.toUpperCase()];
  if (CUENTA) lineas.push('Cuenta: ' + CUENTA);
  var mascota = textoOpcion(selMascota), servicio = textoOpcion(selServicio);
  if (mascota)  lineas.push('Mascota: ' + mascota);
  if (servicio) lineas.push('Servicio: ' + servicio);
  lineas.push('Monto: ' + MONEDA + ' ' + montoTxt);

  imagen.innerHTML = qrSvg(lineas.join('\n'), 240);
  imagen.style.display = 'inline-block';
  nota.textContent = 'El QR se actualiza automáticamente si cambias el costo.';
}

// Al elegir un servicio nuevo, sugiere su precio como costo
selServicio.addEventListener('change', function () {
  var precio = this.options[this.selectedIndex].getAttribute('data-precio');
  if (precio !== null) inpCosto.value = precio;
  actualizarQR();
});
inpCosto.addEventListener('input', actualizarQR);
selMetodo.addEventListener('change', actualizarQR);
selMascota.addEventListener('change', actualizarQR);
actualizarQR();
</script>
<?php require "views/plantilla/pie.php"; ?>
