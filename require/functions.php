<?php

# defining a function named test_input which takes an argument and remove special entities from the argument,trims any white space
#   remove slashes and returns the value

function test_input($data){
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
 }

 # defining a function render which takes two arguments(a template and an associative array)
 function render($template, $data=[]) {
   $path = __DIR__ . "/../templates/" . $template . ".php" ;

   // checking if the file exists, we extract the $data arrray and require the $path
   if(file_exists($path)) {
     extract($data);
     require_once($path);
   }
 }

# build query function is defined which takes an argument given by user to search for a particular keyword
function build_query($user_search){

$search_query = "SELECT uc.uploaded_content, uc.no_of_clicks, uc.upload_id, su.name FROM upload_content AS uc INNER JOIN signup AS su USING(user_id) ";

$clean_user_search = str_replace(',' , '', $user_search);        # removing commas from the user's input

$search_words = explode(' ' , $clean_user_search);            # breaking the string(user's input) into substrings based on
                                                              # comma delimiter
$final_search_words = array();

if(count($search_words) > 0){                               # sanity check to be sure that the user has provided input
   foreach ($search_words as $word) {
        if(!empty($word)){
           $final_search_words[] = $word;
        }
   }
}

$where_list = array();

if(count($final_search_words) > 0){
  foreach($final_search_words as $word){
     $where_list[] = "uploaded_content like '%$word%' ";
   }
}

$where_clause = implode(' AND ', $where_list);           # which logical operator must be used here?

if(!empty($where_clause)){
  $search_query .= " WHERE $where_clause" ;
}

return $search_query;

}


function generate_page_links($user_search, $cur_page, $num_pages){

 $page_links = '' ;

# if this is not the first page, then generate the previous links
if($cur_page > 1){
 $page_links .= '<li> <a href="' . $_SERVER['PHP_SELF'] . '?q=' . $user_search . '&page=' . ($cur_page-1) . '"> <- </a> </li> ' ;
} else {
  $page_links .= '<li>  <- </li>' ;
}

# loop through the pages generating the page number links
for($i = 1 ; $i <= $num_pages ; $i++){

  if($cur_page == $i){
     $page_links .= ' <li class="active">  <a href="' . $_SERVER['PHP_SELF'] . '?q=' . $user_search . '&page=' . $i . '"> ' . $i . ' </a> </li>' ;
  } else {
    $page_links .= '<li> <a href="' . $_SERVER['PHP_SELF'] . '?q=' . $user_search . '&page=' . $i . '"> ' . $i . ' </a> </li>' ;
  }

}

if($cur_page < $num_pages){
   $page_links .= '<li> <a href="' . $_SERVER['PHP_SELF'] . '?q=' . $user_search . '&page=' . ($cur_page + 1) . '"> -> </a> </li> ' ;
} else {
  $page_links .=  '<li> -> </li>' ;
}

return $page_links;

}


?>
