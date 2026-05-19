<?php
// ── Práctica 26: Generador de Tablas de Multiplicar (N dinámico) – PHP ──

$n     = null;
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $raw = $_POST["numero"] ?? "";

    if ($raw === "") {
        $error = "Por favor ingresa un número.";
    } elseif (!ctype_digit($raw) || (int)$raw <= 0) {
        $error = "Por favor, ingresa un número entero positivo válido.";
    } else {
        $n = (int)$raw;
        if ($n > 50) {
            $error = "El número máximo permitido es 50.";
            $n = null;
        }
    }
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Generador de Tablas – PHP</title>
    <link rel="stylesheet" href="/css/estilos-php.css" />
    <style>
      .form-row { display: flex; gap: 10px; align-items: flex-end; }
      .form-row > div { flex: 1; }
      .grid-tablas {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-top: 20px;
      }
      .tabla-card {
        background: white; padding: 15px; border-radius: 8px;
        border: 1px solid #ddd; box-shadow: 0 2px 6px rgba(0,0,0,.07); text-align: center;
      }
      .tabla-card h3 { margin-top: 0; color: #2980b9; border-bottom: 2px solid #ecf0f1; padding-bottom: 10px; }
      .linea-tabla { margin: 5px 0; font-size: 14px; color: #34495e; }
    </style>
  </head>
  <body>
    <header>
      <a class="back-link" href="/practica27">← Regresar</a>
      <h1>Generador de Tablas – PHP</h1>
      <span></span>
    </header>

    <main>
      <div class="card">
        <h2>Tablas de Multiplicar Dinámicas</h2>
        <p style="color:#666; margin-bottom:16px;">
          Ingresa un número entero positivo (máx. 50) para generar las tablas del 1 hasta ese valor.
        </p>

        <form method="POST" action="">
          <div class="form-row">
            <div>
              <label for="numero">Número de tablas</label>
              <input type="number" id="numero" name="numero" min="1" max="50"
                     placeholder="Ej. 5"
                     value="<?= htmlspecialchars($_POST['numero'] ?? '') ?>">
            </div>
            <button type="submit" style="align-self:flex-end; margin-bottom:0;">Generar tablas</button>
          </div>
        </form>

        <?php if ($error): ?>
          <div class="error">⚠ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($n !== null): ?>
          <div class="grid-tablas">
            <?php for ($i = 1; $i <= $n; $i++): ?>
              <div class="tabla-card">
                <h3>Tabla del <?= $i ?></h3>
                <?php for ($j = 1; $j <= 10; $j++): ?>
                  <div class="linea-tabla"><?= $i ?> × <?= $j ?> = <strong><?= $i * $j ?></strong></div>
                <?php endfor; ?>
              </div>
            <?php endfor; ?>
          </div>
        <?php endif; ?>

        <p style="text-align:center; font-size:.7rem; color:#aaa; margin-top:16px; letter-spacing:.1em;">⚙ PROCESADO POR PHP</p>
      </div>
    </main>
  </body>
</html>
