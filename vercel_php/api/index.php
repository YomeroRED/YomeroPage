<?php
$practicas = [
    ["nombre" => "Práctica 21", "ruta" => "/proyectos/practica21", "descripcion" => "Calculadora, con formulario bonito."],
    ["nombre" => "Práctica 22", "ruta" => "/proyectos/practica22", "descripcion" => "Fórmula general (ecuación cuadrática) con formularios."],
    ["nombre" => "Práctica 23", "ruta" => "/proyectos/practica23", "descripcion" => "Calculadora de IMC de mujeres únicamente."],
    ["nombre" => "Práctica 24", "ruta" => "/proyectos/practica24", "descripcion" => "Fecha actual del servidor."],
    ["nombre" => "Práctica 25", "ruta" => "/proyectos/practica25", "descripcion" => "Tablas de multiplicar del 1 al 10."],
    ["nombre" => "Práctica 26", "ruta" => "/proyectos/practica26", "descripcion" => "Tablas de multiplicar dinámicas (número ingresado por el usuario)."],
];
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mis Trabajos – Prácticas 21 a 26</title>
    <link rel="stylesheet" href="/css/estilos.css" />
  </head>
  <body>
    <header>
      <div class="menu-icon">&#9776;</div>
      <h1>Mis trabajos</h1>
      <div class="opiniones">
        Opiniones
        <div class="trash-icon">&#128465;</div>
      </div>
    </header>

    <main>
      <section class="practicas">
        <h2>Prácticas 21 – 26 <span class="badge">PHP</span></h2>
        <div id="contenedor-practicas">
          <?php foreach ($practicas as $p): ?>
            <div class="card">
              <h3><?= htmlspecialchars($p['nombre']) ?></h3>
              <p><?= htmlspecialchars($p['descripcion']) ?></p>
              <a href="<?= $p['ruta'] ?>" target="_blank">Ver práctica</a>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    </main>

    <footer>
      <button onclick="window.open('https://classroom.google.com','_blank')">Classroom</button>
      <button onclick="window.open('https://chatgpt.com','_blank')">ChatGPT</button>
      <button>Buscaminas</button>
    </footer>
  </body>
</html>
