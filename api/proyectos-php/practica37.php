<?php
// ── Práctica 36 + 37: CRUD con PHP, Supabase y Sesiones (JWT en cookie) ──

// ─────────────────────────────────────────
// CONFIGURACIÓN
// ─────────────────────────────────────────
define('SUPABASE_URL', 'https://jelxabpuqiqenqfzlgfm.supabase.co');   
define('SUPABASE_KEY', 'sb_publishable_XXDLQi-xR1VnM_2BjpkFpw_Yjaze0fC');                  
define('TABLA', 'usuarios');
define('JWT_SECRET', 'frase_secreta_larga'); 
// ─────────────────────────────────────────

// ── Helper: Supabase REST API ──
function supabase(string $method, string $endpoint, array $body = null): array {
    $url = SUPABASE_URL . '/rest/v1/' . $endpoint;
    $ch  = curl_init($url);
    $headers = [
        'Content-Type: application/json',
        'apikey: '         . SUPABASE_KEY,
        'Authorization: Bearer ' . SUPABASE_KEY,
        'Prefer: return=representation',
    ];
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER,     $headers);
    if ($body !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    $response = curl_exec($ch);
    $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['status' => $status, 'data' => json_decode($response, true)];
}

// ── JWT mínimo (HS256) ──
function jwt_crear(array $payload): string {
    $header  = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $payload = base64_encode(json_encode($payload));
    $firma   = base64_encode(hash_hmac('sha256', "$header.$payload", JWT_SECRET, true));
    return "$header.$payload.$firma";
}

function jwt_verificar(string $token): ?array {
    $partes = explode('.', $token);
    if (count($partes) !== 3) return null;
    [$header, $payload, $firma] = $partes;
    $firma_esperada = base64_encode(hash_hmac('sha256', "$header.$payload", JWT_SECRET, true));
    if (!hash_equals($firma_esperada, $firma)) return null;
    $data = json_decode(base64_decode($payload), true);
    if (isset($data['exp']) && $data['exp'] < time()) return null;
    return $data;
}

// ── Leer sesión desde cookie ──
$sesion = null;
if (!empty($_COOKIE['auth_token'])) {
    $sesion = jwt_verificar($_COOKIE['auth_token']);
}

$accion  = $_POST['accion'] ?? $_GET['accion'] ?? ($sesion ? 'listar' : 'login');
$mensaje = '';
$error   = '';
$usuario = null;

// ── LOGIN ──
if ($accion === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo     = trim($_POST['correo']     ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

    if (!$correo || !$contrasena) {
        $error  = 'Ingresa correo y contraseña.';
        $accion = 'login';
    } else {
        $res = supabase('GET', TABLA . '?correo=eq.' . urlencode($correo) . '&select=*');
        $u   = $res['data'][0] ?? null;

        if ($u && password_verify($contrasena, $u['contrasena'])) {
            $token = jwt_crear([
                'id'       => $u['id'],
                'nombres'  => $u['nombres'],
                'correo'   => $u['correo'],
                'exp'      => time() + 3600, // expira en 1 hora
            ]);
            setcookie('auth_token', $token, time() + 3600, '/', '', false, true);
            $sesion  = jwt_verificar($token);
            $accion  = 'listar';
            $mensaje = '¡Bienvenido, ' . htmlspecialchars($u['nombres']) . '!';
        } else {
            $error  = 'Correo o contraseña incorrectos.';
            $accion = 'login';
        }
    }
}

// ── LOGOUT ──
if ($accion === 'logout') {
    setcookie('auth_token', '', time() - 3600, '/');
    $sesion = null;
    $accion = 'login';
    $mensaje = 'Sesión cerrada correctamente.';
}

// ── Proteger acciones que requieren sesión ──
$requiere_sesion = ['listar', 'form_crear', 'crear', 'form_editar', 'actualizar', 'eliminar'];
if (in_array($accion, $requiere_sesion) && !$sesion) {
    $accion = 'login';
    $error  = 'Debes iniciar sesión para acceder.';
}

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
    $mensaje = $res['status'] === 200 ? 'Usuario eliminado correctamente.' : 'Error al eliminar.';
    $accion  = 'listar';
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
    <title>CRUD + Sesiones – PHP</title>
    <link rel="stylesheet" href="/css/estilos-php.css" />
    <style>
      .nav-crud { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; align-items: center; }
      .nav-crud a, .nav-crud span {
        padding: 8px 16px; border-radius: 6px; text-decoration: none;
        font-size: .9em; border: 1px solid #ccc; color: #333; background: #f8f8f8;
        transition: background .2s;
      }
      .nav-crud a:hover, .nav-crud a.activo { background: #333; color: white; border-color: #333; }
      .nav-crud .logout { margin-left: auto; border-color: #e74c3c; color: #e74c3c; }
      .nav-crud .logout:hover { background: #e74c3c; color: white; }
      .nav-crud .usuario-info { border: none; background: none; font-size: .85em; color: #888; padding-left: 0; }

      .login-wrap { max-width: 400px; margin: 0 auto; }
      .login-wrap h2 { text-align: center; margin-bottom: 20px; }
      .divider { text-align: center; margin: 20px 0; color: #aaa; font-size: .85em; }
      .link-registro { text-align: center; margin-top: 10px; font-size: .9em; }
      .link-registro a { color: #2980b9; text-decoration: none; }

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

      .sesion-badge {
        display: inline-block; background: #eafaf1; border: 1px solid #27ae60;
        color: #27ae60; border-radius: 20px; padding: 4px 14px;
        font-size: .78em; margin-bottom: 16px;
      }
    </style>
  </head>
  <body>
    <header>
      <a class="back-link" href="/">← Regresar</a>
      <h1>CRUD + Sesiones – PHP</h1>
      <span></span>
    </header>

    <main>
      <div class="card">

        <?php if ($mensaje): ?>
          <div class="resultado"><p><?= htmlspecialchars($mensaje) ?></p></div>
        <?php endif; ?>
        <?php if ($error): ?>
          <div class="error">⚠ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($sesion): ?>
          <!-- ── NAV (solo visible con sesión) ── -->
          <div class="sesion-badge">🔒 Sesión activa</div>
          <nav class="nav-crud">
            <a href="?accion=listar"     class="<?= $accion === 'listar'     ? 'activo' : '' ?>">📋 Consultar</a>
            <a href="?accion=form_crear" class="<?= $accion === 'form_crear' ? 'activo' : '' ?>">➕ Registrar</a>
            <span class="usuario-info">👤 <?= htmlspecialchars($sesion['nombres']) ?></span>
            <a href="?accion=logout" class="logout">Cerrar sesión</a>
          </nav>
        <?php endif; ?>

        <?php if ($accion === 'login' || $accion === 'form_registro'): ?>
          <!-- ── LOGIN / REGISTRO (sin sesión) ── -->
          <div class="login-wrap">

            <?php if ($accion === 'login'): ?>
              <h2>Iniciar sesión</h2>
              <form method="POST" action="?accion=login">
                <div>
                  <label for="correo">Correo electrónico</label>
                  <input type="email" id="correo" name="correo" placeholder="tu@correo.com"
                         value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
                </div>
                <div>
                  <label for="contrasena">Contraseña</label>
                  <input type="password" id="contrasena" name="contrasena" placeholder="Tu contraseña">
                </div>
                <button type="submit">Entrar</button>
              </form>
              <div class="link-registro">
                ¿No tienes cuenta? <a href="?accion=form_registro">Regístrate aquí</a>
              </div>

            <?php elseif ($accion === 'form_registro'): ?>
              <h2>Crear cuenta</h2>
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
                  <input type="email" id="correo" name="correo" placeholder="tu@correo.com"
                         value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
                </div>
                <div>
                  <label for="contrasena">Contraseña</label>
                  <input type="password" id="contrasena" name="contrasena" placeholder="Mínimo 6 caracteres">
                </div>
                <button type="submit">Registrarse</button>
              </form>
              <div class="link-registro">
                ¿Ya tienes cuenta? <a href="?accion=login">Inicia sesión</a>
              </div>
            <?php endif; ?>

          </div>

        <?php elseif ($accion === 'listar'): ?>
          <!-- ── TABLA (solo con sesión) ── -->
          <h2>Usuarios registrados</h2>
          <?php if (empty($usuarios)): ?>
            <p class="vacio">No hay usuarios registrados aún.</p>
          <?php else: ?>
            <table>
              <thead>
                <tr><th>ID</th><th>Nombres</th><th>Apellidos</th><th>Correo</th><th>Acciones</th></tr>
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
          <!-- ── FORMULARIO CREAR (solo con sesión) ── -->
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
          <!-- ── FORMULARIO EDITAR (solo con sesión) ── -->
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
