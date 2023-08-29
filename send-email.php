<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$first_name = htmlspecialchars($_POST['firstname']);
$last_name = htmlspecialchars($_POST['lastname']);
$name = "$first_name $last_name";
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$subject = htmlspecialchars($_POST['subject']);
$message = htmlspecialchars($_POST['message']);

// var_dump($name, $email, $subject, $message);
// die();

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug  = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = $_ENV['EMAIL_HOST'];                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication                             //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
    $mail->Port       = 2525;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->Username   = $_ENV['EMAIL_USERNAME'];                     //SMTP username
    $mail->Password   = $_ENV['EMAIL_PASSWORD'];
    $mail->setFrom($_ENV['EMAIL_SENDER'], 'Admin');
    $mail->addAddress($email, $name);     //Add a recipient
    $mail->addReplyTo($_ENV['EMAIL_SENDER'], 'Information');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = $message;

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}