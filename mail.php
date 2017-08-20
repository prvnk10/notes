<?php

$to = "kprvn10@gmail.com";
$subject = "Merry Christmas";

$message = "Hey you";

$retval = mail($to, $subject, $message);

if($retval == true)
  echo "mail sent successfully";
else {
  echo "message could not be sent";
}

?>
