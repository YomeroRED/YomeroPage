<?php
// ── Práctica 26: Generador de Tablas de Multiplicar (N dinámico) – PHP ──
// En JS el usuario ingresaba un número y se generaban con un ciclo for.
// Aquí el formulario envía el número al servidor y PHP genera el HTML.

$n     = null;
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $raw = $_POST["numero"] ?? "";

    if ($raw === "") {
        $error = "Por favor ingresa un número.";
    } elseif (!ctype_digit($raw) || (int)$raw <= 0) {
        $error = "Error: Por favor, ingresa un número entero positivo válido.";
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
    <style>
      body{
        font-family:"Segoe UI",Tahoma,Geneva,Verdana,sans-serif;
        background-color:#f4f7f6;
        display:flex; flex-direction:column; align-items:center;
        padding:20px;
      }
      h1{color:#2c3e50;}
      .subtitle{
        font-size:.78rem; letter-spacing:.12em; text-transform:uppercase;
        color:#7f8c8d; margin-bottom:8px;
      }
      p.desc{color:#555; margin-bottom:16px; text-align:center; max-width:480px;}
      .form-row{display:flex; gap:10px; align-items:center; margin-bottom:10px;}
      input[type="number"]{
        padding:10px 14px; font-size:16px; border:1.5px solid #ccc;
        border-radius:5px; width:160px; outline:none;
        transition:border-color .2s;
      }
      input[type="number"]:focus{border-color:#2980b9;}
      button{
        padding:10px 20px; font-size:16px;
        background-color:#2980b9; color:white;
        border:none; border-radius:5px; cursor:pointer; transition:background .3s;
      }
      button:hover{background-color:#3498db;}
      hr{border:none; border-top:1px solid #ddd; margin:16px 0; width:100%; max-width:900px;}
      .error{color:#c0392b; font-weight:600; margin-top:8px;}
      .grid-tablas{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
        gap:20px; width:100%; max-width:1100px; margin-top:20px;
      }
      .tabla-card{
        background:white; padding:15px; border-radius:8px;
        box-shadow:0 4px 6px rgba(0,0,0,.1); text-align:center;
      }
      .tabla-card h3{
        margin-top:0; color:#2980b9;
        border-bottom:2px solid #ecf0f1; padding-bottom:10px;
      }
      .linea-tabla{margin:5px 0; font-size:14px; color:#34495e;}
      .server-badge{
        margin-top:24px; font-size:.65rem; letter-spacing:.12em;
        text-transform:uppercase; color:#7f8c8d;
      }
    </style>
  </head>
  <body>
    <h1>Generador de Tablas de Multiplicar</h1>
    <p class="subtitle">Práctica 26 · PHP del lado del servidor</p>
    <p class="desc">
      Introduce un número entero positivo (máx. 50) para generar
      las tablas desde el 1 hasta dicho valor.
    </p>

    <form method="POST" action="" class="form-row">
      <input type="number" name="numero" min="1" max="50"
             placeholder="Ejemplo: 5"
             value="<?= htmlspecialchars($_POST['numero'] ?? '') ?>">
      <button type="submit">Generar tablas</button>
    </form>

    <?php if ($error): ?>
      <p class="error">⚠ <?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($n !== null): ?>
      <hr>
      <!-- PHP genera N tablas en el servidor con ciclos anidados -->
      <div class="grid-tablas">
        <?php for ($i = 1; $i <= $n; $i++): ?>
          <div class="tabla-card">
            <h3>Tabla del <?= $i ?></h3>
            <?php for ($j = 1; $j <= 10; $j++): ?>
              <div class="linea-tabla">
                <?= $i ?> × <?= $j ?> = <strong><?= $i * $j ?></strong>
              </div>
            <?php endfor; ?>
          </div>
        <?php endfor; ?>
      </div>
    <?php endif; ?>

    <p class="server-badge">⚙ Las tablas son generadas por PHP en el servidor</p>
  </body>
</html>
