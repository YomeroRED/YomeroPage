<?php
// ── Práctica 25: Tablas de Multiplicar del 1 al 10 – PHP ──
// En JS se generaban al hacer clic en un botón.
// Con PHP se generan del lado del servidor al cargar (o al enviar el form).

$generar = isset($_POST["generar"]);
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tablas de Multiplicar 1-10 – PHP</title>
    <style>
      body{
        font-family:"Segoe UI",Tahoma,Geneva,Verdana,sans-serif;
        background-color:#f4f7f6;
        display:flex; flex-direction:column; align-items:center;
        padding:20px;
      }
      h1{color:#2c3e50; margin-bottom:16px;}
      .subtitle{
        font-size:.78rem; letter-spacing:.12em; text-transform:uppercase;
        color:#7f8c8d; margin-bottom:20px;
      }
      form{margin-bottom:10px;}
      button{
        padding:10px 20px; font-size:16px;
        background-color:#2980b9; color:white;
        border:none; border-radius:5px; cursor:pointer; transition:background .3s;
      }
      button:hover{background-color:#3498db;}
      .grid-tablas{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
        gap:20px; width:100%; max-width:1200px; margin-top:30px;
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
    <h1>Tablas de Multiplicar (1 al 10)</h1>
    <p class="subtitle">Práctica 25 · PHP del lado del servidor</p>

    <form method="POST" action="">
      <button type="submit" name="generar">Generar tablas de multiplicar</button>
    </form>

    <?php if ($generar): ?>
      <!-- PHP genera todas las tablas en el servidor -->
      <div class="grid-tablas">
        <?php for ($i = 1; $i <= 10; $i++): ?>
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
