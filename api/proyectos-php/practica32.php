<?php
// ── Práctica 32: Puntuación a calificación con letra ──

$letra     = null;
$error     = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = $_POST["puntuacion"] ?? "";

    if ($input === "") {
        $error = "Por favor ingresa una puntuación.";
    } elseif (!is_numeric($input)) {
        $error = "La puntuación debe ser un número.";
    } else {
        $puntuacion = (int)$input;

        if ($puntuacion < 0 || $puntuacion > 100) {
            $error = "La puntuación debe estar entre 0 y 100.";
        } elseif ($puntuacion >= 90) {
            $letra = "A";
        } elseif ($puntuacion >= 80) {
            $letra = "B";
        } elseif ($puntuacion >= 70) {
            $letra = "C";
        } elseif ($puntuacion >= 60) {
            $letra = "D";
        } else {
            $letra = "F";
        }
    }
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Calificación con Letra – PHP</title>
    <link rel="stylesheet" href="/css/estilos-php.css" />
  </head>
  <body>
    <header>
      <a class="back-link" href="/">← Regresar</a>
      <h1>Calificación con Letra – PHP</h1>
      <span></span>
    </header>

    <main>
      <div class="card">
        <h2>Conversión de puntuación a letra</h2>

        <form method="POST" action="">
          <div>
            <label for="puntuacion">Puntuación (0 – 100)</label>
            <input type="number" id="puntuacion" name="puntuacion" min="0" max="100"
                   placeholder="Ej. 85"
                   value="<?= htmlspecialchars($_POST['puntuacion'] ?? '') ?>">
          </div>
          <button type="submit">Convertir</button>
        </form>

        <?php if ($error): ?>
          <div class="error">⚠ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($letra !== null): ?>
          <div class="resultado">
            <p><?= $letra ?></p>
          </div>
        <?php endif; ?>

      </div>
    </main>
  </body>
</html>
