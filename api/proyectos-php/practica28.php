<?php
// ── Práctica 28: Conversión de Celsius a Fahrenheit ──

$resultado = null;
$celsius   = null;
$error     = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = $_POST["celsius"] ?? "";

    if ($input === "") {
        $error = "Por favor ingresa una temperatura.";
    } elseif (!is_numeric($input)) {
        $error = "El valor debe ser numérico.";
    } else {
        $celsius    = (float)$input;
        $fahrenheit = $celsius * 9 / 5 + 32;
        $resultado  = $celsius . " Celsius = " . number_format($fahrenheit, 1) . " Fahrenheit";
    }
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Conversión Celsius a Fahrenheit – PHP</title>
    <link rel="stylesheet" href="/css/estilos-php.css" />
  </head>
  <body>
    <header>
      <a class="back-link" href="/">← Regresar</a>
      <h1>Celsius a Fahrenheit – PHP</h1>
      <span></span>
    </header>

    <main>
      <div class="card">
        <h2>Conversión de temperatura</h2>

        <form method="POST" action="">
          <div>
            <label for="celsius">Temperatura en Celsius</label>
            <input type="number" id="celsius" name="celsius" step="any"
                   placeholder="Ej. 25"
                   value="<?= htmlspecialchars($_POST['celsius'] ?? '') ?>">
          </div>
          <button type="submit">Convertir</button>
        </form>

        <?php if ($error): ?>
          <div class="error">⚠ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($resultado !== null): ?>
          <div class="resultado">
            <p><?= htmlspecialchars($resultado) ?></p>
          </div>
        <?php endif; ?>

        <p style="text-align:center; font-size:.7rem; color:#aaa; margin-top:16px; letter-spacing:.1em;">⚙ PROCESADO POR PHP</p>
      </div>
    </main>
  </body>
</html>
