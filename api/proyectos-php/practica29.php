<?php
// ── Práctica 29: Formulario de números pares e impares ──

$resultado = null;
$numero    = null;
$error     = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = $_POST["numero"] ?? "";

    if ($input === "") {
        $error = "Por favor ingresa un número.";
    } elseif (!is_numeric($input)) {
        $error = "El valor debe ser numérico.";
    } else {
        $numero = (int)$input;
        $resultado = $numero % 2 === 0 ? "$numero es par." : "$numero es impar.";
    }
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pares e impares</title>
    <link rel="stylesheet" href="/css/estilos-php.css" />
  </head>
  <body>
    <header>
      <a class="back-link" href="/">← Regresar</a>
      <h1>Pares e impares</h1>
      <span></span>
    </header>

    <main>
      <div class="card">
        <h2>Formulario de números pares e impares</h2>

        <form method="POST" action="">
          <div>
            <label for="numero">Número</label>
            <input type="number" id="numero" name="numero" step="any"
                   placeholder="Ej. 25"
                   value="<?= htmlspecialchars($_POST['numero'] ?? '') ?>">
          </div>
          <button type="submit">Verificar</button>
        </form>

        <?php if ($error): ?>
          <div class="error">⚠ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($resultado !== null): ?>
          <div class="resultado">
            <p><?= htmlspecialchars($resultado) ?></p>
          </div>
        <?php endif; ?>

        <p style="text-align:center; font-size:.7rem; color:#aaa; margin-top:16px; letter-spacing:.1em;">Yomero estuvo aqui</p>
      </div>
    </main>
  </body>
</html>
          