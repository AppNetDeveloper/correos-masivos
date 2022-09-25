<?php
require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

// Load Composer's autoloader
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

									

$list=$_POST["emaillist"];
$asunto = $_POST["asunto"];
$mesajehtml=$_POST["contenido"];
$email=$_POST["email"];
$user=$_POST["user"];
$smtp=$_POST["smtp"];

$password=$_POST["password"];

$mesajenonhtml= $mesajehtml;


$mail = new PHPMailer(true);

try {
    
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
     $mail->SMTPDebug = false;
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = $smtp;                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = $user;                     // SMTP username
    $mail->Password   = $password;                               // SMTP password
    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
                                        // TCP port to connect to
    $mail->SMTPSecure = false;
    $mail->SMTPAutoTLS = false;
    $mail->Port       = "25";
  
      //Set SMTP Options
      $mail->SMTPAuth = true;
      
  $mail->SMTPOptions = array(
                  'ssl' => array(
                      'verify_peer' => false,
                      'verify_peer_name' => false,
                      'allow_self_signed' => true
                  )
              );
    
    //Recipients
    $mail->setFrom($email, $asunto);


    $addresses = explode(';', $list);
foreach ($addresses as $value) {
    $mail->addAddress($value);
}
    


    // Content
    $mail->isHTML(true);                                  
    // Set email format to HTML
    $mail->Subject = $asunto;
    $mail->Body    = $mesajehtml;
    $mail->AltBody = $mesajenonhtml;

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    
}
 
