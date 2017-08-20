<!-- navigation menu for the site is defined here inside its own div

 <div id="nav">
 <div class="login_header">
-->

<!-- <ul class="list-group">  -->

<div class="list-group">
<?php
# we don't start the session here as this file is included from index file where session is already started
# check if the session variables exist or not and show nav menu depending upon that

if(!isset($_SESSION['user_id'], $_SESSION['name'])){
?>

<!--
  <li class="list-group-item"> <a href="./login.php"> Log In </a> </li>
  <li class="list-group-item"> <a href="./signup.php"> Register </a> </li>
-->

<a href="./login.php"  class="btn btn-info list-group-item-info" role="button"> Log In </a>
<a href="./signup.php" class="btn btn-info list-group-item-info" role="button"> Sign Up </a>

<?php
} else {

?>

<!--
# echo '<img src="'. PATHF $_SESSION['profile_pic'] . '" alt= "' . $_SESSION['name'] . '" >';
# echo '<div class="my_account">' ;
-->

<div class="dropdown">

<button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">
<?php echo $_SESSION["name"]; ?>
<span class="caret"> </span> </button>

<ul class="dropdown-menu">
 <li> <a href="./upload.php"> Uploads </a> </li>
 <li> <a href="./edit_profile.php"> Update Profile </a> </li>
 <li> <a href="./logout.php"> Log Out </a> </li>
</ul>

</div>

<?php
}
?>

  </div>            <!-- this closes the div whose class list-group -->
 </div>            <!-- this closes the div(in header.php) whose class is page-header -->
</div>             <!-- this closes the div(in header.php) whose class is container -->

<!-- dropdown is not working -->
