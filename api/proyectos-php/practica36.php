<?php
// ── Práctica 36: CRUD con PHP y Supabase ──

// ─────────────────────────────────────────
// CONFIGURACIÓN — reemplaza estos dos valores
// con los de tu proyecto en Supabase:
// Settings → API
// ─────────────────────────────────────────
define('SUPABASE_URL', 'https://jelxabpuqiqenqfzlgfm.supabase.co');   // ← tu Project URL
define('SUPABASE_KEY', 'sb_publishable_XXDLQi-xR1VnM_2BjpkFpw_Yjaze0fC');                       // ← tu anon public key
define('TABLA', 'usuarios');
// ─────────────────────────────────────────

// ── Helper: llamadas a la REST API de Supabase ──
function supabase(string $method, string $endpoint, array $body = null): array {
    $url = SUPABASE_URL . '/rest/v1/' . $endpoint;
    $ch  = curl_init($url);

    $headers = [
        'Content-Type: application/json',
        'apikey: '        . SUPABASE_KEY,
        'Authorization: Bearer ' . SUPABASE_KEY,
        'Prefer: return=representation',
    ];

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER,     $headers);

    if ($body !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }

    $response = curl_exec($ch);
    $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ['status' => $status, 'data' => json_decode($response, true)];
}

$accion  = $_POST['accion']  ?? $_GET['accion']  ?? 'listar';
$mensaje = '';
$error   = '';
$usuario = null;

// ── CREAR ──
if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombres    = trim($_POST['nombres']    ?? '');
    $apellidos  = trim($_POST['apellidos']  ?? '');
    $correo     = trim($_POST['correo']     ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

    if (!$nombres || !$apellidos || !$correo || !$contrasena) {
        $error = 'Todos los campos son obligatorios.';
        $accion = 'form_crear';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo no es válido.';
        $accion = 'form_crear';
    } else {
        $res = supabase('POST', TABLA, [
            'nombres'    => $nombres,
            'apellidos'  => $apellidos,
            'correo'     => $correo,
            'contrasena' => password_hash($contrasena, PASSWORD_DEFAULT),
        ]);
        if ($res['status'] === 201) {
            $mensaje = 'Usuario registrado correctamente.';
            $accion  = 'listar';
        } else {
            $error  = 'Error al registrar. ¿El correo ya existe?';
            $accion = 'form_crear';
        }
    }
}

// ── ACTUALIZAR ──
if ($accion === 'actualizar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = (int)$_POST['id'];
    $nombres   = trim($_POST['nombres']   ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $correo    = trim($_POST['correo']    ?? '');

    if (!$nombres || !$apellidos || !$correo) {
        $error = 'Nombre, apellidos y correo son obligatorios.';
        $accion = 'form_editar';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo no es válido.';
        $accion = 'form_editar';
    } else {
        $body = ['nombres' => $nombres, 'apellidos' => $apellidos, 'correo' => $correo];
        if (!empty($_POST['contrasena'])) {
            $body['contrasena'] = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
        }
        $res = supabase('PATCH', TABLA . '?id=eq.' . $id, $body);
        if ($res['status'] === 200) {
            $mensaje = 'Usuario actualizado correctamente.';
            $accion  = 'listar';
        } else {
            $error  = 'Error al actualizar.';
            $accion = 'form_editar';
        }
    }
}

// ── ELIMINAR ──
if ($accion === 'eliminar' && isset($_GET['id'])) {
    $id  = (int)$_GET['id'];
    $res = supabase('DELETE', TABLA . '?id=eq.' . $id);
    if ($res['status'] === 200) {
        $mensaje = 'Usuario eliminado correctamente.';
    } else {
        $error = 'Error al eliminar.';
    }
    $accion = 'listar';
}

// ── CARGAR USUARIO PARA EDITAR ──
if ($accion === 'form_editar' && isset($_GET['id']) && !$usuario) {
    $id  = (int)$_GET['id'];
    $res = supabase('GET', TABLA . '?id=eq.' . $id . '&select=*');
    if (!empty($res['data'][0])) {
        $usuario = $res['data'][0];
    } else {
        $error  = 'Usuario no encontrado.';
        $accion = 'listar';
    }
}

// ── LISTAR ──
$usuarios = [];
if ($accion === 'listar') {
    $res      = supabase('GET', TABLA . '?select=*&order=id.asc');
    $usuarios = $res['data'] ?? [];
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CRUD Usuarios – PHP</title>
    <link rel="stylesheet" href="/css/estilos-php.css" />
    <style>
      .nav-crud { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
      .nav-crud a {
        padding: 8px 16px; border-radius: 6px; text-decoration: none;
        font-size: .9em; border: 1px solid #ccc; color: #333; background: #f8f8f8;
        transition: background .2s;
      }
      .nav-crud a:hover, .nav-crud a.activo { background: #333; color: white; border-color: #333; }

      table { width: 100%; border-collapse: collapse; font-size: .88em; }
      th {
        text-align: left; padding: 8px 10px; font-size: .7em;
        text-transform: uppercase; letter-spacing: .1em;
        color: #888; border-bottom: 2px solid #eee;
      }
      td { padding: 10px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
      tr:last-child td { border-bottom: none; }
      .acciones { display: flex; gap: 8px; }
      .btn-editar, .btn-eliminar {
        padding: 4px 12px; border-radius: 4px; font-size: .8em;
        text-decoration: none; border: 1px solid; cursor: pointer;
        background: none; font-family: inherit;
      }
      .btn-editar   { color: #2980b9; border-color: #2980b9; }
      .btn-eliminar { color: #e74c3c; border-color: #e74c3c; }
      .btn-editar:hover   { background: #2980b9; color: white; }
      .btn-eliminar:hover { background: #e74c3c; color: white; }
      .vacio { text-align: center; padding: 30px; color: #aaa; font-size: .9em; }
    </style>
  </head>
  <body>
    <header>
      <a class="back-link" href="/">← Regresar</a>
      <h1>CRUD Usuarios – PHP</h1>
      <span></span>
    </header>

    <main>
      <div class="card">

        <nav class="nav-crud">
          <a href="?accion=listar"    class="<?= $accion === 'listar'    ? 'activo' : '' ?>">📋 Consultar</a>
          <a href="?accion=form_crear" class="<?= $accion === 'form_crear' ? 'activo' : '' ?>">➕ Registrar</a>
        </nav>

        <?php if ($mensaje): ?>
          <div class="resultado"><p><?= htmlspecialchars($mensaje) ?></p></div>
        <?php endif; ?>
        <?php if ($error): ?>
          <div class="error">⚠ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($accion === 'listar'): ?>
        <!-- ── TABLA DE USUARIOS ── -->
          <h2>Usuarios registrados</h2>
          <?php if (empty($usuarios)): ?>
            <p class="vacio">No hay usuarios registrados aún.</p>
          <?php else: ?>
            <table>
              <thead>
                <tr>
                  <th>ID</th><th>Nombres</th><th>Apellidos</th><th>Correo</th><th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($usuarios as $u): ?>
                  <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['nombres']) ?></td>
                    <td><?= htmlspecialchars($u['apellidos']) ?></td>
                    <td><?= htmlspecialchars($u['correo']) ?></td>
                    <td>
                      <div class="acciones">
                        <a href="?accion=form_editar&id=<?= $u['id'] ?>" class="btn-editar">Modificar</a>
                        <a href="?accion=eliminar&id=<?= $u['id'] ?>"
                           class="btn-eliminar"
                           onclick="return confirm('¿Eliminar a <?= htmlspecialchars($u['nombres']) ?>?')">
                          Eliminar
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>

        <?php elseif ($accion === 'form_crear'): ?>
        <!-- ── FORMULARIO CREAR ── -->
          <h2>Registrar usuario</h2>
          <form method="POST" action="?accion=crear">
            <div>
              <label for="nombres">Nombre(s)</label>
              <input type="text" id="nombres" name="nombres" placeholder="Ej. María"
                     value="<?= htmlspecialchars($_POST['nombres'] ?? '') ?>">
            </div>
            <div>
              <label for="apellidos">Apellido(s)</label>
              <input type="text" id="apellidos" name="apellidos" placeholder="Ej. García López"
                     value="<?= htmlspecialchars($_POST['apellidos'] ?? '') ?>">
            </div>
            <div>
              <label for="correo">Correo electrónico</label>
              <input type="email" id="correo" name="correo" placeholder="Ej. maria@correo.com"
                     value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
            </div>
            <div>
              <label for="contrasena">Contraseña</label>
              <input type="password" id="contrasena" name="contrasena" placeholder="Mínimo 6 caracteres">
            </div>
            <button type="submit">Registrar</button>
          </form>

        <?php elseif ($accion === 'form_editar' && $usuario): ?>
        <!-- ── FORMULARIO EDITAR ── -->
          <h2>Modificar usuario</h2>
          <form method="POST" action="?accion=actualizar">
            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
            <div>
              <label for="nombres">Nombre(s)</label>
              <input type="text" id="nombres" name="nombres"
                     value="<?= htmlspecialchars($usuario['nombres']) ?>">
            </div>
            <div>
              <label for="apellidos">Apellido(s)</label>
              <input type="text" id="apellidos" name="apellidos"
                     value="<?= htmlspecialchars($usuario['apellidos']) ?>">
            </div>
            <div>
              <label for="correo">Correo electrónico</label>
              <input type="email" id="correo" name="correo"
                     value="<?= htmlspecialchars($usuario['correo']) ?>">
            </div>
            <div>
              <label for="contrasena">Nueva contraseña <small style="color:#aaa">(dejar en blanco para no cambiar)</small></label>
              <input type="password" id="contrasena" name="contrasena" placeholder="Nueva contraseña">
            </div>
            <button type="submit">Guardar cambios</button>
          </form>

        <?php endif; ?>

      </div>
    </main>
  </body>
</html>
