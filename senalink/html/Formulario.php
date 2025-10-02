<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SenaLink - Diagnóstico Empresarial</title>
    <style>
        body {
          background: #f5f8fb;
          font-family: 'Inter', sans-serif;
          -webkit-font-smoothing: antialiased;
          -moz-osx-font-smoothing: grayscale;
        }

        .form-container {
          max-width: 480px;
          margin: 3rem auto;
          background: white;
          border-radius: 1rem;
          padding: 2.5rem 3rem 3rem 3rem;
          box-shadow: 0 4px 30px rgb(0 0 0 / 0.05);
        }

        .questions {
          position: relative;
          min-height: 180px;
        }

        .question {
          position: relative;
          margin-bottom: 1.5rem;
        }

        legend {
          color: #1e293b;
          font-weight: 700;
          font-size: 1.25rem;
          margin-bottom: 0.75rem;
        }

        label {
          display: block;
          font-weight: 600;
          color: #94a3b8;
          margin: 0.8rem 0 0.25rem 0;
          cursor: pointer;
        }

        input[type="number"] {
          width: 100%;
          padding: 0.75rem;
          border: 1px solid #d1d5db;
          border-radius: 0.5rem;
          font-size: 1rem;
          transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input[type="number"]:focus {
          outline: none;
          border-color: #38bdf8;
          box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1);
        }

        .btn-next {
          margin-top: 2.5rem;
          background: #38bdf8;
          color: white;
          border: none;
          font-weight: 700;
          padding: 0.85rem 0;
          width: 100%;
          border-radius: 0.5rem;
          cursor: pointer;
          font-size: 1rem;
          transition: background-color 0.3s ease;
        }

        .btn-next:hover {
          background: #0ea5e9;
        }

        .card {
          background: white;
          border: 1px solid #e5e7eb;
          border-radius: 0.5rem;
          padding: 1.5rem;
          box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
          transition: box-shadow 0.2s ease;
          margin-bottom: 1rem;
        }

        .card:hover {
          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Diagnóstico Empresarial</h1>
        <form id="form-diagnostico" class="questions"></form>
        <button id="btn-enviar" class="btn-next">Enviar respuestas</button>
    </div>

    <h2 style="text-align:center; margin-top:2rem;">Recomendaciones</h2>
    <div id="recomendaciones-container"></div>

<script>
class DiagnosticoApp {
    constructor() {
        this.form = document.getElementById("form-diagnostico");
        this.btnEnviar = document.getElementById("btn-enviar");
        this.recomendacionesContainer = document.getElementById("recomendaciones-container");

        this.btnEnviar.addEventListener("click", (e) => {
            e.preventDefault();
            this.enviarRespuestas();
        });
        this.cargarPreguntas();
    }

    async cargarPreguntas() {
        const res = await fetch("../controllers/DiagnosticoController.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ accion: "obtenerDiagnosticoCompleto" })
        });
        const data = await res.json();

        if (data.success) {
            this.form.innerHTML = "";

            // ✅ Eliminamos las últimas 2 preguntas
            const preguntas = data.preguntas.slice(0, -2);

            preguntas.forEach(p => {
                const div = document.createElement("div");
                div.classList.add("question");
                div.innerHTML = `<legend>${p.enunciado}</legend>`;

                if (p.opciones.length > 0) {
                    p.opciones.forEach(op => {
                        const label = document.createElement("label");
                        label.innerHTML = `
                            <input type="radio" name="${p.id}" value="${op}"><span>${op}</span>
                        `;
                        div.appendChild(label);
                    });
                } else {
                    const input = document.createElement("input");
                    input.type = "number";
                    input.name = p.id;
                    input.placeholder = "Ingrese un valor";
                    div.appendChild(input);
                }
                this.form.appendChild(div);
            });
        }
    }
    async enviarRespuestas() {
    const formData = new FormData(this.form);
    const respuestas = {};
    formData.forEach((v, k) => respuestas[k] = v);

    const res = await fetch("../controllers/DiagnosticoController.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ accion: "procesarRespuestas", respuestas })
    });
    const data = await res.json();

    // ✅ Guardar en localStorage
    localStorage.setItem("recomendaciones", JSON.stringify(data.recomendaciones || []));

    // ✅ Volver a la página anterior y recargarla
    window.location.href = document.referrer;
}



    renderRecomendaciones(programas) {
        this.recomendacionesContainer.innerHTML = "";
        if (!programas.length) {
            this.recomendacionesContainer.innerHTML = "<p>No se encontraron programas.</p>";
            return;
        }
        programas.forEach(prog => {
            const card = document.createElement("div");
            card.classList.add("card");
            card.innerHTML = `
                <h3>${prog.nombre_programa}</h3>
                <p><b>Nivel:</b> ${prog.nivel_formacion}</p>
                <p><b>Ocupación:</b> ${prog.nombre_ocupacion}</p>
                <p><b>Sector:</b> ${prog.sector_economico}</p>
                <p><b>Duración:</b> ${prog.duracion_programa} horas</p>
                <p><b>Etapa:</b> ${prog.etapa_ficha}</p>
                <p><b>Puntaje:</b> ${prog.score}</p>
            `;
            this.recomendacionesContainer.appendChild(card);
        });
    }
}

document.addEventListener("DOMContentLoaded", () => new DiagnosticoApp());
</script>
</body>
</html>
