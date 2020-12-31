<?php

require "PHPMailer/PHPMailerAutoload.php";

$mail = new PHPMailer();

$mail->isSMTP();
//$mail->SMTPDebug = 1;
$mail->Host ='smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = "staiifi57@gmail.com";
$mail->Password = "Issam261285";
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom("staiifi57@gmail.com");
$mail->addAddress('issam.boucheraki@gmail.com');

$mail->isHTML(true);

$mail->Subject="Email Test";
$mail->Body= "Afin de valider votre inscription, veuillez cliquez sur ce lien svp !";

if(!$mail->send()) {
    echo "Email non envoyé <br>";
    echo "Erreur: ".$mail->ErrorInfo;
} else {
    echo "Votre email a bien été envoyé";
}