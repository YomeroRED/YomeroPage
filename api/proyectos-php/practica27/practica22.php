<?php
// ── Práctica 22: Fórmula General (Ecuación Cuadrática) – PHP ──

$x1 = $x2 = $discriminante = null;
$error = "";
$calculado = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $a = $_POST["val_a"] ?? "";
    $b = $_POST["val_b"] ?? "";
    $c = $_POST["val_c"] ?? "";

    if ($a === "" || $b === "" || $c === "") {
        $error = "Por favor ingresa los tres coeficientes (a, b y c).";
    } elseif (!is_numeric($a) || !is_numeric($b) || !is_numeric($c)) {
        $error = "Los tres valores deben ser numéricos.";
    } else {
        $a = (float)$a; $b = (float)$b; $c = (float)$c;

        if ($a == 0) {
            $error = "El valor de 'a' no puede ser 0.";
        } else {
            $discriminante = ($b * $b) - (4 * $a * $c);
            $calculado = true;
            if ($discriminante >= 0) {
                $raiz = sqrt($discriminante);
                $x1 = round((-$b + $raiz) / (2 * $a), 4);
                $x2 = round((-$b - $raiz) / (2 * $a), 4);
            }
        }
    }
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fórmula General – PHP</title>
    <link rel="stylesheet" href="/css/estilos-php.css" />
    <style>
      .formula {
        background: #f4f7fb;
        border: 1px solid #dde3ec;
        border-radius: 8px;
        padding: 12px;
        text-align: center;
        font-size: 1rem;
        margin-bottom: 20px;
        letter-spacing: .05em;
      }
      .campos { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
      .fila-res { display: flex; gap: 16px; justify-content: center; margin-top: 8px; }
      .res-box {
        flex: 1; max-width: 140px; border-radius: 10px;
        border: 1.5px solid; padding: 14px; text-align: center;
      }
      .res-box.x1 { border-color: #2980b9; background: #eef6fb; color: #2980b9; }
      .res-box.x2 { border-color: #e74c3c; background: #fef0ef; color: #e74c3c; }
      .res-label { font-size: .72rem; text-transform: uppercase; letter-spacing: .12em; opacity: .7; }
      .res-valor { font-size: 1.6rem; font-weight: 700; margin-top: 4px; }
      .no-reales {
        background: #fdf3e3; border: 1px solid #f8c98d;
        border-radius: 8px; padding: 14px; color: #9a6700; font-size: .88rem;
        line-height: 1.5;
      }
      .discriminante { text-align: center; font-size: .78rem; color: #888; margin-top: 10px; }
    </style>
  </head>
  <body>
    <header>
      <a class="back-link" href="/practica27">← Regresar</a>
      <h1>Fórmula General – PHP</h1>
      <span></span>
    </header>

    <main>
      <div class="card">
        <h2>Ecuación Cuadrática</h2>
        <div class="formula">x = (−b ± √(b² − 4ac)) / 2a</div>

        <form method="POST" action="">
          <div class="campos">
            <div>
              <label for="val_a">a</label>
              <input type="number" id="val_a" name="val_a" step="any" placeholder="1"
                     value="<?= htmlspecialchars($_POST['val_a'] ?? '') ?>">
            </div>
            <div>
              <label for="val_b">b</label>
              <input type="number" id="val_b" name="val_b" step="any" placeholder="−5"
                     value="<?= htmlspecialchars($_POST['val_b'] ?? '') ?>">
            </div>
            <div>
              <label for="val_c">c</label>
              <input type="number" id="val_c" name="val_c" step="any" placeholder="6"
                     value="<?= htmlspecialchars($_POST['val_c'] ?? '') ?>">
            </div>
          </div>
          <button type="submit">Calcular x₁ y x₂</button>
        </form>

        <?php if ($error): ?>
          <div class="error">⚠ <?= htmlspecialchars($error) ?></div>
        <?php elseif ($calculado): ?>
          <?php if ($discriminante < 0): ?>
            <div class="no-reales">
              ⚠ Discriminante = <strong><?= $discriminante ?></strong><br>
              Como es negativo, la ecuación no tiene soluciones reales (raíces complejas).
            </div>
          <?php else: ?>
            <div class="resultado">
              <div class="fila-res">
                <div class="res-box x1">
                  <div class="res-label">x₁</div>
                  <div class="res-valor"><?= $x1 ?></div>
                </div>
                <div class="res-box x2">
                  <div class="res-label">x₂</div>
                  <div class="res-valor"><?= $x2 ?></div>
                </div>
              </div>
              <p class="discriminante">Discriminante: <?= $discriminante ?></p>
            </div>
          <?php endif; ?>
        <?php endif; ?>

        <p style="text-align:center; font-size:.7rem; color:#aaa; margin-top:16px; letter-spacing:.1em;">⚙ PROCESADO POR PHP</p>
      </div>
    </main>
  </body>
</html>
