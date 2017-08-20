<?php

# require_once("require/helpers.php");
require_once("require/start_session.php");
require_once("require/redirect_invalid_user.php");

if(isset($_SESSION['user_id'])){

// unset the session variables
session_unset();

// session variables to an empty array
$_SESSION = array();

// if the cookie is set by the session behind the scenes, then set the delete that cookie
if(isset($_COOKIE[session_name()])){
  setcookie(session_name(), '', time() - 3600 ) ;
}

// if the user id is set in cookie then delete that cookie
if(isset($_COOKIE['user_id'])){
  setcookie('user_id' , '', time()-3600 );
}

$_COOKIE = array();

// destroys the session
session_destroy();

# $home_url = 'http://' . $_SERVER['HTTP_POST'] . dirname($_SERVER['PHP_SELF']) . '/index.php' ;
header('Location: index.php');


}
  
/*
else {
      header("Location: ./login.php");
     }
*/
?>
