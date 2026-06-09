<?php
// ── Práctica 31: Verificador de edad para votar ──

$resultado = null;
$puede     = null;
$error     = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $edad   = $_POST["edad"] ?? "";

    if ($nombre === "" || $edad === "") {
        $error = "Por favor ingresa el nombre y la edad.";
    } elseif (!is_numeric($edad) || (int)$edad != $edad) {
        $error = "La edad debe ser un número entero.";
    } elseif ((int)$edad < 0 || (int)$edad > 120) {
        $error = "Ingresa una edad válida.";
    } else {
        $edad   = (int)$edad;
        $puede  = $edad >= 18;
        $resultado = $puede
            ? "$nombre puede votar."
            : "$nombre no puede votar.";
    }
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>¿Puede votar? – PHP</title>
    <link rel="stylesheet" href="/css/estilos-php.css" />
  </head>
  <body>
    <header>
      <a class="back-link" href="/">← Regresar</a>
      <h1>¿Puede votar? – PHP</h1>
      <span></span>
    </header>

    <main>
      <div class="card">
        <h2>Verificador de edad para votar</h2>

        <form method="POST" action="">
          <div>
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre"
                   placeholder="Ej. María"
                   value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
          </div>
          <div>
            <label for="edad">Edad</label>
            <input type="number" id="edad" name="edad" min="0" max="120"
                   placeholder="Ej. 25"
                   value="<?= htmlspecialchars($_POST['edad'] ?? '') ?>">
          </div>
          <button type="submit">Verificar</button>
        </form>

        <?php if ($error): ?>
          <div class="error">⚠ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($resultado !== null): ?>
          <div class="resultado" style="border-left-color: <?= $puede ? '#27ae60' : '#e74c3c' ?>">
            <p><?= htmlspecialchars($resultado) ?></p>
          </div>
        <?php endif; ?>

        <p style="text-align:center; font-size:.7rem; color:#aaa; margin-top:16px; letter-spacing:.1em;">⚙ PROCESADO POR PHP</p>
      </div>
    </main>
  </body>
</html>
