<?php
// ── Práctica 35: Convertidor de tiempo ──

$resultado = null;
$error     = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = $_POST["segundos"] ?? "";

    if ($input === "") {
        $error = "Por favor ingresa el número de segundos.";
    } elseif (!is_numeric($input) || (int)$input != $input) {
        $error = "El valor debe ser un número entero.";
    } elseif ((int)$input < 0) {
        $error = "El valor debe ser mayor o igual a 0.";
    } else {
        $total    = (int)$input;
        $horas    = intdiv($total, 3600);
        $minutos  = intdiv($total % 3600, 60);
        $segundos = $total % 60;

        $resultado = "$total segundos corresponden a {$horas}h, {$minutos}m y {$segundos}s";
    }
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Convertidor de Tiempo – PHP</title>
    <link rel="stylesheet" href="/css/estilos-php.css" />
  </head>
  <body>
    <header>
      <a class="back-link" href="/">← Regresar</a>
      <h1>Convertidor de Tiempo – PHP</h1>
      <span></span>
    </header>

    <main>
      <div class="card">
        <h2>Convertidor de tiempo</h2>

        <form method="POST" action="">
          <div>
            <label for="segundos">Segundos</label>
            <input type="number" id="segundos" name="segundos" min="0"
                   placeholder="Ej. 3661"
                   value="<?= htmlspecialchars($_POST['segundos'] ?? '') ?>">
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

      </div>
    </main>
  </body>
</html>
