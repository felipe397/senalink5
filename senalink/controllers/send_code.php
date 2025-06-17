<?php
session_start();
date_default_timezone_set('America/Bogota');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
require '../PHPMailer/Exception.php';
require '../config/conexion.php';

header('Content-Type: application/json');

$correo = $_POST['correo'] ?? null;
if (!$correo || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Correo inválido']);
    exit;
}

try {
    $conexion = Conexion::conectar();

    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE correo = :correo");
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $idUsuario = $usuario['id'];

        // Generar token y fecha de expiración (ej: 1 hora)
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Guardar token en tabla de recuperación
        $stmt = $conexion->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$correo, $token, $expires]);

        // Crear enlace
        $enlace = "http://localhost/senalink5/senalink/controllers/reset_password.php?token=$token";

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'senalink05@gmail.com';
            $mail->Password = 'nlbb bkoe opyf vrvj';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('senalink05@gmail.com', 'SenaLink');
            $mail->addAddress($correo);

            $mail->isHTML(true);
            $mail->Subject = 'Restablece tu contraseña';
            $mail->Body = "
                <p>Hola <strong>Usuario</strong>,</p>
                <p>Hemos recibido una solicitud para restablecer tu contraseña.</p>
                <p>Haz clic en el siguiente enlace para continuar:</p>
                <p><a href='$enlace'>Recupera tu contraseña</a></p>
                <p>Este enlace expira en 1 hora. Si no solicitaste el cambio, ignora este mensaje.</p>
                <p>Atentamente,<br>Equipo SenaLink</p>
            ";

            if ($_SERVER['SERVER_NAME'] === 'localhost') {
                $mail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ];
            }

            $mail->send();
            echo json_encode(['success' => true, 'message' => 'Correo de recuperación enviado.']);
        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $mail->ErrorInfo);
            echo json_encode(['success' => false, 'message' => 'Error al enviar el correo: ' . $mail->ErrorInfo]);
        }

    } else {
        echo json_encode(['success' => false, 'message' => 'Correo no registrado.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en el servidor.']);
}
?>
