<?php
session_start();

$timeout_duration = 10; // 15 minutos

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    // Destruir todas las variables de sesión
    $_SESSION = array();

    // Si se usa cookies para la sesión, eliminar la cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finalmente destruir la sesión
    session_destroy();

    header("Location: ../html/index.php?timeout=1");
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../html/index.php");
    exit();
}
?>
