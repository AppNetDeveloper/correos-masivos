<?php
require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

// Load Composer's autoloader
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Obtener los datos del formulario
$list = $_POST["emaillist"] ?? '';
$asunto = $_POST["asunto"] ?? '';
$mensaje_html = $_POST["contenido"] ?? '';
$email = $_POST["email"] ?? '';
$user = $_POST["user"] ?? '';
$smtp = $_POST["smtp"] ?? '';
$password = $_POST["password"] ?? '';

// Verificar que los campos necesarios están llenos
if (empty($list) || empty($asunto) || empty($mensaje_html) || empty($email) || empty($user) || empty($smtp) || empty($password)) {
    die('Falta completar alguno de los campos necesarios');
}

// Verificar que la lista de correos electrónicos tiene un formato válido
if (!filter_var($list, FILTER_VALIDATE_EMAIL) && !preg_match('/^[\w\.\-\+\@]+\;[\w\.\-\+\@]+(\;[\w\.\-\+\@]+)*$/', $list)) {
    die('La lista de correos electrónicos tiene un formato inválido');
}

// Crear un objeto PHPMailer
$mail = new PHPMailer(true);

try {
    // Configurar el objeto PHPMailer
    $mail->isSMTP();
    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->Host = $smtp;
    $mail->SMTPAuth = true;
    $mail->Username = $user;
    $mail->Password = $password;
    $mail->SMTPSecure = false;
    $mail->SMTPAutoTLS = false;
    $mail->Port = 25;
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ],
    ];
    
    // Agregar los destinatarios
    $addresses = explode(';', $list);
    foreach ($addresses as $value) {
        if (filter_var(trim($value), FILTER_VALIDATE_EMAIL)) {
            $mail->addAddress(trim($value));
        }
    }
    
    // Configurar el contenido del mensaje
    $mail->setFrom($email, $asunto);
    $mail->isHTML(true);
    $mail->Subject = $asunto;

    // Enviar el correo electrónico
    $mail->Body = $mensaje_html;
    if(!$mail->send()) {
        // Si el correo no se envía con el puerto 25, intenta enviarlo con el puerto 465
        $mail->Port = 465;
        if(!$mail->send()) {
            throw new Exception('Error al enviar el correo electrónico: ' . $mail->ErrorInfo);
        }
    }

    echo 'Correo enviado correctamente';

} catch (Exception $e) {
    echo 'Error al enviar el correo electrónico: ' . $e->getMessage();
}
