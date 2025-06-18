<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/OAuthTokenProvider.php';
require 'PHPMailer/src/OAuth.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail($mail_cc, $pass_email, $name, $toEmail, $subject, $body)
{
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $mail_cc;
        $mail->Password = $pass_email;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($mail_cc, $name);
        $mail->addAddress($toEmail);
        $mail->addCC($mail_cc);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return "Message has been sent";
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
    }
}
