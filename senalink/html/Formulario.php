<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SenaLink - Diagnóstico Empresarial</title>
    <link rel="stylesheet" href="../css/components/gov.css">
    <link rel="stylesheet" href="../css/pages/Formulario.css">
    <link rel="stylesheet" href="../css/base.css">
</head>
    <header class="gov" id="inicio">
        <div class="gov__container">
            <a href="https://www.gov.co/" target="_blank">
                <img loading="lazy" src="../img/gov-logo.svg" alt="Logo de la pagina gov.co" class="gov__img">
            </a>
        </div>
    </header>
<body>
    <div class="form-container">
        <h1>Diagnóstico Empresarial</h1>
        <form id="form-diagnostico" class="questions"></form>
        <button id="btn-enviar" class="btn-next">Enviar respuestas</button>
        <!-- Nuevo botón que se muestra solo si la URL tiene ?from=gestion -->
        <button id="btn-especifico" class="btn-specific">Gestion</button>
        <button type="btn" onclick="goBack()" class="btn-volver">Volver</button>
    </div>
<script src="../js/backbutton.js"></script>
<script>
class DiagnosticoApp {
    constructor() {
        this.form = document.getElementById("form-diagnostico");
        this.btnEnviar = document.getElementById("btn-enviar");
        this.btnEspecifico = document.getElementById("btn-especifico"); // Referencia al nuevo botón
        this.recomendacionesContainer = document.getElementById("recomendaciones-container");

        // Verificar si la URL tiene el parámetro 'from=gestion' y mostrar el botón
        this.verificarParametroUrlYMostrarBoton();

        this.btnEnviar.addEventListener("click", (e) => {
            e.preventDefault();
            this.enviarRespuestas();
        });

        // Listener para el nuevo botón: redirigir a otra página
        this.btnEspecifico.addEventListener("click", (e) => {
            e.preventDefault();
            window.location.href = 'Super_Admin/Diagnostico/VerDiagnostico.html';
        });

        this.cargarPreguntas();
    }

    verificarParametroUrlYMostrarBoton() {
        // Obtener parámetros de la URL actual
        const urlParams = new URLSearchParams(window.location.search);
        const fromParam = urlParams.get('from');
        
        // Si el parámetro 'from' es 'gestion', mostrar el botón
        if (fromParam === 'gestion') {
            this.btnEspecifico.classList.add('show');
        }
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

            const preguntas = data.preguntas.slice(0);

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

    // Método opcional para renderizar recomendaciones desde localStorage (útil si el botón lo activa)
    renderRecomendacionesFromLocalStorage() {
        const recomendaciones = JSON.parse(localStorage.getItem("recomendaciones") || "[]");
        this.renderRecomendaciones(recomendaciones);
    }
}

document.addEventListener("DOMContentLoaded", () => new DiagnosticoApp());
</script>
</body>
</html>
