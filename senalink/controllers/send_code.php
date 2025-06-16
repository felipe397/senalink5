<?php
error_log("send_code.php recibido petición POST");
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Asegúrate de que PHPMailer esté instalado y autoload configurado

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? null;

    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Correo inválido']);
        exit;
    }

    // Generar código de 6 cifras
    $code = random_int(100000, 999999);

    // Guardar código y email en sesión para validar después
    $_SESSION['reset_email'] = $email;
    $_SESSION['reset_code'] = $code;

    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.tu-servidor.com'; // Cambia esto por tu servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'tu-email@dominio.com'; // Cambia por tu usuario SMTP
        $mail->Password = 'tu-contraseña'; // Cambia por tu contraseña SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // Cambia si usas otro puerto

        // Destinatarios
        $mail->setFrom('no-reply@senalink.com', 'SenaLink');
        $mail->addAddress($email);

        // Contenido
        $mail->isHTML(false);
        $mail->Subject = 'Código de recuperación de contraseña';
        $mail->Body    = "Su código de recuperación es: $code";

        // Habilitar debug para diagnóstico
        $mail->SMTPDebug = 2; // Muestra salida detallada
        $mail->Debugoutput = function($str, $level) {
            error_log("SMTP Debug level $level; message: $str");
        };

        $mail->send();
        echo json_encode(['success' => true, 'message' => 'Código enviado al correo']);
    } catch (Exception $e) {
        error_log("Error al enviar correo: {$mail->ErrorInfo}");
        echo json_encode(['success' => false, 'message' => 'Error al enviar el correo']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
