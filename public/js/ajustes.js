window.onload = iniciar;
function iniciar() {
    document.querySelectorAll(".button").forEach((obj) => {
        obj.addEventListener("click", () => mostrar(obj.id));
    });
}
async function mostrar(id) {
    let div = document.getElementById(id);
    if (realizado[id]) {
        return;
    }
    if (id === "mostrar") {
        div.innerHTML = await cargarJson(0);
        realizado[id] = true;
        return;
    }
    div.innerHTML = contenido[id];
    realizado[id] = true;
}

async function cargarJson(id) {
    let datos = "";
    try {
        const response = await fetch("/log/show", {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
        });

        const data = await response.json(); // Esperamos a que se convierta a JSON
        console.log(data); // Ver los datos en consola para depuración

        // Iteramos sobre los datos para construir el HTML
        data.forEach((element) => {
            datos += `<div class="usu ${element.id}">
                    <h5>Nivel: ${nivel(element.nivel)}</h5>
                    <p>Nombre: ${element.nombre} - Correo: ${element.correo}</p>
                  </div>`;
        });
    } catch (error) {
        console.error("Error:", error); // Mostrar el error en caso de fallo
        return "<p>Servicio no disponible</p>";
    }

    return datos; // Ahora puedes retornar los datos
}

function nivel(nivel) {
    if (nivel == 0) {
        return "usuario";
    } else if (nivel == 1 || nivel == 2) {
        return "admin";
    }
    return "Nivel del usuario incorrecto";
}
