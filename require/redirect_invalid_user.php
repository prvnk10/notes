<?php

# if the user is not logged in or not allowed to access a particular page without logging in, then redirect to login page

if(!isset($_SESSION['user_id'], $_SESSION['name'])){
  header("Location: /pk5.0/login.php");
  exit();
}

 ?>
