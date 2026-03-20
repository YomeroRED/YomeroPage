fetch("data/practicas.json")
  .then((respuesta) => respuesta.json())
  .then((practicas) => {
    const contenedor = document.getElementById("contenedor-practicas");

    practicas.forEach((practica) => {
      const card = document.createElement("div");

      card.className = "card";

      card.innerHTML = `
<h3>${practica.nombre}</h3>
<p>${practica.descripcion}</p>
<a href="proyectos/${practica.archivo}" target="_blank">
Ver practicas
</a>
`;

      contenedor.appendChild(card);
    });
  });
