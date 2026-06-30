<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function envoyerMail($destinataire, $prenom, $sujet, $message)
{
    $mail = new PHPMailer(true);

    try{

        $mail->isSMTP();

        $mail->Host = 'smtp.gmail.com';

        $mail->SMTPAuth = true;

        $mail->Username = 'vitegourmandoff@gmail.com';

        $mail->Password = 'kkbggvdscpamzvpl';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;

        $mail->CharSet = 'UTF-8';

        $mail->setFrom(
            'vitegourmandoff@gmail.com',
            'Vite & Gourmand'
        );

        $mail->addAddress(
            $destinataire,
            $prenom
        );

        $mail->isHTML(true);

        $mail->Subject = $sujet;

        $mail->Body = $message;

        $mail->send();

        return true;

    }catch(Exception $e){

        return false;

    }
}