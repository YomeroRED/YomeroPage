<?php
// ── Práctica 21: Calculadora con PHP del lado del servidor ──

$resultado   = null;
$expresion   = "";
$error       = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $a  = $_POST["num_a"] ?? "";
    $b  = $_POST["num_b"] ?? "";
    $op = $_POST["operador"] ?? "";

    // Validación
    if ($a === "" || $b === "") {
        $error = "Por favor ingresa ambos números.";
    } elseif (!is_numeric($a) || !is_numeric($b)) {
        $error = "Los valores deben ser numéricos.";
    } else {
        $a = (float)$a;
        $b = (float)$b;

        $simbolos = ["+" => "+", "-" => "−", "*" => "×", "/" => "÷", "**" => "^"];
        $sim = $simbolos[$op] ?? $op;

        switch ($op) {
            case "+":
                $resultado = $a + $b;
                break;
            case "-":
                $resultado = $a - $b;
                break;
            case "*":
                $resultado = $a * $b;
                break;
            case "/":
                if ($b == 0) {
                    $error = "Error: División entre cero.";
                } else {
                    $resultado = $a / $b;
                }
                break;
            case "**":
                $resultado = pow($a, $b);
                break;
            default:
                $error = "Operador no válido.";
        }

        if ($resultado !== null) {
            // Redondear si hay muchos decimales
            $resultado = round($resultado, 10);
            $expresion = "$a $sim $b =";
        }
    }
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Calculadora – PHP</title>
    <style>
      :root {
        --bg: #eef2f7;
        --card: #ffffff;
        --dark: #1c1c2e;
        --muted: #8892a4;
        --border: #dde3ec;
        --x1: #e74c3c;
        --x2: #2980b9;
        --shadow: 0 10px 40px rgba(28,28,46,0.1);
      }
      * { margin:0; padding:0; box-sizing:border-box; }
      body {
        background: var(--bg);
        font-family: "Courier New", Courier, monospace;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 16px;
      }
      body::before {
        content:"";
        position:fixed; inset:0;
        background-image: radial-gradient(circle,#c5cfe0 1px,transparent 1px);
        background-size: 28px 28px;
        opacity: 0.5;
        pointer-events: none;
      }
      .card {
        position: relative;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: var(--shadow);
        width: 100%; max-width: 360px;
        padding: 0 0 28px 0;
        animation: subir 0.5s ease both;
        overflow: hidden;
      }
      @keyframes subir {
        from { opacity:0; transform:translateY(28px); }
        to   { opacity:1; transform:translateY(0); }
      }
      .card::before {
        content:"";
        position:absolute; top:0; left:0; right:0; height:5px;
        background: linear-gradient(90deg,var(--x1),var(--x2));
      }
      /* Pantalla */
      .pantalla {
        background: var(--dark);
        padding: 28px 24px 20px;
        text-align: right;
        min-height: 110px;
        display: flex; flex-direction: column; justify-content: flex-end;
      }
      .pantalla .expresion {
        font-size: 0.8rem; color: var(--muted);
        margin-bottom: 6px; min-height: 18px; letter-spacing:.05em;
      }
      .pantalla .resultado {
        font-size: 2.4rem; color: white; font-weight: 700;
        letter-spacing: -0.02em; word-break: break-all;
      }
      /* Formulario */
      .form-body { padding: 20px 20px 0; }
      .grupo { margin-bottom: 14px; }
      .grupo label {
        display: block; font-size: 0.72rem; letter-spacing:.12em;
        text-transform: uppercase; color: var(--muted); margin-bottom: 5px;
      }
      .grupo input, .grupo select {
        width: 100%; padding: 10px 14px;
        border: 1.5px solid var(--border); border-radius: 8px;
        font-family: inherit; font-size: 1rem; background: #f8fafc;
        color: var(--dark); outline: none; transition: border-color .2s;
      }
      .grupo input:focus, .grupo select:focus { border-color: var(--x2); }
      .btn {
        display: block; width: calc(100% - 40px); margin: 18px 20px 0;
        padding: 13px; border: none; border-radius: 10px;
        background: linear-gradient(90deg,var(--x1),var(--x2));
        color: white; font-size: 1rem; font-weight: 700;
        cursor: pointer; letter-spacing:.06em; transition: opacity .2s;
      }
      .btn:hover { opacity: .88; }
      .error-box {
        margin: 12px 20px 0;
        background: #fff0ee; border: 1px solid #f5c6c0;
        border-radius: 8px; padding: 10px 14px;
        color: #c0392b; font-size: .85rem;
      }
      .server-badge {
        text-align:center; font-size:.65rem; letter-spacing:.12em;
        text-transform:uppercase; color:var(--muted); margin-top:14px;
      }
    </style>
  </head>
  <body>
    <div class="card">
      <!-- Pantalla de resultado -->
      <div class="pantalla">
        <div class="expresion">
          <?= htmlspecialchars($expresion) ?>
        </div>
        <div class="resultado">
          <?php
            if ($error)          echo "Error";
            elseif ($resultado !== null) echo $resultado;
            else                 echo "0";
          ?>
        </div>
      </div>

      <!-- Formulario PHP -->
      <form method="POST" action="" class="form-body">
        <div class="grupo">
          <label for="num_a">Número A</label>
          <input type="number" id="num_a" name="num_a" step="any"
                 placeholder="Ej. 8"
                 value="<?= htmlspecialchars($_POST['num_a'] ?? '') ?>">
        </div>
        <div class="grupo">
          <label for="operador">Operador</label>
          <select id="operador" name="operador">
            <?php
              $ops = ["+" => "+  Suma", "-" => "−  Resta", "*" => "×  Multiplicación",
                      "/" => "÷  División", "**" => "^  Potencia"];
              $sel = $_POST['operador'] ?? '+';
              foreach ($ops as $val => $label):
            ?>
              <option value="<?= $val ?>" <?= $val === $sel ? 'selected' : '' ?>>
                <?= $label ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="grupo">
          <label for="num_b">Número B</label>
          <input type="number" id="num_b" name="num_b" step="any"
                 placeholder="Ej. 3"
                 value="<?= htmlspecialchars($_POST['num_b'] ?? '') ?>">
        </div>
        <button type="submit" class="btn">= Calcular</button>
      </form>

      <?php if ($error): ?>
        <div class="error-box">⚠ <?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <p class="server-badge">⚙ procesado por PHP</p>
    </div>
  </body>
</html>
