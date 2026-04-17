fetch("data/practicas.json")
  .then((respuesta) => respuesta.json())
  .then((practicas) => {
    const contenedor = document.getElementById("contenedor-practicas");

    practicas.forEach((practica) => {
      const card = document.createElement("div");

      card.className = "card";

      // La práctica 27 tiene ruta especial; las demás van a /proyectos/
      const href = practica.especial
        ? practica.archivo
        : "proyectos/" + practica.archivo;

      card.innerHTML = `
<h3>${practica.nombre}</h3>
<p>${practica.descripcion}</p>
<a href="${href}" target="_blank">
Ver práctica
</a>
`;

      contenedor.appendChild(card);
    });
  });
