<?php

require_once('connection.php');
require_once('functions.php');
require_once('start_session.php');
require_once('redirect_invalid_user.php');

#   initialise $show_form to false and $passwordErr to an empty string
#   $show_form will show form for verifying password

$show_form = $_SESSION['return'] = false;
$passwordErr = '' ;
# echo var_dump($_SESSION['return']);
$url = '/pk5.0/' ;
echo $url .= $_GET['u'];


if(isset($_POST['submit'])){

 extract($_POST);

 $password = test_input($password);
 $user_id = $_SESSION['user_id'];
 $user_name = $_SESSION['name'];

 if(empty($password)){
    $passwordErr = "Please fill out this field";
    $show_form = true;
    exit();
 } else {

    $password = sha1($password);
    $search = "SELECT* FROM users WHERE user_id = '$user_id' AND password = '$password' LIMIT 1 ";
    $result = $conn->query($search);

# echo $result->num_rows ;
    if($result->num_rows == 1){                     # means password is correct

     $show_form = false;
     $_SESSION['user_verified'] = true;

     header("Location: ". $url);        # here reload the file which calls this script

   } else {
       $passwordErr = "incorrect password";
       $show_form = true;
     }
   }       # this closes the block which gets executed when the password is not empty

}         # this curly brace closes the block whether the form's submitted or not
 else
    $show_form = true;

if($show_form){
?>

<form method="post" class="form-horizontal">
  <div class="form-group">
   <label class="control-label col-sm-3" for="password"> Please enter your password: </label>
   <div class="col-sm-5">
   <input class="form-control" type="password" name="password" onblur="validateNonEmpty(this, document.getElementById('passwordErr'))" required="required" />
   <span id="passwordErr" class="help-block error"> <?php echo $passwordErr; ?> </span>
   </div>
   <div class="col-sm-4"> </div>
  </div>

  <div class="form-group">
   <div class="col-sm-offset-3 col-sm-9">
   <button class="btn btn-info"  name="submit" /> Continue </button>
   </div>
  </div>

</form>

<?php
}
?>
