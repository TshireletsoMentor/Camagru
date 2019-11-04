<?php

function sendVerification($email, $token, $url){

$subject = "<i>Camagru</i> - Email Verification";

$header = 'MIME-Version: 1.0'."\r\n";
$header .= 'Content-type: text/html; charset=UTF-8'."\r\n";
$header .= 'From: Camagru@DoNotReply.co.za'."\r\n";

$message = '
<html>
    <head>
        <title>'.$subject.'</title>
    </head>
    <body>
        Thanks for signing up to camagru.<br>
        To finalise the sign up process, please click the link below <br>
        <a href="http://'.$url.'/verify.php?token='.$token.'">Verify my email</a><br>
        Alternatively, if the link does not work, paste the url:<br> http://'.$url.'/verify.php?token='.$token.'<br>
        If this email does not concern you, please ignore this email.
    </body>
';

$retval = mail($email, $subject, $message, $header);
if ($retval == true)
    echo "Verification mail has been sent to: ".$email;
else
    echo "Error";
}

$email = 'tmentor@mailinator.com';
$token = 'lol';
$url = 'pathway';
sendVerification($email, $token, $url);

?>