<?php
# session started so that we can access session variables
require_once('require/start_session.php');

# require file which connects our webapp to db
require_once('require/connection.php');

# including helpers.php file so that we can include header and footer file
require_once 'require/functions.php';
render('header', ["title" => "NOTES"]);

# requiring navigation menu
 require_once 'require/nav.php';
?>


<style>
  .bg-1 { color: #ffffff; color: #555555;}

  .col-md-4 { width: 350px; height: 300px; }
</style>

<div class="container-fluid bg-1 text-center">
  <div class="row">

   <div class="col-md-4">
    <p> hello world </p>
    <img src="1.jpg" alt="upload_msg" width="350" height="300">
   </div>

   <div class="col-md-4">
     <p> hello india </p>
     <img src="1.jpg" alt="upload_msg" width="350" height="300">
   </div>

   <div class="col-md-4">
     <p> dsijf </p>
     <img src="1.jpg" alt="upload_msg" width="350" height="300">
   </div>

 </div>
