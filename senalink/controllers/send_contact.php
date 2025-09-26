<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $asunto = $_POST['asunto'] ?? '';
    $mensaje = $_POST['mensaje'] ?? '';

    if (!$nombre || !$correo || !$asunto || !$mensaje) {
        echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios.']);
        exit;
    }

    $mail = new PHPMailer(true);
    try {
        // Desactivar verificación SSL solo para pruebas locales
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
        // Configuración SMTP para Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'senalink2025@gmail.com'; // Correo Gmail de destino y autenticación
        $mail->Password = 'wiwx yxau yzhy hfhp'; // Contraseña de aplicación generada para senalink2025@gmail.com
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('senalink2025@gmail.com', 'SenaLink Contacto');
        $mail->addAddress('senalink2025@gmail.com'); // Destinatario
        $mail->addReplyTo($correo, $nombre);

        $mail->Subject = 'Contacto desde SenaLink: ' . $asunto;
        $mail->Body = "Nombre: $nombre\nCorreo: $correo\nAsunto: $asunto\nMensaje:\n$mensaje";

        $mail->send();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Error al enviar el correo: ' . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
