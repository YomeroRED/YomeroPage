<?php
// ── Práctica 34: Cambio de divisas ──

$resultado = null;
$error     = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cantidad = $_POST["cantidad"] ?? "";
    $tasa     = $_POST["tasa"]     ?? "";

    if ($cantidad === "" || $tasa === "") {
        $error = "Por favor ingresa la cantidad y el tipo de cambio.";
    } elseif (!is_numeric($cantidad) || !is_numeric($tasa)) {
        $error = "Los valores deben ser numéricos.";
    } else {
        $resultado = "El resultado es " . number_format((float)$cantidad * (float)$tasa, 2);
    }
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cambio de Divisas – PHP</title>
    <link rel="stylesheet" href="/css/estilos-php.css" />
  </head>
  <body>
    <header>
      <a class="back-link" href="/">← Regresar</a>
      <h1>Cambio de Divisas – PHP</h1>
      <span></span>
    </header>

    <main>
      <div class="card">
        <h2>Cambio de divisas</h2>

        <form method="POST" action="">
          <div>
            <label for="cantidad">Cantidad</label>
            <input type="number" id="cantidad" name="cantidad" step="any"
                   placeholder="Ej. 100"
                   value="<?= htmlspecialchars($_POST['cantidad'] ?? '') ?>">
          </div>
          <div>
            <label for="tasa">Tipo de cambio</label>
            <input type="number" id="tasa" name="tasa" step="any"
                   placeholder="Ej. 0.85"
                   value="<?= htmlspecialchars($_POST['tasa'] ?? '') ?>">
          </div>
          <button type="submit">Calcular</button>
        </form>

        <?php if ($error): ?>
          <div class="error">⚠ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($resultado !== null): ?>
          <div class="resultado">
            <p><?= htmlspecialchars($resultado) ?></p>
          </div>
        <?php endif; ?>

      </div>
    </main>
  </body>
</html>
