<?php
//require_once '../../controllers/session_expiration.php';
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
    <a href="#contact">Contactenos</a>
    <a href="../Preguntas_frecuentes.html">Preguntas frecuentes</a>
    <a href="../viewuser.php">Mi perfil</a>
    <a href="#" id="cerrar-sesion">Cerrar sesion</a>
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
                <h2>¿Tienes algun problema?</h2>
                <p>Comunicate por este medio para resolver cualquier inquietud.</p>
                <a class="cta-button" href="#contact">Contáctanos</a>
            </div>
        </section>
    </main>
            <!-- Static info container for Contáctanos -->
        <style>
        #contact-info-container {
            display: none;
            position: fixed;
            top: 20vh;
            left: 50%;
            transform: translateX(-50%);
            width: 450px;
            background: #fff;
            color: #000;
            padding: 2rem 3rem;
            border-radius: 14px;
            box-shadow: 0 0 32px rgba(0,0,0,0.3);
            z-index: 10000;
            font-family: 'Roboto', Arial, sans-serif;
        }
        #contact-info-container h2 {
            margin-top: 0;
            margin-bottom: 1rem;
            font-size: 1.7rem;
            font-weight: 600;
        }
        #contact-info-container p {
            margin: 0.5rem 0;
            font-size: 1rem;
            color: #333;
        }
        #close-contact-info {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #333;
        }
        .contact-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
        }
        .contact-info-label {
            font-weight: 600;
            color: #6200ea;
        }
        .contact-info-value {
            color: #555;
        }
        </style>
        <div id="contact-info-container" role="dialog" aria-modal="true" aria-labelledby="contact-info-title">
            <button id="close-contact-info" aria-label="Close contact information">&times;</button>
            <h2 id="contact-info-title">Información de Contacto</h2>
            <div class="contact-info-row">
                <span class="contact-info-label">Nombre:</span>
                <span class="contact-info-value">Senalink Información</span>
            </div>
            <div class="contact-info-row">
                <span class="contact-info-label">Teléfono:</span>
                <span class="contact-info-value">+57 313 691 8283</span>
            </div>
            <div class="contact-info-row">
                <span class="contact-info-label">Email:</span>
                <span class="contact-info-value">SenaLink2025@gmail.com</span>
            </div>
            <div class="contact-info-row">
                <span class="contact-info-label">Dirección:</span>
                <span class="contact-info-value">Calle Falsa 123, Ciudad, País</span>
            </div>
            <div class="contact-info-row">
                <span class="contact-info-label">Horario de atención:</span>
                <span class="contact-info-value">Lunes a Viernes, 9am - 6pm</span>
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
        window.location.href = '../../html/index.php';
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

        // ✅ Filtrar solo programas con puntaje entre 5 y 10
        const filtrados = recomendaciones.filter(p => p.score >= 5 && p.score <= 10);

        if (filtrados.length === 0) {
            contenedor.innerHTML = '<p>No se encontraron programas con puntaje entre 5 y 10.</p>';
            return;
        }

        filtrados.forEach(programa => {
            const card = document.createElement('div');
            card.className = 'card';

            card.innerHTML = `
                <div class="card-content">
                    <h3 class="card-title">${programa.nombre_programa}</h3>
                    <p><b>Nivel:</b> ${programa.nivel_formacion}</p>
                    <p><b>Ocupación:</b> ${programa.nombre_ocupacion}</p>
                    <p><b>Sector:</b> ${programa.sector_economico}</p>
                    <p><b>Duración:</b> ${programa.duracion_programa} horas</p>
                    <p><b>Puntaje:</b> ${programa.score}</p>
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

</script>
<script>
    (function(){
        function initContactToggle(){
            const contactBtn = document.querySelector('.cta-button');
            const contactContainer = document.getElementById('contact-info-container');
            const closeBtn = document.getElementById('close-contact-info');

            if (!contactBtn || !contactContainer || !closeBtn) return;

            contactBtn.addEventListener('click', function(e){
                e.preventDefault();
                const isVisible = window.getComputedStyle(contactContainer).display !== 'none';
                contactContainer.style.display = isVisible ? 'none' : 'block';
            });

            closeBtn.addEventListener('click', function(){
                contactContainer.style.display = 'none';
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initContactToggle);
        } else {
            initContactToggle();
        }
    })();
</script>
</body>
</html>
</content>
</create_file>