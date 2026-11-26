<?php $flash = Sesion::leerFlash(); $otro = $rol === 'veterinario' ? 'secretaria' : 'veterinario'; ?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ingreso - Veterinaria</title>
<style>
  * { box-sizing: border-box; }
  body { font-family: Arial, sans-serif; margin: 0; min-height: 100vh; display: flex;
         align-items: center; justify-content: center;
         background: linear-gradient(135deg, #0f766e, #14b8a6); }
  .login { background: #fff; width: 92%; max-width: 380px; padding: 30px; border-radius: 10px;
           box-shadow: 0 10px 30px rgba(0,0,0,.2); }
  h1 { margin: 0 0 4px; color: #0f766e; font-size: 24px; text-align: center; }
  p.sub { text-align: center; color: #64748b; margin: 0 0 20px; }
  .tabs { display: flex; margin-bottom: 20px; border-radius: 6px; overflow: hidden;
          border: 1px solid #0f766e; }
  .tabs a { flex: 1; text-align: center; padding: 9px; text-decoration: none;
            color: #0f766e; font-size: 14px; }
  .tabs a.activo { background: #0f766e; color: #fff; font-weight: bold; }
  label { font-weight: bold; font-size: 14px; }
  input { width: 100%; padding: 10px; margin: 5px 0 15px; border: 1px solid #b9c5c3;
          border-radius: 4px; font-size: 14px; }
  button { width: 100%; background: #0f766e; color: #fff; border: 0; padding: 11px;
           border-radius: 4px; font-size: 15px; cursor: pointer; }
  .alerta { padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; }
  .alerta.ok { background: #d1fae5; color: #065f46; }
  .alerta.error { background: #fee2e2; color: #991b1b; }
</style></head><body>
<div class="login">
  <h1>🐾 Veterinaria</h1>
  <p class="sub">Ingreso como <strong><?= e($rolNombre) ?></strong></p>

  <div class="tabs">
    <a href="?c=auth&a=login&rol=veterinario" class="<?= $rol === 'veterinario' ? 'activo' : '' ?>">Veterinario</a>
    <a href="?c=auth&a=login&rol=secretaria"  class="<?= $rol === 'secretaria'  ? 'activo' : '' ?>">Secretaria</a>
  </div>

  <?php if ($flash): ?>
    <div class="alerta <?= e($flash['tipo']) ?>"><?= e($flash['mensaje']) ?></div>
  <?php endif; ?>

  <form method="post" action="?c=auth&a=entrar">
    <input type="hidden" name="rol" value="<?= e($rol) ?>">
    <label>Usuario:</label>
    <input name="usuario" required autofocus autocomplete="username">
    <label>Contraseña:</label>
    <input type="password" name="clave" required autocomplete="current-password">
    <button type="submit">Ingresar</button>
  </form>
</div>
</body></html>
