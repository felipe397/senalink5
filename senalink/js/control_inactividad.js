// control_inactividad.js
// Control de cierre de sesión por inactividad según el rol del usuario
// Uso: llamar a iniciarControlInactividad(rol) después de obtener el rol del usuario

let inactividadTimeout;
let inactividadFocoTimeout; // para controlar pestaña en segundo plano
let tiempoMaxInactividad = 600000; // default 10 min
let mensajeInactividad = 'Tu sesión ha finalizado por inactividad.';
let ultimaActividad = Date.now();
let paginaVisible = true;

function definirTiempoInactividad(rol) {
    if (rol === 'empresa') {
        tiempoMaxInactividad = 15 * 60 * 1000; // 15 minutos
        mensajeInactividad = 'Tu sesión de empresa ha finalizado por inactividad.';
    } else if (rol === 'super_admin') {
        tiempoMaxInactividad = 1 * 60 * 1000; // 1 minuto para pruebas
        mensajeInactividad = 'Tu sesión de super_admin ha finalizado por inactividad.';
    } else if (rol === 'AdminSENA') {
        tiempoMaxInactividad = 10 * 60 * 1000; // 10 minutos
        mensajeInactividad = 'Tu sesión de administrador ha finalizado por inactividad.';
    } else {
        tiempoMaxInactividad = 10 * 60 * 1000; // Valor general de 10 minutos
    }
}

function cerrarSesionPorInactividad() {
    // Guardar mensaje en sessionStorage para mostrarlo en index.html
    try {
        sessionStorage.setItem('alertaInactividad', mensajeInactividad);
    } catch (e) {}
    // Mostrar alerta usando showAlert de alert.js
    if (typeof showAlert === 'function') {
        showAlert(mensajeInactividad, 'warning');
    } else {
        alert(mensajeInactividad);
    }
    setTimeout(function() {
        window.location.href = '../html/index.html';
    }, 1500);
    // Aquí podrías limpiar storage/cookies si es necesario
}

function reiniciarTemporizadorInactividad() {
    clearTimeout(inactividadTimeout);
    ultimaActividad = Date.now();
    inactividadTimeout = setTimeout(cerrarSesionPorInactividad, tiempoMaxInactividad);
    // Si la pestaña estaba en segundo plano, limpiamos también ese timeout
    clearTimeout(inactividadFocoTimeout);
}

function manejarOculto() {
    if (document.visibilityState === 'hidden') {
        paginaVisible = false;
        ultimaActividad = Date.now();
        // Iniciar temporizador para cuando la pestaña está fuera de foco
        inactividadFocoTimeout = setTimeout(function() {
            cerrarSesionPorInactividad();
        }, tiempoMaxInactividad);
    } else {
        paginaVisible = true;
        // Si vino después del tiempo, igual cerrar sesión (backup)
        const tiempoFuera = Date.now() - ultimaActividad;
        if (tiempoFuera >= tiempoMaxInactividad) {
            cerrarSesionPorInactividad();
            return;
        }
        // Si vuelve antes, limpia timeout de segundo plano
        clearTimeout(inactividadFocoTimeout);
        reiniciarTemporizadorInactividad();
    }
}

function iniciarControlInactividad(rol) {
    definirTiempoInactividad(rol);
    ['mousemove', 'keydown', 'scroll', 'click'].forEach(function(evt) {
        window.addEventListener(evt, reiniciarTemporizadorInactividad);
    });
    document.addEventListener('visibilitychange', manejarOculto);
    reiniciarTemporizadorInactividad();
}

// Mostrar alerta en index.html si viene de cierre por inactividad
if (
    window.location.pathname.endsWith('index.html') ||
    window.location.pathname.endsWith('/index.html')
) {
    const alerta = sessionStorage.getItem('alertaInactividad');
    if (alerta && typeof showAlert === 'function') {
        showAlert(alerta, 'warning');
        sessionStorage.removeItem('alertaInactividad');
    } else if (alerta) {
        alert(alerta);
        sessionStorage.removeItem('alertaInactividad');
    }
}
