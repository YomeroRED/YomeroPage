fetch("proyectos/")
  .then((response) => response.text())
  .then((html) => {
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, "text/html");

    const links = doc.querySelectorAll("a");

    const contenedor = document.getElementById("lista-proyectos");

    links.forEach((link) => {
      let nombre = link.getAttribute("href");

      if (nombre.endsWith(".html")) {
        let card = document.createElement("div");

        card.innerHTML = `<a href="proyectos/${nombre}" target="_blank">${nombre}</a>`;

        contenedor.appendChild(card);
      }
    });
  });
