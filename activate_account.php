<?php
require_once('require/connection.php');
# require_once('require/helpers.php');

require_once('require/functions.php');
render("header", ["title"=> "Account Activation | NOTES"]);

echo "</div>";    # this closes the div(in header.php) whose class is page-header
# grab email and pw from activation link

$email = $_GET['email'];
$a_code = $_GET['a_code'];
$time = $_GET['t'];
$cur_time = time();

/*
echo $time."<br />";
echo $cur_time."<br />";
echo $cur_time - $time ;
*/

/*
$check = "SELECT activation_code FROM users WHERE email='$email' ";

$check_result = $conn->query($check);
 $rows = $check_result->fetch_assoc();
 $activation_code = $rows['activation_code'];

 if($activation_code == NULL)
  header('Refresh:1 , url=index.php');

*/

# checks if the current time is less than the time at which link was generated + 86400 seconds
if($cur_time < ($time+86400) ){

// update the db, activates the account show user a msg and redirect to index.php if the email and a_code combination is valid
$query = "UPDATE users SET activation_code = 'NULL' WHERE email = '$email' AND activation_code = '$a_code' LIMIT 1";

 if($conn->query($query)){
   # echo "<div id='verified'> Account verified successfully </div>";
   echo "<div class='alert alert-success'> Account verified successfully </div>";
   header("Refresh: 5 , url=index.php ");
   # login link must be shown here or not?
 }

} else {
   echo "<div class='alert alert-danger'> This link has expired. </div>";               # show them a button to get another activation link(also update db with new a_code of user clicks on this link)
   # echo "<a href='#'> <button class='activate_account_btn'> Click here to get another activation link </button>  </a> ";
   echo "<a href='#' class='btn btn-info' role='button'> Get another link </a>";
   # send them an email for account activation
  }

?>
