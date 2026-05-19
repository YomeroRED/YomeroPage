<?php
// ── Práctica 24: Fecha actual del servidor – PHP ──

date_default_timezone_set("America/Mexico_City");

$dias_es   = ["domingo","lunes","martes","miércoles","jueves","viernes","sábado"];
$meses_es  = ["","Enero","Febrero","Marzo","Abril","Mayo","Junio",
               "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

$hoy        = new DateTime();
$nombre_dia = $dias_es[(int)$hoy->format("w")];
$dia        = (int)$hoy->format("j");
$nombre_mes = $meses_es[(int)$hoy->format("n")];
$anio       = $hoy->format("Y");
$hora       = $hoy->format("H:i:s");
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fecha Actual – PHP</title>
    <link rel="stylesheet" href="/css/estilos-php.css" />
    <style>
      .fecha-card { text-align: center; }
      .emoji-cal  { font-size: 3.5rem; margin-bottom: 14px; }
      .nombre-dia { font-size: .9rem; letter-spacing: .18em; text-transform: uppercase; color: #888; margin-bottom: 8px; }
      .fecha-grande { font-size: 2.6rem; font-weight: 700; color: #1c1c2e; line-height: 1.1; margin-bottom: 4px; }
      .anio { font-size: 1rem; color: #888; letter-spacing: .08em; margin-bottom: 20px; }
      .hora-bloque {
        display: inline-flex; align-items: center; gap: 10px;
        background: #f4f7fb; border: 1px solid #dde3ec;
        border-radius: 10px; padding: 10px 20px; margin-bottom: 20px;
      }
      .hora-texto { font-size: 1.5rem; font-weight: 700; color: #1c1c2e; letter-spacing: .06em; }
      .frase {
        font-size: .88rem; color: #333; line-height: 1.5;
        background: #f8fafc; border: 1px solid #dde3ec;
        border-radius: 8px; padding: 12px 16px;
      }
      .nota { margin-top: 12px; font-size: .72rem; color: #aaa; font-style: italic; }
    </style>
  </head>
  <body>
    <header>
      <a class="back-link" href="/practica27">← Regresar</a>
      <h1>Fecha Actual – PHP</h1>
      <span></span>
    </header>

    <main>
      <div class="card fecha-card">
        <div class="emoji-cal">📅</div>
        <p class="nombre-dia"><?= $nombre_dia ?></p>
        <p class="fecha-grande"><?= $dia ?> de <?= $nombre_mes ?></p>
        <p class="anio">año <?= $anio ?></p>

        <div class="hora-bloque">
          <span style="font-size:1.4rem">🕐</span>
          <span class="hora-texto"><?= $hora ?></span>
        </div>

        <div class="frase">
          Hoy es <strong><?= $nombre_dia ?></strong>
          <?= $dia ?> de <?= $nombre_mes ?> del año <?= $anio ?>
        </div>

        <p style="font-size:.7rem; color:#aaa; margin-top:16px; letter-spacing:.1em;">⚙ PROCESADO POR PHP</p>
        <p class="nota">La fecha y hora provienen del servidor PHP,<br>no del navegador del cliente.</p>
      </div>
    </main>
  </body>
</html>
