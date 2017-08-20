<?php

// defining the db_host, db_user and other db variables(for connecting to the database) as constants so that no one can change them
define("servername" , "localhost");
define("username", "root");
define("password", "");
define("db_name", "notes_user");
define("PATH" , "uploads/images/");
define("PATHF" , "uploads/pdfs/");

// connecting to the db server
$conn = new mysqli(servername , username, password , db_name);
if($conn->connect_error){
  echo "Could not connect".$conn->connect_error or die("Error connecting to the database");
  exit();
}

?>
