<?php
// ── Práctica 25: Tablas de Multiplicar del 1 al 10 – PHP ──

$generar = isset($_POST["generar"]);
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tablas de Multiplicar 1-10 – PHP</title>
    <link rel="stylesheet" href="/css/estilos-php.css" />
    <style>
      .grid-tablas {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-top: 20px;
      }
      .tabla-card {
        background: white;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #ddd;
        box-shadow: 0 2px 6px rgba(0,0,0,.07);
        text-align: center;
      }
      .tabla-card h3 {
        margin-top: 0;
        color: #2980b9;
        border-bottom: 2px solid #ecf0f1;
        padding-bottom: 10px;
      }
      .linea-tabla { margin: 5px 0; font-size: 14px; color: #34495e; }
    </style>
  </head>
  <body>
    <header>
      <a class="back-link" href="/practica27">← Regresar</a>
      <h1>Tablas del 1 al 10 – PHP</h1>
      <span></span>
    </header>

    <main>
      <div class="card">
        <h2>Tablas de Multiplicar</h2>
        <p style="color:#666; margin-bottom:16px;">
          Genera las tablas del 1 al 10 desde el servidor con PHP.
        </p>

        <form method="POST" action="">
          <button type="submit" name="generar">Generar tablas</button>
        </form>

        <?php if ($generar): ?>
          <div class="grid-tablas">
            <?php for ($i = 1; $i <= 10; $i++): ?>
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
