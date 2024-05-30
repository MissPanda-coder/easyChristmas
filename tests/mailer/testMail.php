<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Configurations SMTP
    $mail->isSMTP();
    $mail->Host       = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth   = true;
    $mail->Username   = '3de76f35955204';  // Votre username Mailtrap
    $mail->Password   = '7b78b623719e8d';  // Votre mot de passe Mailtrap

    // Test avec STARTTLS
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 2525;
    $mail->SMTPAutoTLS = false;

    echo "Testing with STARTTLS...\n";
    $mail->send();
    echo 'Message envoyé avec STARTTLS';

} catch (Exception $e) {
    echo "Le message n'a pas pu être envoyé avec STARTTLS. Erreur de Mailer : {$mail->ErrorInfo}\n";
}

try {
    $mail = new PHPMailer(true);

    // Configurations SMTP
    $mail->isSMTP();
    $mail->Host       = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'aab54592199f55';  // Votre username Mailtrap
    $mail->Password   = 'cb56cdbdbcd3db';  // Votre mot de passe Mailtrap

    // Test avec SMTPS
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;


// Destinataires
$mail->setFrom('contact@easychristmas.fr', 'Mailer');
$mail->addAddress('adeline@gmail.com', 'Adeline');

// Contenu de l'email
$mail->isHTML(true);
$mail->Subject = 'Demande de contact';
$mail->Body    = 'Bonjour, ceci est un test.';

    echo "Testing with SMTPS...\n";
    $mail->send();
    echo 'Message envoyé avec SMTPS';

} catch (Exception $e) {
    echo "Le message n'a pas pu être envoyé avec SMTPS. Erreur de Mailer : {$mail->ErrorInfo}\n";
}
