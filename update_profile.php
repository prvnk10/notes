<?php
require_once('require/start_session.php');

require_once('require/functions.php');
render("header", ["title" => "Update Profile"]);
echo "</div>";                 # this closes the div(in header.php) whose class is page-header

require_once('require/connection.php');

require_once('require/redirect_invalid_user.php');

$show_update_form = false;
$nameErr = $emailErr = $passwordErr = $confirm_PasswordErr = $user_PictureErr = '' ;

# echo var_dump($_SESSION['return']);

if(!($_SESSION['user_verified'])){                            # condition?
  require_once("require/authorize_user.php");
}

else{                                    # means the user identity is verified

 $show_update_form = true;

 if(isset($_POST['update'])){

  extract($_POST);

  $user_id = $_SESSION['user_id'];
  $user_name = $_SESSION['name'];

  $name = test_input($name);
#  $email = test_input($email);
  $password = test_input($password);
  $confirm_Password = test_input($confirm_Password);
  # include for profile picture

  if(empty($name)){
    $nameErr = "Please fill out name";
    $show_update_form = true;
  } else {
      $name = test_input($name);
       if(!preg_match("/^[a-zA-Z ]*$/" ,$name)){
         $nameErr = "Only letters and white spaces are allowed";
         $show_update_form = true;
       }
    }

/*  if(empty($email)){
    $emailErr = "Please fill out email";
    $show_update_form = true;
  } else {
      $email = test_input($email);
       if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
         $emailErr = "Invalid email format";
         $show_update_form = true;
       }
    }  */

  if(empty($password)){
    $passwordErr = "Please fill out the password";
    $show_update_form = true;
  }

  if(empty($confirm_Password)){
    $confirm_PasswordErr = "Please fill out the password";
    $show_update_form = true;
  }

  if($password !== $confirm_Password){
     $passwordErr = "Passwords do not match" ;
     $confirm_PasswordErr = "Passwords do not match";
     $show_update_form = true;
  }

  if( (!empty($name)) && (!empty($password)) && (!$show_update_form) ) {         # && (!empty($email))

   /* $query = "SELECT * FROM signup WHERE email = '$email' ";
      $result = $conn->query($query);

   if($result->num_rows == 0)  */

    $password = sha1($password);
    $update_query = "UPDATE users SET name = '$name' , password = '$password' WHERE user_id = '$user_id' " ;  #  email = '$email'

    if($conn->query($update_query) === TRUE){
       echo "<div class='alert alet-success'> Account updated successfully </div>";
       $show_update_form = false;
       unset($_SESSION['return']);
       $_SESSION['name'] = $name;
       header("Location: index.php");
    } else { echo "there's some error updating your profile"; $show_update_form = true; }
  }
 }
}

if($show_update_form){
?>

<!--
    form which is shown when the $show_update_form is true which is the case only when
    password entered is correct

<link href="index.css" rel="stylesheet" type="text/css" />

 <div id="content">  -->

<div class="alert alert-success"> <h4> You're logged in as <i> (<?php echo $_SESSION['name']; ?>) </i> </h4> </div>

<form class="form-horizontal" method="post" enctype="multipart/form-data">

 <div class="form-group">
  <label class="control-label col-sm-3" for="name"> Name: </label>
  <div class="col-sm-5">
  <input class="form-control" type="text" name="name" value="<?php if(!empty($name)) echo $name; ?>" onblur="validateNonEmpty(this, document.getElementById('nameErr'))" required="required" />
  <span id="nameErr" class="help-block error"> <?= $nameErr ?> </span>
  <dic class="col-sm-4"> </div>
 </div>

<!--
 <div class="form-group">
  <label class="control-label col-sm-3" for="email"> Email: </label>
  <div class="col-sm-5">
  <input class="form-control" type="email" name="email" value="<?php if(!empty($email)) echo $email; ?>" onblur="validateNonEmpty(this, document.getElementById('emailErr'))" required="required" />
  <span id="emailErr" class="help-block error"> <?= $emailErr ?> </span>
  </div>
 </div>
-->

 <div class="form-group">
  <label class="control-label col-sm-3" for="password"> Password: </label>
  <div class="col-sm-5">
  <input class="form-control" type="password" name="password" onblur="validateNonEmpty(this, document.getElementById('passwordErr'))" required="required" />
  <span id="passwordErr" class="help-block error"> <?= $passwordErr ?> </span>
  </div>
 </div>

 <div class="form-group">
   <label class="control-label col-sm-3" for="confirm_password"> Confirm Password: </label>
   <div class="col-sm-5">
   <input class="form-control" type="password" name="confirm_Password" onblur="validateNonEmpty(this, document.getElementById('confirm_PasswordErr'))" required="required" />
   <span id="confirm_PasswordErr" class="help-block error"> <?= $confirm_PasswordErr ?> </span>
   </div>
 </div>

 <div class="form-group">
   <div class="col-sm-offset-3 col-sm-9">
   <button class="btn btn-info" type="submit" name="update" />
   Sign Up
   </button>
   </div>
 </div>

</form>

<?php
// php if block (which gets executed when $show_update_form is true) ends here
 }
?>

</div>

<?php render("footer"); ?>
