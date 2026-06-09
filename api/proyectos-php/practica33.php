<?php
// ── Práctica 33: Verificación de anagramas ──

$resultado = null;
$es_anagrama = null;
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $palabra1 = trim(strtolower($_POST["palabra1"] ?? ""));
    $palabra2 = trim(strtolower($_POST["palabra2"] ?? ""));

    if ($palabra1 === "" || $palabra2 === "") {
        $error = "Por favor ingresa las dos palabras.";
    } elseif (!ctype_alpha($palabra1) || !ctype_alpha($palabra2)) {
        $error = "Las palabras solo deben contener letras.";
    } else {
        $letras1 = str_split($palabra1);
        $letras2 = str_split($palabra2);
        sort($letras1);
        sort($letras2);

        $es_anagrama = ($letras1 === $letras2);
        $resultado   = $es_anagrama ? "Sí" : "No";
    }
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verificación de Anagramas – PHP</title>
    <link rel="stylesheet" href="/css/estilos-php.css" />
  </head>
  <body>
    <header>
      <a class="back-link" href="/">← Regresar</a>
      <h1>Verificación de Anagramas – PHP</h1>
      <span></span>
    </header>

    <main>
      <div class="card">
        <h2>¿Son anagramas?</h2>

        <form method="POST" action="">
          <div>
            <label for="palabra1">Primera palabra</label>
            <input type="text" id="palabra1" name="palabra1"
                   placeholder="Ej. listen"
                   value="<?= htmlspecialchars($_POST['palabra1'] ?? '') ?>">
          </div>
          <div>
            <label for="palabra2">Segunda palabra</label>
            <input type="text" id="palabra2" name="palabra2"
                   placeholder="Ej. silent"
                   value="<?= htmlspecialchars($_POST['palabra2'] ?? '') ?>">
          </div>
          <button type="submit">Verificar</button>
        </form>

        <?php if ($error): ?>
          <div class="error">⚠ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($resultado !== null): ?>
          <div class="resultado" style="border-left-color: <?= $es_anagrama ? '#27ae60' : '#e74c3c' ?>">
            <p><?= $resultado ?></p>
          </div>
        <?php endif; ?>

      </div>
    </main>
  </body>
</html>
