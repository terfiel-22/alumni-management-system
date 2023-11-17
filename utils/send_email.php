<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require "../admin/assets/vendor/PHPMailer/src/Exception.php"; 
    require "../admin/assets/vendor/PHPMailer/src/PHPMailer.php";
    require "../admin/assets/vendor/PHPMailer/src/SMTP.php";

    function send_email($email_receiver,$email_subject,$email_message)
    {
        $mail = new PHPMailer(true);
        $mail->IsSMTP();
        $mail->SMTPAuth = true;  
        $mail->Host = "smtp.gmail.com";

        include "../config.php";
        $mail->Username = $email;
        $mail->Password = $password;

        $mail->SMTPSecure = "ssl";
        $mail->Port = "465";
        $mail->setFrom($email);

        $mail->addAddress($email_receiver);
        $mail->isHTML(true);
        $mail->Subject = $email_subject;
        $mail->Body = $email_message;

        $mail->send();
    }
?>