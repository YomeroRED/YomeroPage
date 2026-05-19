<?php
// ── Práctica 23: Calculadora de IMC (Mujeres) – PHP ──

$categorias = [
    ["min" => 0,    "max" => 18.5, "grado" => "Bajo peso",    "color" => "#3498db", "riesgo" => "Riesgo moderado",   "emoji" => "🔵"],
    ["min" => 18.5, "max" => 25,   "grado" => "Peso normal",  "color" => "#27ae60", "riesgo" => "Riesgo bajo ✓",     "emoji" => "🟢"],
    ["min" => 25,   "max" => 30,   "grado" => "Sobrepeso I",  "color" => "#f39c12", "riesgo" => "Riesgo aumentado",  "emoji" => "🟡"],
    ["min" => 30,   "max" => 35,   "grado" => "Sobrepeso II", "color" => "#e67e22", "riesgo" => "Riesgo alto",       "emoji" => "🟠"],
    ["min" => 35,   "max" => 40,   "grado" => "Obesidad I",   "color" => "#e74c3c", "riesgo" => "Riesgo muy alto",   "emoji" => "🔴"],
    ["min" => 40,   "max" => 50,   "grado" => "Obesidad II",  "color" => "#c0392b", "riesgo" => "Riesgo severo",     "emoji" => "🔴"],
    ["min" => 50,   "max" => 9999, "grado" => "Obesidad III", "color" => "#922b21", "riesgo" => "Riesgo muy severo", "emoji" => "⛔"],
];

$imc = $categoria = null;
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $peso     = $_POST["peso"]     ?? "";
    $estatura = $_POST["estatura"] ?? "";

    if ($peso === "" || $estatura === "") {
        $error = "Por favor ingresa el peso y la estatura.";
    } elseif (!is_numeric($peso) || !is_numeric($estatura)) {
        $error = "Los valores deben ser numéricos.";
    } else {
        $peso = (float)$peso; $estatura = (float)$estatura;
        if ($peso <= 0 || $estatura <= 0) {
            $error = "Los valores deben ser mayores a 0.";
        } elseif ($estatura > 2.5) {
            $error = "Verifica la estatura. Ingrésala en metros (ej. 1.65).";
        } else {
            $imc = round($peso / ($estatura * $estatura), 2);
            foreach ($categorias as $cat) {
                if ($imc >= $cat["min"] && $imc < $cat["max"]) {
                    $categoria = $cat;
                    break;
                }
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
    <title>IMC – PHP</title>
    <link rel="stylesheet" href="/css/estilos-php.css" />
    <style>
      .campos { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
      .imc-principal {
        border-radius: 12px; border: 2px solid;
        padding: 20px; text-align: center; margin-bottom: 16px;
      }
      .imc-emoji { font-size: 2.2rem; margin-bottom: 6px; }
      .imc-num   { font-size: 3rem; font-weight: 700; line-height: 1; }
      .imc-unidad { font-size: .78rem; letter-spacing: .1em; margin-top: 4px; opacity: .7; }
      .imc-grado  { font-size: 1.05rem; font-weight: 700; margin-top: 8px; }
      .imc-riesgo { font-size: .78rem; text-transform: uppercase; margin-top: 4px; opacity: .75; }
      .tabla-ref { width: 100%; border-collapse: collapse; font-size: .78rem; margin-top: 8px; }
      .tabla-ref th {
        padding: 6px 8px; text-align: left; font-size: .65rem;
        letter-spacing: .12em; text-transform: uppercase; color: #888;
        border-bottom: 1px solid #eee;
      }
      .tabla-ref td { padding: 5px 8px; border-bottom: 1px solid #f0f0f0; }
      .tabla-ref tr.activa td { font-weight: 700; }
      .dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; margin-right: 6px; vertical-align: middle; }
    </style>
  </head>
  <body>
    <header>
      <a class="back-link" href="/practica27">← Regresar</a>
      <h1>Calculadora IMC – PHP</h1>
      <span></span>
    </header>

    <main>
      <div class="card">
        <h2>Índice de Masa Corporal — Mujeres adultas</h2>

        <form method="POST" action="">
          <div class="campos">
            <div>
              <label for="peso">Peso (kg)</label>
              <input type="number" id="peso" name="peso" step="0.1" placeholder="65" min="1"
                     value="<?= htmlspecialchars($_POST['peso'] ?? '') ?>">
            </div>
            <div>
              <label for="estatura">Estatura (m)</label>
              <input type="number" id="estatura" name="estatura" step="0.01" placeholder="1.65" min="0.5" max="2.5"
                     value="<?= htmlspecialchars($_POST['estatura'] ?? '') ?>">
            </div>
          </div>
          <button type="submit">Calcular IMC</button>
        </form>

        <?php if ($error): ?>
          <div class="error">⚠ <?= htmlspecialchars($error) ?></div>
        <?php elseif ($imc !== null && $categoria): ?>
          <div class="imc-principal"
               style="background:<?= $categoria['color'] ?>15; border-color:<?= $categoria['color'] ?>55; color:<?= $categoria['color'] ?>">
            <div class="imc-emoji"><?= $categoria['emoji'] ?></div>
            <div class="imc-num"><?= $imc ?></div>
            <div class="imc-unidad">kg / m²</div>
            <div class="imc-grado"><?= htmlspecialchars($categoria['grado']) ?></div>
            <div class="imc-riesgo"><?= htmlspecialchars($categoria['riesgo']) ?></div>
          </div>

          <table class="tabla-ref">
            <thead>
              <tr><th>Categoría</th><th>Rango IMC</th><th>Riesgo</th></tr>
            </thead>
            <tbody>
              <?php foreach ($categorias as $cat): ?>
                <tr class="<?= ($cat['grado'] === $categoria['grado']) ? 'activa' : '' ?>">
                  <td><span class="dot" style="background:<?= $cat['color'] ?>"></span><?= htmlspecialchars($cat['grado']) ?></td>
                  <td><?= $cat['min'] ?> – <?= $cat['max'] < 9999 ? $cat['max'] : '∞' ?></td>
                  <td><?= htmlspecialchars($cat['riesgo']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>

        <p style="text-align:center; font-size:.7rem; color:#aaa; margin-top:16px; letter-spacing:.1em;">⚙ PROCESADO POR PHP</p>
      </div>
    </main>
  </body>
</html>
