<?php
// ── Práctica 21: Calculadora con PHP del lado del servidor ──

$resultado   = null;
$expresion   = "";
$error       = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $a  = $_POST["num_a"] ?? "";
    $b  = $_POST["num_b"] ?? "";
    $op = $_POST["operador"] ?? "";

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
            case "+": $resultado = $a + $b; break;
            case "-": $resultado = $a - $b; break;
            case "*": $resultado = $a * $b; break;
            case "/":
                if ($b == 0) $error = "Error: División entre cero.";
                else $resultado = $a / $b;
                break;
            case "**": $resultado = pow($a, $b); break;
            default:   $error = "Operador no válido.";
        }

        if ($resultado !== null) {
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
    <link rel="stylesheet" href="/css/estilos-php.css" />
    <style>
      /* Pantalla de resultado estilo calculadora */
      .pantalla {
        background: #1c1c2e;
        padding: 24px 20px 18px;
        text-align: right;
        min-height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        border-radius: 6px;
        margin-bottom: 20px;
      }
      .pantalla .expresion {
        font-size: 0.8rem;
        color: #8892a4;
        margin-bottom: 6px;
        min-height: 18px;
      }
      .pantalla .valor {
        font-size: 2.4rem;
        color: white;
        font-weight: 700;
        word-break: break-all;
      }
    </style>
  </head>
  <body>
    <header>
      <a class="back-link" href="/practica27">← Regresar</a>
      <h1>Calculadora – PHP</h1>
      <span></span>
    </header>

    <main>
      <div class="card">
        <div class="pantalla">
          <div class="expresion"><?= htmlspecialchars($expresion) ?></div>
          <div class="valor">
            <?php
              if ($error)             echo "Error";
              elseif ($resultado !== null) echo $resultado;
              else                    echo "0";
            ?>
          </div>
        </div>

        <form method="POST" action="">
          <div>
            <label for="num_a">Número A</label>
            <input type="number" id="num_a" name="num_a" step="any"
                   placeholder="Ej. 8"
                   value="<?= htmlspecialchars($_POST['num_a'] ?? '') ?>">
          </div>
          <div>
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
          <div>
            <label for="num_b">Número B</label>
            <input type="number" id="num_b" name="num_b" step="any"
                   placeholder="Ej. 3"
                   value="<?= htmlspecialchars($_POST['num_b'] ?? '') ?>">
          </div>
          <button type="submit">= Calcular</button>
        </form>

        <?php if ($error): ?>
          <div class="error">⚠ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <p style="text-align:center; font-size:.7rem; color:#aaa; margin-top:16px; letter-spacing:.1em;">⚙ PROCESADO POR PHP</p>
      </div>
    </main>
  </body>
</html>
