<?php
// ── Práctica 30: Crear un usuario

?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Crear usuario</title>
    <link rel="stylesheet" href="/css/estilos-php.css" />
  </head>
  <body>
    <header>
      <a class="back-link" href="/">← Regresar</a>
      <h1>Create un usuario</h1>
      <span></span>
    </header>

    <main>
      <div class="card">
        <h2>Generador de Usuario e Iniciales</h2>

        <div class="campo">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" placeholder="Ej. Juan">
        </div>
    
        <div class="campo">
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" placeholder="Ej. Lopez">
        </div>
        
        <button type="submit" onclick="procesarDatos()">Generar</button>
        
        <div id="salida" class="resultado" style="margin-top:16px;"></div>

        <script>
            function procesarDatos() {
                // 1. Leer las entradas del formulario
                const nombre = document.getElementById('nombre').value.trim();
                const apellido = document.getElementById('apellido').value.trim();

                // Validar que los campos no estén vacíos
                if (nombre === "" || apellido === "") {
                    document.getElementById('salida').innerText = "Por favor, llena ambos campos.";
                    return;
                }

                // 2. Crear el nombre de usuario (unir ambos y convertir a minúsculas)
                const usuario = (nombre + apellido).toLowerCase();

                // 3. Obtener las iniciales (primer carácter de cada uno en mayúsculas)
                const iniciales = (nombre.charAt(0) + apellido.charAt(0)).toUpperCase();

                // 4. Imprimir el resultado exactamente como se solicitó
                document.getElementById('salida').innerHTML = 
                    `Nombre de usuario: ${usuario}<br>Iniciales: ${iniciales}`;
            }
        </script>

        <p style="text-align:center; font-size:.7rem; color:#aaa; margin-top:16px; letter-spacing:.1em;">Yomero estuvo aqui</p>
      </div>
    </main>
  </body>
</html>
          