<?php
$to = 'rominus.27000@gmail.com';
$subject = 'Test Mail';
$message = 'Ceci est un message de test.';
$headers = 'From: webmaster@example.com' . "\r\n" .
            'Reply-To: webmaster@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

if(mail($to, $subject, $message, $headers)) {
    echo "Test mail sent";
} else {
    echo "Mail failed";
}
