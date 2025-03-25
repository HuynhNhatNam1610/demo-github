<?php
    require 'C:/xampp/htdocs/baocao/model/mail/PHPMailer/src/PHPMailer.php';
    require 'C:/xampp/htdocs/baocao/model/mail/PHPMailer/src/SMTP.php';
    require 'C:/xampp/htdocs/baocao/model/mail/PHPMailer/src/Exception.php';
    require 'C:/xampp/htdocs/baocao/model/mail/PHPMailer/src/OAuthTokenProvider.php';
    require 'C:/xampp/htdocs/baocao/model/mail/PHPMailer/src/OAuth.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    function sendMail($toEmail, $subject, $body) {
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'nhatnam161005@gmail.com';
            $mail->Password = 'yvqhroiprluiakmi'; 
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
    
            $mail->setFrom('nhatnam161005@gmail.com', 'Mailer');
            $mail->addAddress($toEmail);
            $mail->addCC('nhatnam161005@gmail.com');
    
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
    
            $mail->send();
            return "Message has been sent";
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
        }
        // try{
        //     $mail -> SMTPDebug =0;
        //     $mail->isSMTP(); 
        //     $mail->Host = 'smtp.gmail.com'; 
        //     $mail->SMTPAuth = true; 
        //     $mail->Username = 'nhatnam161005@gmail.com'; 
        //     $mail->Password = 'yvqhroiprluiakmi';  //zhpu cpwi tixx arsy
        //     $mail->SMTPSecure = 'tls'; 
        //     $mail->Port = 587; 

        //     $mail->setFrom('nhatnam161005@gmail.com', 'Mailer');
        //     $mail->addAddress('quyen2533@gmail.com','JOE User');
        //     // $mail->addAddress('recipient.email@example.com');
        //     // $mail->addReplyTo('your.email@gmail.com', 'Information');
        //     $mail->addCC('nhatnam161005@gmail.com');
        //     // $mail->addBCC('bcc@example.com');

        //     // $mail->addAttachment('/var/tmp/file.tar.gz');
        //     // $mail->addAttachment('/tmp/image.jpg','new.jpg');

        //     $mail->isHTML(true);
        //     $mail->Subject = 'TEST MAIL';
        //     $mail->Body = 'New content test mail';
        //     // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        //     $mail->send();
        //     echo 'Message has been sent';
        // }catch(Exception $e){
        //     echo "Message could not be sent. Mailer Error: ",$mail->ErrorInfo;
        // }
    }
?>