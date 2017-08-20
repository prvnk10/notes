<?php
require_once('require/start_session.php');

# if user's already logged in, then redirect user to home page
if(isset($_SESSION['user_id'], $_SESSION['name']))
 header("Location: index.php");

require_once("require/functions.php");
 render("header" , ["title" => "Sign Up | Notes"]);

require_once('require/connection.php');

echo "</div>";                  # this closes the div(in header.php) whose clase is page-header

# initialising different variables needed in the script
$show_form = false;
$nameErr = $emailErr = $passwordErr = $confirm_PasswordErr = $user_PictureErr = '' ;

# check if the form is submitted or not
if($_SERVER["REQUEST_METHOD"] == "POST") {
 if(isset($_POST['submit'])){
/*
    firstly we extract the $_POST
     then we test the variables
     then it is checked whether the field is empty or not, if yes show corresponding error message
     value of password and confirm_Password fields are matched
     if everything is fine then we use connection.php
     get properties of uploaded file
     check specifications of uploaded file
      if file specifications are within constraints, then we query the database using value of enterd email in WHERE clause
      if 0 rows are affected, it means email is unique
      insert values into db and move uploaded file
*/
   extract($_POST);

   if(empty($name)){
     $nameErr = "Please fill out name";
     $show_form = true;
   } else {
     $name = test_input($name);
      if(!preg_match("/^[a-zA-Z ]*$/" ,$name)){                      # make sure only alphabets are allowed in name
       $nameErr = "Only letters and white spaces are allowed";
       $show_form = true;
      }
   }

  if(empty($email)){
     $emailErr = "Please fill out email";
     $show_form = true;
  } else {
     $email = test_input($email);
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
       $emailErr = "Invalid email format";
       $show_form = true;
      }
  }

  if(empty($password)){
     $passwordErr = "Please fill out this field" ;
     $show_form = true;
  }

  if(empty($confirm_Password)){
     $confirm_PasswordErr = "Please fill out this field" ;
     $show_form = true;
  }

  if( $password !== $confirm_Password){
     $passwordErr = "Passwords do not match" ;
     $confirm_PasswordErr = "Passwords do not match";
     $show_form = true;
  }

  /*    this block is not working     && (!empty($user_Picture))
  if(empty($user_Picture)){
     $user_PictureErr = "Please select a pic as your profile picture" ;
     $show_form = true;
  }
  */

  if( (!empty($name)) && (!empty($email)) && (!empty($password)) && (!empty($confirm_Password)) && ($password === $confirm_Password)  && (!$show_form) ){

   $type = $_FILES['user_Picture']['type'];
   $size = $_FILES['user_Picture']['size'];
   $pic_name = $_FILES['user_Picture']['name'];
   $target = PATH . $pic_name ;                           # make sure image files are not overwritten(uniqueness of images)

   if(($size > 0) && ($size < 5242880)){                # max. size is set to be 5 mb
    if( ($type == 'image/jpeg') || ($type == 'image/png') || ($type == 'image/gif') || ($type == 'image/pjpeg') ){

     $query = "SELECT * FROM users WHERE email = '$email' ";
     $result = $conn->query($query);

     if($result->num_rows == 0){                     # make sure that entered email is not registered already
      if(move_uploaded_file($_FILES['user_Picture']['tmp_name'] , $target) ){
       $password = sha1($password);
       $a_code = substr(md5(uniqid(rand(), true)) , 10, 15);       # generate activation code
       $time = time();
#      $hash = md5($email . $time . "pk");

       $insert = "INSERT INTO users(name, email, password, join_date, profile_pic, activation_code) VALUES ('$name', '$email', '$password', NOW() , '$pic_name', '$a_code' )" ;

       if($conn->query($insert) === TRUE){

        echo "Signed up successfully. You can now <a href='login.php'> login </a> to your account";
        echo " You need to activate your account to get access to all features. We have sent you an email, please click on the link in the email to activate your account";
        echo '<a href="activate_account.php?email=' . $email . '&a_code=' . $a_code . '&t='.$time.'"> Activate your account </a> ';
  #     send an email to the user
  #     show hashed form of diff. parameters in the activation link 
  #     echo '<a href="activate_account.php?$hash=" . $hash . '"> Activate </a> ';

       } else { @unlink($_FILES['user_Picture']['tmp_name']); echo "some error connecting to the database" ; }

      } else { echo @unlink($_FILES['user_Picture']['tmp_name']); "there was some error uploading your profile pic"; }

     } else { $emailErr =  "Entered email is already registered"; $show_form = true; }

    } else { $user_PictureErr =  'only images are allowed' ;  $show_form = true; }

   } else { $user_PictureErr =  "images under 5mb are allowed";  $show_form = true;  }

  }                  # this brace closes the if block which gets executed when none of the field is empty
 }                   # this brace closes the if block which gets executed if the form is submitted
}

# if the form is not submitted, do this
else
 $show_form = true ;

if( $show_form ){
 ?>

<!-- <div id="content-wrapper">   -->

<fieldset> <strong> Sign Up </strong> </fieldset>
 <!-- <legend> Sign Up </legend>  -->

 <form method="post" enctype="multipart/form-data" class="form-horizontal">
    <!-- <table class="signup_table">  -->

      <div class="form-group">
       <label class="control-label col-sm-3" for="name"> Name: </label>
       <div class="col-sm-5">
       <input class="form-control" type="text" name="name" value="<?php if(!empty($name)) echo $name; ?>" onblur="validateNonEmpty(this , document.getElementById('nameErr'))" required="required" />
       <span id="nameErr" class="help-block error"> <?= $nameErr ?> </span>
       </div>
      </div>

      <div class="form-group">
       <label class="control-label col-sm-3" for="email"> Email: </label>
       <div class="col-sm-5">
       <input class="form-control" type="email" name="email" id="email" value="<?php if(!empty($email)) echo $email; ?>" required="required" />
       <span id="emailErr" class="help-block error"> <?= $emailErr ?> </span>
       </div>
      </div>


      <div class="form-group">
       <label class="control-label col-sm-3" for="password"> Password: </label>
       <div class="col-sm-5">
       <input class="form-control" type="password" id="password" name="password" onblur="validateNonEmpty(this , document.getElementById('passwordErr'))"  required="required" />
       <span id="passwordErr" class="help-block error"> <?= $passwordErr ?> </span>
       </div>
      </div>

      <div class="form-group">
       <label class="control-label col-sm-3" for="confirm_password"> Confirm Password: </label>
       <div class="col-sm-5">
       <input class="form-control" type="password" id="confirm_password" name="confirm_Password" onblur="validateNonEmpty(this , document.getElementById('confirm_PasswordErr') ) " required="required" />
       <span id="confirm_PasswordErr" class="help-block error"> <?= $confirm_PasswordErr ?> </span>
       </div>
      </div>

      <div class="form-group">
       <label class="control-label col-sm-3" for="user_picture"> Profile Picture: </label>
       <div class="col-sm-5">
       <input class="form-control" type="file" name="user_Picture" required="required">
       <span id="user_PictureErr" class="help-block error"> <?= $user_PictureErr ?> </span>
       </div>
      </div>


      <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
        <button type="submit" id="signup_btn" name="submit" class="btn btn-info" />
        Sign up
        </button>
        </div>
      </div>

  </form>


<?php
}

render("footer");

 ?>

 <script src="signup.js"> </script>
  <script src="jquery.js"> </script>
  <script src="validateNonEmpty.js"> </script>
