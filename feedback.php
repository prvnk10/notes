<?php

# include test_input file to check for the user input
require_once('require/functions.php');
render("header", ["title" => "Feedback | Notes"]);

echo "</div>";

# include connection files
require_once('require/connection.php');

# initialize nameErr and emailErr to be empty strings
$nameErr = $emailErr = '' ;

/* if the form is submitted, then extract the POST, grab the variables
   test the input variables, check if the variables fulfill to the regex's
   if everything fine upto this level, then insert the record into the db
*/
if(isset($_POST['submit'])){
  extract($_POST);

  $name = test_input($user_name);
  $email = test_input($email);
  $feedback = test_input($user_feedback);

if(empty($name)){
  $nameErr = "Please fill out name";
  $show_form = true;
}
 else {
   $name = test_input($name);
     if(!preg_match("/^[a-zA-Z ]*$/" ,$name)){
       $nameErr = "Only letters and white spaces are allowed";
       $show_form = true;
    }
}

if(empty($email)){
   $emailErr = "Please fill out email";
   $show_form = true;
}
 else {
    $email = test_input($email);
     if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $emailErr = "Invalid email format";
      $show_form = true;
  }
}


if(!empty($name) && !empty($email) && !empty($feedback) && !($show_form)){


 $query = "INSERT INTO feedback VALUES('' , '$name' , '$email', '$feedback')" ;

 if($conn->query($query))
   $feedback_result = "Feedback submitted successfully";

//show feedback_result for a few seconds and then hide it using jquery
 }
}
 ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

  <p class="feedback_result"> <?php if(!empty($feedback_result)) echo $feedback_result; ?> </p>

  <label for="user_name"> Name: </label>
  <input type="text" name="name" id="user_name">
  <span class="error"> <?php if(!empty($nameErr)) echo $nameErr; ?> </span> <br/>

  <label for="email"> Email: </label>
  <input type="email" name="email" id="email">
  <span class="error"> <?php if(!empty($emailErr)) echo $emailErr; ?> </span>  <br/>

  <label for="user_feedback"> Feedback: </label>
  <textarea cols="30" rows="8" name="user_feedback" id="user_feedback"> </textarea> <br />

  <input type="submit" name="submit" value="Submit">
</form>
