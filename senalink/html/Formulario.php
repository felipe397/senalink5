<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SenaLink - Diagnóstico Empresarial</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .pregunta { margin-bottom: 20px; }
        .card { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 8px; }
        .card h3 { margin: 0 0 10px; }
    </style>
</head>
<body>
    <h1>Diagnóstico Empresarial</h1>
    <form id="form-diagnostico"></form>
    <button id="btn-enviar">Enviar respuestas</button>

    <h2>Recomendaciones</h2>
    <div id="recomendaciones-container"></div>

<script>
class DiagnosticoApp {
    constructor() {
        this.form = document.getElementById("form-diagnostico");
        this.btnEnviar = document.getElementById("btn-enviar");
        this.recomendacionesContainer = document.getElementById("recomendaciones-container");

        this.btnEnviar.addEventListener("click", () => this.enviarRespuestas());
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
            data.preguntas.forEach(p => {
                const div = document.createElement("div");
                div.classList.add("pregunta");
                div.innerHTML = `<label><b>${p.enunciado}</b></label><br>`;

                if (p.opciones.length > 0) {
                    const select = document.createElement("select");
                    select.name = p.id;
                    select.innerHTML = `<option value="">Seleccione...</option>`;
                    p.opciones.forEach(op => {
                        select.innerHTML += `<option value="${op}">${op}</option>`;
                    });
                    div.appendChild(select);
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
        this.renderRecomendaciones(data.recomendaciones || []);
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
