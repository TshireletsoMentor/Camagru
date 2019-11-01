<?php
    $to = "tmentor@student.wethinkcode.co.za";
    $subject = "Camagru: Confirm Email";
    $text = "<a href='http://127.0.0.1:8080/camagru/'>Please click the link below to confirm your account</a>";
    $headers = "From: DoNotReply@camagru.com";
    $mail = mail($to,$subject,$text,$headers);
    if(empty($mail)){
        echo "lol0";
    }
    if($mail){
        echo "<p style='color:green;'> Please check email to confirm email address</p>";
    }else{
        echo "<p style='color:red;'> Email not Sent</p>";
    }
?>