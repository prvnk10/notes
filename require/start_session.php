<?php

/*
  start_session.php

  Parveen Khurana
  07/09/2016

 this code starts the session and also initialize session variables using cookie variables, helpful if user closes
 the browser with signing out
*/

session_start();

if(!isset($_SESSION['user_id'])){
   if(isset($_COOKIE['user_id'])){

     $_SESSION['user_id'] = $_COOKIE['user_id'];
     $_SESSION['name'] = $_COOKIE['name'];
     $_SESSION['profile_pic'] = $_COOKIE['profile_pic'];

   }
}

 ?>
