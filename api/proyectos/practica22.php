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
        $a = (float)$a;
        $b = (float)$b;
        $c = (float)$c;

        if ($a == 0) {
            $error = "El valor de 'a' no puede ser 0. Si a=0, no es una ecuación cuadrática.";
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
    <style>
      :root {
        --bg:#eef2f7; --card:#fff; --dark:#1c1c2e;
        --muted:#8892a4; --border:#dde3ec;
        --x1:#e74c3c; --x2:#2980b9;
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
        background:linear-gradient(90deg,var(--x1),var(--x2));
        border-radius:16px 16px 0 0;
      }
      .encabezado{text-align:center; margin-bottom:24px;}
      .encabezado small{
        display:block; font-size:.65rem; letter-spacing:.2em;
        text-transform:uppercase; color:var(--muted); margin-bottom:10px;
      }
      .encabezado h1{
        font-family:Georgia,"Times New Roman",serif;
        font-size:1.75rem; color:var(--dark);
      }
      .formula{
        background:#f4f7fb; border:1px solid var(--border);
        border-radius:8px; padding:12px; text-align:center;
        font-size:1rem; color:var(--dark); margin-bottom:24px;
        letter-spacing:.05em;
      }
      .campos{display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:20px;}
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
      .grupo input:focus{border-color:var(--x2);}
      .btn{
        display:block; width:100%; padding:13px; border:none;
        border-radius:10px;
        background:linear-gradient(90deg,var(--x1),var(--x2));
        color:white; font-size:1rem; font-weight:700;
        cursor:pointer; letter-spacing:.06em; transition:opacity .2s;
      }
      .btn:hover{opacity:.88;}
      .sep{border:none; border-top:1px solid var(--border); margin:20px 0;}
      .res-titulo{
        font-size:.7rem; letter-spacing:.18em; text-transform:uppercase;
        color:var(--muted); text-align:center; margin-bottom:14px;
      }
      .fila-res{display:flex; gap:16px; justify-content:center;}
      .res-box{
        flex:1; max-width:140px; border-radius:10px;
        border:1.5px solid; padding:14px; text-align:center;
      }
      .res-box.x1{border-color:#2980b9; background:#eef6fb;}
      .res-box.x2{border-color:#e74c3c; background:#fef0ef;}
      .res-label{font-size:.72rem; text-transform:uppercase; letter-spacing:.12em; color:var(--muted);}
      .res-valor{font-size:1.6rem; font-weight:700; margin-top:4px;}
      .res-box.x1 .res-valor{color:#2980b9;}
      .res-box.x2 .res-valor{color:#e74c3c;}
      .error-box{
        background:#fff0ee; border:1px solid #f5c6c0;
        border-radius:8px; padding:12px 14px;
        color:#c0392b; font-size:.85rem; margin-top:4px;
      }
      .no-reales{
        background:#fdf3e3; border:1px solid #f8c98d;
        border-radius:8px; padding:14px; color:#9a6700; font-size:.88rem;
        line-height:1.5;
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
        <small>Práctica 22 · PHP</small>
        <h1>Fórmula General</h1>
      </div>

      <div class="formula">x = (−b ± √(b² − 4ac)) / 2a</div>

      <form method="POST" action="">
        <div class="campos">
          <div class="grupo">
            <label for="val_a">a</label>
            <input type="number" id="val_a" name="val_a" step="any"
                   placeholder="1"
                   value="<?= htmlspecialchars($_POST['val_a'] ?? '') ?>">
          </div>
          <div class="grupo">
            <label for="val_b">b</label>
            <input type="number" id="val_b" name="val_b" step="any"
                   placeholder="−5"
                   value="<?= htmlspecialchars($_POST['val_b'] ?? '') ?>">
          </div>
          <div class="grupo">
            <label for="val_c">c</label>
            <input type="number" id="val_c" name="val_c" step="any"
                   placeholder="6"
                   value="<?= htmlspecialchars($_POST['val_c'] ?? '') ?>">
          </div>
        </div>
        <button type="submit" class="btn">Calcular x₁ y x₂</button>
      </form>

      <?php if ($error): ?>
        <hr class="sep">
        <div class="error-box">⚠ <?= htmlspecialchars($error) ?></div>

      <?php elseif ($calculado): ?>
        <hr class="sep">
        <div class="res-titulo">Resultados</div>

        <?php if ($discriminante < 0): ?>
          <div class="no-reales">
            ⚠ Discriminante = <strong><?= $discriminante ?></strong><br>
            Como es negativo, la ecuación no tiene<br>soluciones reales (raíces complejas).
          </div>
        <?php else: ?>
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
          <p style="text-align:center;font-size:.78rem;color:var(--muted);margin-top:10px;">
            Discriminante: <?= $discriminante ?>
          </p>
        <?php endif; ?>
      <?php endif; ?>

      <p class="server-badge">⚙ procesado por PHP</p>
    </div>
  </body>
</html>
