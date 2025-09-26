<?php
// require_once '../../controllers/session_expiration.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../../css/alert.css">
    <link rel="stylesheet" href="../../css/components/gov.css">
    <link rel="stylesheet" href="../../css/components/buttons.css">
    <!-- <link rel="stylesheet" href="../../css/layouts/Form_layout.css"> -->
    <!-- <link rel="stylesheet" href="../../css/base.css"> -->
    <link rel="stylesheet" href="../../css/Pages/Home.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<title>Senalink-Home</title>
</head>
<body>
    <header class="gov" id="inicio">
        <div class="gov__container">
        <a href="https://www.gov.co/" target="_blank">
            <img loading="lazy" src="../../img/gov-logo.svg" alt="Logo de la pagina gov.co" class="gov__img">
        </a>
        </div>
    </header>
<header class="header-main">
  <nav>
    <a href="#">Inicio</a>
    <a href="#recommendations">Recomendaciones</a>
    <a href="#contact">Contáctenos</a>
    <a href="../Preguntas_frecuentes.html">Preguntas frecuentes</a>
    <a href="../viewuser.html">Mi perfil</a>
    <a href="Empresa/Gestion_Empresa.html">Gestión</a>
    <a href="#" id="cerrar-sesion">Cerrar sesión</a>
  </nav>
</header>

<main>
  <div class="Diagnostico">
    <div class="left-content">

      <img src="../../img/logo-senalink1.png" alt="" class="logo">

      <p class="subtitle">
        El aplicativo que ofreceremos a las empresas brindará diversos servicios asociados a cada programa de formación. Las empresas podrán consultar información detallada sobre los programas de formación disponibles en el CDITI.
      </p>

      <a href="../Formulario.php" class="btn">Inicia tu diagnostico</a>

    </div>
      <div class="illustration" aria-label="Ilustración de tarjetas AI">
      <img src="../../img/img_home.png" alt="" class="img_home">
    </div>
  </div>

        <section class="recommendations" id="recommendations">
            <h2 class="section-title">Programas Recomendados</h2>
            <div class="grid">
              <div id="resultados-home" class="cards"></div>
                <!-- <div class="card">
                    <div class="card-content">
                        <h3 class="card-title">Desarrollo Web Full Stack</h3>
                        <p class="card-description">Aprende a construir aplicaciones web completas con HTML, CSS, JavaScript, Node.js y más. Ideal para principiantes y avanzados.</p>
                        <a class="card-button" href="#contact">Más Información</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-image">
                        <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/fe173fed-b053-4b99-8246-831920b0d302.png" alt="Vista de un gráfico de datos y análisis en una pantalla de computadora, colores vibrantes con gráficos de barras y líneas ascendentes, ambiente de oficina moderna con luz natural" />
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Ciencia de Datos</h3>
                        <p class="card-description">Domina Python, SQL, machine learning y análisis de datos. Transfórmate en un experto en data science con proyectos reales.</p>
                        <a class="card-button" href="#contact">Más Información</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-image">
                        <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/7551bde6-b3f7-47d4-b394-f6dafc366d1b.png" alt="Grupo de personas colaborando en una reunión virtual con gráficos de marketing, tonos cálidos naranjas y amarillos, espacio de coworking con plantas y mesas modernas" />
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Marketing Digital</h3>
                        <p class="card-description">Aprende SEO, redes sociales, Google Ads y estrategias de marketing digital para crecer tu negocio en el mundo online.</p>
                        <a class="card-button" href="#contact">Más Información</a>
                    </div> -->
                </div>
            </div>
        </section>

        <section class="cta">
            <div class="cta-content">
                <h2>¿Tienes algún problema?</h2>
                <p>Comunícate por este medio para resolver cualquier inquietud.</p>
                <a class="cta-button" href="#contact">Contáctanos</a>
            </div>
        </section>
    </main>

        <!-- Modal de contacto mejorado -->
        <style>
        @import url(variables.css);    

        #modal-contacto {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        .modal-contacto-content {
            background: #fff;
            color: #000;
            padding: 2.5rem 3.5rem;
            border-radius: 14px;
            min-width: 420px;
            max-width: 600px;
            min-height: 420px;
            box-shadow: 0 0 32px #000;
            position: relative;
            font-family: 'Roboto', Arial, sans-serif;
        }
        .modal-contacto-content form {
            display: flex;
            flex-direction: column;
        }
        .modal-contacto-content h2 {
            margin-top: 0;
            margin-bottom: 1.7rem;
            font-size: 1.7rem;
            font-weight: 600;
        }
        .modal-contacto-content label {
            display: block;
            margin-bottom: 0.3rem;
            font-size: 1.15rem;
        }
        .modal-contacto-content input,
        .modal-contacto-content textarea {
            width: 100%;
            margin-bottom: 1.2rem;
            padding: 13px 16px;
            border-radius: 8px;
            border: none !important;
            outline: none !important;
            font-size: 1.15rem;
            background: #dad5d5d2;
            color: #000;
            box-sizing: border-box;
            box-shadow: none !important;
        }
        .modal-contacto-content textarea {
            min-height: 80px;
            resize: vertical;
        }
        .modal-contacto-content .modal-contacto-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 0.5rem;
        }
        .modal-contacto-content button[type="submit"] {
            background: var(--primary-950);
            color: #fff;
            border: none;
            padding: 8px 22px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.5rem;
            font-weight: 500;
        }
        .modal-contacto-content button[type="button"] {
            background: #444;
            color: #fff;
            border: none;
            padding: 8px 22px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.5rem;
            font-weight: 500;
        }
        #cerrar-modal-contacto {
            position: absolute;
            top: 10px; right: 10px;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
        }
        #contacto-exito {
            color: #4caf50;
            margin-top: 10px;
            text-align: center;
        }
        </style>
        <div id="modal-contacto">
            <div class="modal-contacto-content">
                <button id="cerrar-modal-contacto">&times;</button>
                <h2>Contáctanos</h2>
                <form id="form-contacto">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" required>
                    <label for="correo">Correo</label>
                    <input type="email" id="correo" name="correo" required>
                    <label for="asunto">Asunto</label>
                    <input type="text" id="asunto" name="asunto" required>
                    <label for="mensaje">Mensaje</label>
                    <textarea id="mensaje" name="mensaje" required></textarea>
                    <div class="modal-contacto-actions">
                        <button type="button" id="cancelar-contacto">Cancelar</button>
                        <button type="submit">Enviar</button>
                    </div>
                </form>
                <div id="contacto-exito" style="display:none;">¡Mensaje enviado correctamente!</div>
            </div>
        </div>
        <footer id="contact">
                <p>© 2025 SenaLink. Todos los derechos reservados.</p>
                <p>Contáctanos: SenaLink@gmail.com | Teléfono: +57 </p>
        </footer>

        <script>
        // Función cerrar sesión
        document.getElementById('cerrar-sesion').addEventListener('click', function(e) {
            e.preventDefault();
            // Elimina datos de sesión/localStorage
            sessionStorage.clear();
            localStorage.clear();
            // Redirige al login (ajusta la ruta si tu login es diferente)
            window.location.href = '../index.php';
        });
        // Smooth scrolling para navegación
        document.querySelectorAll('.menu a').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                target.scrollIntoView({ behavior: 'smooth' });
            });
        });

        // Animación de entrada para cards en scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.card').forEach(card => {
            card.style.opacity = 0;
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s, transform 0.6s';
            observer.observe(card);
        });

        // Tooltip simple para botones
        document.querySelectorAll('.card-button, .cta-button').forEach(btn => {
        // Abrir modal de contacto al hacer clic en el botón 'Contáctanos'
        document.querySelectorAll('.cta-button, [href="#contact"]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('modal-contacto').style.display = 'flex';
            });
        });

        // Cerrar modal de contacto
        document.getElementById('cerrar-modal-contacto').addEventListener('click', function() {
            document.getElementById('modal-contacto').style.display = 'none';
        });
        document.getElementById('cancelar-contacto').addEventListener('click', function() {
            document.getElementById('modal-contacto').style.display = 'none';
        });

        // Enviar formulario de contacto por AJAX a send_contact.php
        document.getElementById('form-contacto').addEventListener('submit', function(e) {
            e.preventDefault();
            var form = e.target;
            var formData = new FormData(form);
            var exito = document.getElementById('contacto-exito');
            exito.style.display = 'none';
            fetch('../../controllers/send_contact.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    exito.textContent = '¡Mensaje enviado correctamente!';
                    exito.style.color = '#4caf50';
                    exito.style.display = 'block';
                    setTimeout(function() {
                        document.getElementById('modal-contacto').style.display = 'none';
                        exito.style.display = 'none';
                        form.reset();
                    }, 2000);
                } else {
                    exito.textContent = data.error || 'Error al enviar el mensaje.';
                    exito.style.color = '#ff5252';
                    exito.style.display = 'block';
                }
            })
            .catch(() => {
                exito.textContent = 'Error de conexión.';
                exito.style.color = '#ff5252';
                exito.style.display = 'block';
            });
        });
            btn.addEventListener('mouseenter', () => {
                const tooltip = document.createElement('div');
                tooltip.textContent = 'Haz clic para más detalles';
                tooltip.style.position = 'absolute';
                tooltip.style.background = '#333';
                tooltip.style.color = '#fff';
                tooltip.style.padding = '5px 10px';
                tooltip.style.borderRadius = '5px';
                tooltip.style.fontSize = '12px';
                tooltip.style.pointerEvents = 'none';
                tooltip.style.zIndex = '1000';
                const rect = btn.getBoundingClientRect();
                tooltip.style.left = rect.left + 'px';
                tooltip.style.top = (rect.top - 30) + 'px';
                document.body.appendChild(tooltip);

                btn.addEventListener('mouseleave', () => {
                    document.body.removeChild(tooltip);
                }, { once: true });
            });
        });
        const empresaId = sessionStorage.getItem('empresaId'); // o desde PHP con un `<script>`

        fetch('../../controllers/DiagnosticoController.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                accion: 'obtenerRecomendaciones',
                empresaId: empresaId
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.length > 0) {
                mostrarRecomendaciones(data);
            } else {
                document.getElementById('contenedor').innerHTML = 'No se encontraron recomendaciones';
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const recomendaciones = JSON.parse(localStorage.getItem('recomendaciones')) || [];
            const contenedor = document.getElementById('resultados-home');

            if (recomendaciones.length === 0) {
                contenedor.innerHTML = '<p>No se encontraron recomendaciones.</p>';
                return;
            }

            recomendaciones.forEach(programa => {
                const card = document.createElement('div');
                card.className = 'card';

                card.innerHTML = `
                    <div class="card-content">
                        <h3 class="card-title">${programa.nombre_programa}</h3>
                        <p class="card-description">Código: ${programa.codigo}</p>
                        <a class="card-button" href="Programa de formacion.html?id=${programa.id}">
                            Más Información
                        </a>
                    </div>
                `;

                contenedor.appendChild(card);
            });
        });

    </script>
    <!-- <script>
  // Tiempo de inactividad en milisegundos (ejemplo: 15 minutos)
//   const inactivityTime = 15 * 60 * 1000;
  const inactivityTime = 10 * 1000; // 30 segundos

  let timer;

  function logout() {
    window.location.href = '../index.php?timeout=1'; // Ajusta ruta login
  }

  function resetTimer() {
    clearTimeout(timer);
    timer = setTimeout(logout, inactivityTime);
  }

  // Eventos que indican actividad del usuario
  ['click', 'mousemove', 'keydown', 'scroll', 'touchstart'].forEach(event => {
    window.addEventListener(event, resetTimer);
  });

  resetTimer(); // Iniciar temporizador al cargar la página
</script> -->

</body>
</html>
</content>
</create_file>