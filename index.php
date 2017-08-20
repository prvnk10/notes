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


<!-- <div id="content_wrapper"> -->

<div class="container">

<!-- for showing search form -->

<!-- <div id="search_form"> -->

<!-- form-horizontal class is added to the form element to make the form apppear as horizontal -->
<form action="" method="get" class="form-horizontal">
 <div class="form-group">

  <label for="user_search" class="control-label"> </label> <br/>
  <div class="col-sm-8">
  <input type="text" name="q" id="user_search" class="form-control" placeholder="ex. php">
  </div>

  <!--   <input type="submit" name="search" class="search_db" value="Search"> <br />    -->

  <div class="col-sm-4">
  <button type="submit" class="btn btn-default col-sm-4" name="search"> Search </button>
  </div>

 </div>
</form>


<!-- division where searched items or recently uploaded files will be shown -->
<div id="content_body">

<?php

# links only for those users with their account verified(activated) ?

$user_search = '' ;

# if the user entered some words to search, then this block gets executed
 if(isset($_GET['q']) && $_GET['q'] != ''){
  $user_search = $_GET['q'];

  $search_query =  build_query($user_search);
  # echo $search_query;

  # set $cur_page based on whether the page parameter is set or not
  $cur_page = isset($_GET['page']) ? $_GET['page'] : 1;

  // number of results per page
  $results_per_page = 5;

  $skip = (($cur_page - 1) * $results_per_page);

  $temp = $conn->query($search_query);
  $total = $temp ->num_rows ;

  $num_pages = ceil($total / $results_per_page);

  $search_query = $search_query . " ORDER BY no_of_clicks DESC LIMIT $skip, $results_per_page  " ;
  # echo $search_query;
  $result = $conn->query($search_query);

  if($result->num_rows > 0){

   echo '<ul class="list-group">';
   while($rows = $result->fetch_assoc() ) {

    # $path_of_uploaded_file = PATHF ;
    $name_of_uploaded_file = $rows['uploaded_content'];
    $upload_id = $rows['upload_id'];

    # show each of the file in its div whose class is uc_row
    # echo "<div class='uc_row'>";

    # if user's logged in, then show the links otherwise only show the file name
    if( isset($_SESSION['user_id'] , $_SESSION['name'] ) ){

     echo '<li class="list-group-item">';
     echo '<a href = "' . PATHF .$name_of_uploaded_file . ' " onclick="update_no_of_views(' . $upload_id .')" > ' ;             # hide the folder names
     echo  $rows['uploaded_content']."</a>" ;
     echo "<div id= {$upload_id} > <span class='badge'> "  . $rows['no_of_clicks'] . " </span> </div> <br />" ;
     echo "<span> <h5>  uploaded by: </h5>" . $rows['name'] . "</span>  <br />";
     echo "</li>";
     echo '<br />';
    } else {
        echo  '<li class="list-group-item">' . $name_of_uploaded_file ;
        echo "<p> <h5>  uploaded by: </h5>" . $rows['name'] . "</p>  <br />";
        echo '</li>';
        echo " <br />" ;
      }

    # div whose class is uc_row ends here
    # echo "</div>";

  }                                       # while loop ends here

} else {                                  # cross check this condition(of if-else block) again
         echo "No match found";
        }

# if the number of pages are greater than 1, then use pagination

  if ($num_pages > 1) {
   echo "<ul id='pagination' class='pagination'>";
  # echo '<li>' . generate_page_links($user_search, $cur_page, $num_pages) . '</li>';
   echo  generate_page_links($user_search, $cur_page, $num_pages);
   echo "</ul>";
  }

} else {

    # this else block gets executed if the 'q' is not set or it is empty (by default when the page loads)

  $search_query = build_query('');
  $search_query .= " ORDER BY no_of_clicks DESC,  date DESC LIMIT 10 ";
  # echo $search_query;
  $temp = $conn->query($search_query);                   # later on change this query here to show results using order
                                                         #    by clause wrt date_uploaded column
   if($temp->num_rows >0){
     echo "<div class='recently_uploaded '>";
     # why this div automatically ends here

     echo "<h3> Recently uploaded files </h3>" ;

       echo "<ul class='list-group'>";
       while($rows = $temp->fetch_assoc()){

        $name_of_uploaded_file = $rows['uploaded_content'];
        $upload_id = $rows['upload_id'];

      #  echo "<div class='uc_row'>";

        if(isset($_SESSION['user_id']) && isset($_SESSION['name'])){
         echo '<li class="list-group-item">';
         echo '<a href = "' . PATHF .$name_of_uploaded_file . ' " onclick="update_no_of_views(' . $upload_id .')" > ' ;             # hide the folder names
         echo  $rows['uploaded_content']."</a> <p />" ;
         echo " <div id= {$upload_id} > <span> Number of views: "  . $rows['no_of_clicks'] . "</span>"  ;
         echo "<span id='user_uploaded_file'> " . $rows['name'] . "</span> </div> <br />";
         echo '</li>';
         echo '<br />';

        } else {
           echo '<li class="list-group-item">';
           echo  $name_of_uploaded_file ;
           echo "<div class='user_name'> <h5>  uploaded by: </h5>" . $rows['name'] . "</div>  ";
           echo '</li>';
           echo '<br/>';
        }

    #    echo "</div>";                # div whose class is uc_row ends here

      }                  # while loop ends here
       echo '</ul>';
       echo "</div>" ;                         # div for uploaded files end here

   }


}   # else block which gets executed when either 'q' is not set or is equal to an empty string ends here

# close the connection (where to put this statement? )
$conn->close();
?>

 </div>                        <!-- this closes div whose id is content-body -->
<!-- </div>                     -->     <!-- this closes div whose id is content-wrapper -->

<?php
render('footer');
?>

<script src="update.js"> </script>
