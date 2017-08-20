<?php
require_once('require/start_session.php');

# if user's already logged in, then redirect user to home page
if(isset($_SESSION['user_id'], $_SESSION['name']))
 header("Location: index.php");

require_once('require/functions.php');
render("header" , ["title" => "Log In | Notes"]);
# render function must be separated or its right there in its place

echo "</div>";     # this closes the div(in header.php) whose clase is page-header

# initialising different variables needed in the script
$show_form = false;
$login_result = $emailErr = $passwordErr = '' ;

if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST["submit"])){

   extract($_POST);

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
      $passwordErr = "Please fill out the password";
      $show_form = true;
   }

   if( (!empty($email)) && (!empty($password)) && (!$show_form)){
     $password = sha1($password);

/*
   query written below queries the database where email value is equal to the
   entered email and store it in the $search variable in form of array.
*/
    require_once("require/connection.php");
    $search = " SELECT * FROM users WHERE email = '$email' AND password = '$password' ";
    $result = $conn->query($search);


/* we check if for entered values only one row is affected then login is OK
   else we show an error message and login form
   if login is OK, then we fetch the query results and store it in $row
   then we grab the user_id and store it in the corresponding session variable
   and also set the cookie and then redirect the user to home page

*/

    if($result->num_rows == 1){
      $query = "UPDATE users SET last_login = NOW() WHERE email = '$email' " ;      // query which updates the last_login of the user
      $update_last_login = $conn->query($query);                                     // executing the above query

      if($update_last_login){                                                        // check if the above query actually got executed or not

         $row = $result->fetch_assoc();

         $_SESSION['user_id'] = $row['user_id'];
         $_SESSION['name'] = $row['name'];
         $_SESSION['profile_pic'] = $row['profile_pic'];

         setcookie('user_id' , $row['user_id'] , time() + 24*60*60);
         setcookie('name' , $row['name'], time() + 24*60*60);
         setcookie('profile_pic' , $row['profile_pic'], time() + 24*60*60);

         header("Location: index.php");
         $conn->close();
      }  else { echo "There's some problem logging you in. We are sorry for the inconvience"; }

    } else{
           $login_result = "Invalid credentials" ;
           $login_result .= '<a href="forgot_password.php"> Forgot Password </a>' ;
           $show_form = true;
          }

      }    #this curly brace ends the block that executes when none of the fields is empty and $show_form is false

    }     #this curly brace ends the block that executes only when the form is submitted
 }
  else {
   $show_form = true;
}

if($show_form){
?>

<!--  </div>  this div closes the header block, as nav.php is not included in this file so we have to close the header div explicitly  -->

<!-- main content of this page starts from here -->
<!-- <div id="content-wrapper">


<fieldset class="login_table">
  <legend> Log In </legend>  -->


<fieldset> <strong> Login </strong> </fieldset>


<form method="post" class="form-horizontal">

  <div class="form-group">
   <label class="control-label col-sm-3" for="email"> Email: </label>
   <div class="col-sm-5">
   <input type="email" name="email" id="email" class="form-control" onblur="checkEmail(this.value)" required="required" />
   <span id="emailErr" class="help-block error"> <?php echo $emailErr; ?> </span>
   </div>
   <div class="col-sm-4"> </div>
 </div>

  <div class="form-group">
   <label class="control-label col-sm-3" for="password"> Password: </label>
   <div class="col-sm-5">
   <input type="password" name="password" class="form-control" required="required" />
   <span id="passwordErr" class="help-block error"> <?= $passwordErr ?> </span>
   </div>
   <div class="col-sm-3"> </div>
  </div>

    <div class="error">
       <?php echo $login_result; ?>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-9">
      <button type="submit" id="login_btn" name="submit" class="btn btn-info" />
      Log In
      </button>
      </div>
    </div>

</form>
</fieldset>

<?php
 }
 ?>

<?php render("footer"); ?>

<script src="login.js"> </script>
