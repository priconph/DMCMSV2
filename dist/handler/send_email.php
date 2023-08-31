<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '.\PHPMailer\src\Exception.php';
require '.\PHPMailer\src\PHPMailer.php';
require '.\PHPMailer\src\SMTP.php';

$mail = new PHPMailer(true)

try {
    //Server settings                                    // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = 'smtp.googlemail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'dmcmsv2@gmail.com';                     // SMTP username
    $mail->Password   = 'Pr1c0N123*';                               // SMTP password
    $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('dmcmsv2@gmail.com', 'DMCMS V2 System Notification');
    $mail->addAddress('aaleoncio@pricon.ph');     // Add a recipient

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Device (device_name_here) has reached 80% accumulated shots';
    $mail->Body    = 'This is to inform you that device <b style="color:red">(device name here)</b> has reached <b>80%</b> accumulated shots. <br><br>For more info, please log-in to your Rapid account. Go to http://rapid/ and DMCMSV2 under My Systems.<br><br><br>Notice of Disclaimer: This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}