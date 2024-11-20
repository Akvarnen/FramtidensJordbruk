<?php

require 'PHPMailerAutoload.php';
$mail = new PHPMailer();

$mail->IsSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->Port = 465;
$mail->SMTPAuth = true;
$mail->Username = GMAIL_APP_USERNAME;
$mail->Password = GMAIL_APP_PASSWORD;
$mail->SMTPSecure = 'ssl';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $from = $_POST["from"];
    $to = "agnes@kvarnsbacke.se";
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    $modifiedMessage = "Observera! Detta meddelande är skickat från kontaktformuläret på 'HÅLLBART JORDBRUK I VÅR DIGITALA TIDSÅLDER'.";
    $message = "Detta mejl är skickat från: " . $from . "\n\n" . $message . "\n\n" . $modifiedMessage;

    $mail->setFrom($from);
    $mail->addAddress($to);
    $mail->Subject = "$subject";
    $mail->Body = "$message";

    if ($mail->send()) {
        $note = "Ditt epost-meddelande har skickats!";
    } else {
        $note = "Något gick fel: " . $mail->ErrorInfo;
    }

    header('Location: about.html?message=' . urlencode($note));
    exit;
}
