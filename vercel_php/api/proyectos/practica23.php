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
        $peso     = (float)$peso;
        $estatura = (float)$estatura;

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
    <title>IMC – Índice de Masa Corporal – PHP</title>
    <style>
      :root{
        --bg:#eef2f7; --card:#fff; --dark:#1c1c2e;
        --muted:#8892a4; --border:#dde3ec;
        --shadow:0 10px 40px rgba(28,28,46,.1);
      }
      *{margin:0;padding:0;box-sizing:border-box;}
      body{
        background:var(--bg); font-family:"Courier New",Courier,monospace;
        min-height:100vh; display:flex; align-items:center;
        justify-content:center; padding:40px 16px;
      }
      body::before{
        content:""; position:fixed; inset:0;
        background-image:radial-gradient(circle,#c5cfe0 1px,transparent 1px);
        background-size:28px 28px; opacity:.5; pointer-events:none;
      }
      .card{
        position:relative; background:var(--card);
        border:1px solid var(--border); border-radius:16px;
        box-shadow:var(--shadow); width:100%; max-width:460px;
        padding:44px 40px 36px; animation:subir .5s ease both;
      }
      @keyframes subir{
        from{opacity:0;transform:translateY(28px);}
        to{opacity:1;transform:translateY(0);}
      }
      .card::before{
        content:""; position:absolute; top:0; left:0; right:0; height:5px;
        background:linear-gradient(90deg,#e91e8c,#2980b9);
        border-radius:16px 16px 0 0;
      }
      .encabezado{text-align:center; margin-bottom:28px;}
      .encabezado small{
        display:block; font-size:.65rem; letter-spacing:.2em;
        text-transform:uppercase; color:var(--muted); margin-bottom:10px;
      }
      .encabezado h1{
        font-family:Georgia,"Times New Roman",serif;
        font-size:1.75rem; color:var(--dark);
      }
      .encabezado .subtitulo{
        font-size:.78rem; color:var(--muted); margin-top:6px;
      }
      .campos{display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:20px;}
      .grupo label{
        display:block; font-size:.72rem; letter-spacing:.12em;
        text-transform:uppercase; color:var(--muted); margin-bottom:5px;
      }
      .grupo input{
        width:100%; padding:10px 12px; border:1.5px solid var(--border);
        border-radius:8px; font-family:inherit; font-size:1rem;
        background:#f8fafc; color:var(--dark); outline:none;
        transition:border-color .2s;
      }
      .grupo input:focus{border-color:#e91e8c;}
      .btn{
        display:block; width:100%; padding:13px; border:none;
        border-radius:10px;
        background:linear-gradient(90deg,#e91e8c,#2980b9);
        color:white; font-size:1rem; font-weight:700;
        cursor:pointer; letter-spacing:.06em; transition:opacity .2s;
      }
      .btn:hover{opacity:.88;}
      .sep{border:none; border-top:1px solid var(--border); margin:20px 0;}
      .imc-principal{
        border-radius:12px; border:2px solid; padding:20px;
        text-align:center; margin-bottom:16px; transition:all .3s;
      }
      .imc-emoji{font-size:2.2rem; margin-bottom:6px;}
      .imc-num{font-size:3rem; font-weight:700; line-height:1;}
      .imc-unidad{font-size:.78rem; letter-spacing:.1em; margin-top:4px; opacity:.7;}
      .imc-grado{
        font-size:1.05rem; font-weight:700; margin-top:8px;
        letter-spacing:.04em;
      }
      .imc-riesgo{
        font-size:.78rem; letter-spacing:.08em;
        text-transform:uppercase; margin-top:4px; opacity:.75;
      }
      .tabla-ref{width:100%; border-collapse:collapse; font-size:.78rem; margin-top:8px;}
      .tabla-ref th{
        padding:6px 8px; text-align:left; font-size:.65rem;
        letter-spacing:.12em; text-transform:uppercase; color:var(--muted);
        border-bottom:1px solid var(--border);
      }
      .tabla-ref td{padding:5px 8px; border-bottom:1px solid #f0f0f0;}
      .tabla-ref tr.activa td{font-weight:700;}
      .dot{
        display:inline-block; width:10px; height:10px;
        border-radius:50%; margin-right:6px; vertical-align:middle;
      }
      .error-box{
        background:#fff0ee; border:1px solid #f5c6c0;
        border-radius:8px; padding:12px 14px;
        color:#c0392b; font-size:.85rem;
      }
      .server-badge{
        text-align:center; font-size:.65rem; letter-spacing:.12em;
        text-transform:uppercase; color:var(--muted); margin-top:16px;
      }
    </style>
  </head>
  <body>
    <div class="card">
      <div class="encabezado">
        <small>Práctica 23 · PHP</small>
        <h1>Calculadora IMC</h1>
        <p class="subtitulo">Índice de Masa Corporal — Mujeres adultas</p>
      </div>

      <form method="POST" action="">
        <div class="campos">
          <div class="grupo">
            <label for="peso">Peso (kg)</label>
            <input type="number" id="peso" name="peso" step="0.1"
                   placeholder="65" min="1"
                   value="<?= htmlspecialchars($_POST['peso'] ?? '') ?>">
          </div>
          <div class="grupo">
            <label for="estatura">Estatura (m)</label>
            <input type="number" id="estatura" name="estatura" step="0.01"
                   placeholder="1.65" min="0.5" max="2.5"
                   value="<?= htmlspecialchars($_POST['estatura'] ?? '') ?>">
          </div>
        </div>
        <button type="submit" class="btn">Calcular IMC</button>
      </form>

      <?php if ($error): ?>
        <hr class="sep">
        <div class="error-box">⚠ <?= htmlspecialchars($error) ?></div>

      <?php elseif ($imc !== null && $categoria): ?>
        <hr class="sep">

        <!-- Caja principal de resultado -->
        <div class="imc-principal"
             style="background:<?= $categoria['color'] ?>15;
                    border-color:<?= $categoria['color'] ?>55;
                    color:<?= $categoria['color'] ?>">
          <div class="imc-emoji"><?= $categoria['emoji'] ?></div>
          <div class="imc-num"><?= $imc ?></div>
          <div class="imc-unidad">kg / m²</div>
          <div class="imc-grado"><?= htmlspecialchars($categoria['grado']) ?></div>
          <div class="imc-riesgo"><?= htmlspecialchars($categoria['riesgo']) ?></div>
        </div>

        <!-- Tabla de referencia -->
        <table class="tabla-ref">
          <thead>
            <tr>
              <th>Categoría</th>
              <th>Rango IMC</th>
              <th>Riesgo</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($categorias as $cat): ?>
              <tr class="<?= ($cat['grado'] === $categoria['grado']) ? 'activa' : '' ?>">
                <td>
                  <span class="dot" style="background:<?= $cat['color'] ?>"></span>
                  <?= htmlspecialchars($cat['grado']) ?>
                </td>
                <td><?= $cat['min'] ?> – <?= $cat['max'] < 9999 ? $cat['max'] : '∞' ?></td>
                <td><?= htmlspecialchars($cat['riesgo']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>

      <p class="server-badge">⚙ procesado por PHP</p>
    </div>
  </body>
</html>
