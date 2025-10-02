<?php
session_start();
if (!isset($_SESSION['rol'])) {
    header("Location: index.php"); 
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Perfil de Usuario - SenaLink</title>
  <link rel="shortcut icon" href="../img/Favicon1.png" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../css/components/gov.css">
  <link rel="stylesheet" href="../css/components/buttons.css">
  <link rel="stylesheet" href="../css/components/forms.css">
  <link rel="stylesheet" href="../css/Pages/perfil.css" />
</head>

<body>
  <header class="gov" id="inicio">
    <div class="gov__container">
      <a href="https://www.gov.co/" target="_blank">
        <img loading="lazy" src="../img/gov-logo.svg" alt="Logo de la pagina gov.co" class="gov__img">
      </a>
    </div>
  </header>

  <header class="header-main">
    <nav>
      <a href="#" id="home-btn">Inicio</a>
      <a href="#" id="cerrar-sesion">Cerrar sesión</a>
    </nav>
  </header>

  <main class="container">
    <section class="profile-card">
      <img src="../img/logo-proyecto1.png" alt="Logo Proyecto" class="project-logo" />
      <h1 class="welcome-title">Bienvenido Usuario</h1>

      <div class="user-info">
        <div id="empresa-section" class="user-details" hidden>
          <h2>Datos de la Empresa</h2>
          <p><strong>NIT:</strong> <span id="nit"></span></p>
          <p><strong>Razón Social:</strong> <span id="razon_social"></span></p>
          <p><strong>Representante Legal:</strong> <span id="representante_legal"></span></p>
          <p><strong>Tipo de Empresa:</strong> <span id="tipo_empresa"></span></p>
          <p><strong>Teléfono:</strong> <span id="telefono_empresa"></span></p>
          <p><strong>Correo Electrónico:</strong> <span id="correo_empresa"></span></p>
          <p><strong>Dirección:</strong> <span id="direccion_empresa"></span></p>
          <p><strong>Departamento:</strong> <span id="departamento_empresa"></span></p>
          <p><strong>Ciudad:</strong> <span id="ciudad_empresa"></span></p>
          <p><strong>Estado:</strong> <span id="estado"></span></p>
        </div>

        <div id="admin-section" class="user-details" hidden>
          <h2>Datos del Administrativo</h2>
          <p><strong>Nombre:</strong> <span id="nombre_admin"></span></p>
          <p><strong>Apellidos:</strong> <span id="apellido_admin"></span></p>
          <p><strong>Correo Electrónico:</strong> <span id="correo_admin"></span></p>
          <p><strong>Teléfono:</strong> <span id="telefono_admin"></span></p>
        </div>
      </div>

      <div class="actions">
        <button id="edit-btn" class="btn edit">Actualizar</button>
        <button id="back-btn" class="btn">Volver</button>
      </div>
    </section>
  </main>

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <script src="../js/viewuser.js"></script>
  <script src="../js/alert.js"></script>
  <script src="../js/backbutton.js"></script>

  <script>
    // Guardar el rol desde PHP en sessionStorage
    const userRole = '<?php echo $_SESSION['rol'] ?? ''; ?>';
    if (userRole) {
      sessionStorage.setItem('userRole', userRole);
    }

    // Referencias a botones
    const homeBtn = document.getElementById('home-btn');
    const backBtn = document.getElementById('back-btn');
    const editBtn = document.getElementById('edit-btn');

    // Redirigir según rol
    const role = sessionStorage.getItem('userRole');
    switch (role) {
      case 'super_admin':
        homeBtn.href = '../html/Super_Admin/Home2.php';
        backBtn.onclick = () => window.location.href = '../html/Super_Admin/Home2.php';
        editBtn.onclick = () => window.location.href = '../html/EditSuper_Admin.html?id=';
        break;
      case 'AdminSENA':
        homeBtn.href = '../html/AdminSENA/Home2.php';
        backBtn.onclick = () => window.location.href = '../html/AdminSENA/Home2.php';
        editBtn.onclick = () => window.location.href = '../html/EditAdminSENA.html?id=';
        break;
      case 'empresa':
        homeBtn.href = '../html/Empresa/Home2.php';
        backBtn.onclick = () => window.location.href = '../html/Empresa/Home2.php';
        editBtn.onclick = () => window.location.href = '../html/EditEmpresa.html?id=';
        break;
      default:
        homeBtn.href = 'index.php';
        backBtn.onclick = () => window.location.href = 'index.php';
        editBtn.onclick = () => window.location.href = 'index.php';
        break;
    }

    // Función cerrar sesión
    document.getElementById('cerrar-sesion').addEventListener('click', function(e) {
      e.preventDefault();
      sessionStorage.clear();
      localStorage.clear();
      window.location.href = 'index.php';
    });
  </script>

</body>
</html>
