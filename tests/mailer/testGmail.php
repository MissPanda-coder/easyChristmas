<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Configurations SMTP
    $mail->SMTPDebug = 2; // Niveau de débogage
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'adeline.aiguier@gmail.com';  // Votre adresse Gmail
    $mail->Password   = 'jblg kkqz hjbq dnnl';  // Votre mot de passe Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Destinataires
    $mail->setFrom('adeline.aiguier@gmail.com', 'Mailer');
    $mail->addAddress('adeliine@hotmail.com', 'Destinataire');

    // Contenu de l'email
    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body    = 'Bonjour, ceci est un test.';

    $mail->send();
    echo 'Message envoyé avec Gmail SMTP';
} catch (Exception $e) {
    echo "Le message n'a pas pu être envoyé. Erreur de Mailer : {$mail->ErrorInfo}";
}