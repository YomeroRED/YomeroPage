<?php
// ── Práctica 24: Fecha actual del servidor – PHP ──
// En JavaScript se usaba new Date() del navegador.
// Aquí usamos las funciones de fecha de PHP que corren en el servidor.

// Configurar zona horaria (México – Ciudad de México)
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
    <style>
      :root{
        --bg:#eef2f7; --card:#fff; --dark:#1c1c2e;
        --muted:#8892a4; --border:#dde3ec;
        --x1:#6c3fc5; --x2:#2980b9;
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
        box-shadow:var(--shadow); width:100%; max-width:420px;
        padding:44px 40px 36px; animation:subir .5s ease both;
        text-align:center;
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
      .etiqueta{
        font-size:.65rem; letter-spacing:.2em;
        text-transform:uppercase; color:var(--muted); margin-bottom:20px;
      }
      .emoji-cal{font-size:3.5rem; margin-bottom:14px;}
      .nombre-dia{
        font-size:.9rem; letter-spacing:.18em; text-transform:uppercase;
        color:var(--muted); margin-bottom:8px;
      }
      .fecha-grande{
        font-size:2.6rem; font-weight:700; color:var(--dark);
        line-height:1.1; margin-bottom:4px;
      }
      .anio{
        font-size:1rem; color:var(--muted); letter-spacing:.08em;
        margin-bottom:20px;
      }
      .sep{border:none; border-top:1px solid var(--border); margin:20px 0;}
      .hora-bloque{
        display:inline-flex; align-items:center; gap:10px;
        background:#f4f7fb; border:1px solid var(--border);
        border-radius:10px; padding:10px 20px;
      }
      .hora-bloque .icono{font-size:1.4rem;}
      .hora-texto{
        font-size:1.5rem; font-weight:700; color:var(--dark);
        letter-spacing:.06em;
      }
      .frase{
        margin-top:20px; font-size:.88rem; color:var(--dark); line-height:1.5;
        background:#f8fafc; border:1px solid var(--border);
        border-radius:8px; padding:12px 16px;
      }
      .frase strong{color:var(--x1);}
      .server-badge{
        margin-top:20px; font-size:.65rem; letter-spacing:.12em;
        text-transform:uppercase; color:var(--muted);
      }
      .nota{
        margin-top:12px; font-size:.72rem; color:var(--muted);
        font-style:italic;
      }
    </style>
  </head>
  <body>
    <div class="card">
      <p class="etiqueta">Práctica 24 · PHP</p>

      <div class="emoji-cal">📅</div>

      <p class="nombre-dia"><?= $nombre_dia ?></p>
      <p class="fecha-grande"><?= $dia ?> de <?= $nombre_mes ?></p>
      <p class="anio">año <?= $anio ?></p>

      <hr class="sep">

      <div class="hora-bloque">
        <span class="icono">🕐</span>
        <span class="hora-texto"><?= $hora ?></span>
      </div>

      <div class="frase">
        Hoy es <strong><?= $nombre_dia ?></strong>
        <?= $dia ?> de <?= $nombre_mes ?> del año <?= $anio ?>
      </div>

      <p class="server-badge">⚙ procesado por PHP</p>
      <p class="nota">
        La fecha y hora provienen del servidor PHP,<br>
        no del navegador del cliente.
      </p>
    </div>
  </body>
</html>
