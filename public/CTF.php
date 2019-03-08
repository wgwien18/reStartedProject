<?php require_once("../private/initialize.php"); 
?>
<!DOCTYPE html>
  <html>
      
    <head>
        <title>CTF</title>
       <!-- <script src = "assets/js/jquery.js" ></script> 
		<script src = "assets/js/jquery-ui.min.js" ></script> 
		<link rel="stylesheet" href= "assets/css/bootstrap.css" />
		-->
		<script src = "assets/js/jquery.js" ></script> 
		<script src = "assets/js/jquery-ui.min.js" ></script> 
		<link rel="stylesheet" href="assets/fonts/fontawesome5/css/all.css" />

</head>
 <body>
 <style>
 div {
  width: 60px;
  height: 60px;
  background-color: skyblue;
}

.moved {
  transform: translate(50px,60px); /* Equal to translateX(10px) */
  background-color: pink;
}

div {
  border: 1 solid red;
  transform: translate3d(12px, 90%, 0.2em);
  width: 140px;
  height: 60px;
}
.div {
  width: 60px;
  height: 60px;
  background-color: skyblue;
  zoom: 10%;
}

.moved {
  /* Equivalent to perspective(500px) translateX(10px) */
  transform: perspective(5px) translate3d(10px, 0, 0px);
  background-color: pink;
  transition: all .5;
}

 </style>

 <?php
 
 
 $my_query = "SELECT id,firstname,lastname, IF(firstname = 'Yussif' && lastname = 'Muniru','correct','wrong') AS correct FROM users WHERE id = 1";
 
 
 
 if($db->multi_query($my_query)){
	 
	 
	 do{
		 if($results = $db->store_result()){
		 if($results->num_rows >0){
			while($row = $results->fetch_assoc()){
				print_r($row);
			} 
				 
			 }
			 
		 }
	 }while($db->more_results() && $db->next_result());
	 
 }else{
	 echo $db->error;
 }
 
 
 
 
 die();
 $offset = 220;
 $offset_upperbound = 250;
 
 $query = "SELECT ".ViewsLikes::$table_name.".".ViewsLikes::$id." AS ".ViewsLikes::$alias_of_id.",".ViewsLikes::$post_id." AS ".ViewsLikes::$alias_of_post_id.",".ViewsLikes::$table_name.".".ViewsLikes::$comment_id."    AS ".ViewsLikes::$alias_of_comment_id.",".ViewsLikes::$table_name.".".ViewsLikes::$user_id." AS ".ViewsLikes::$alias_of_user_id.",".ViewsLikes::$table_name.".".ViewsLikes::$firstname." AS ".ViewsLikes::$alias_of_firstname.",".ViewsLikes::$table_name.".".ViewsLikes::$lastname." AS ".ViewsLikes::$alias_of_lastname.",".ViewsLikes::$table_name.".".ViewsLikes::$likes_time." AS ".ViewsLikes::$alias_of_likes_time." FROM ".ViewsLikes::$table_name." WHERE ".ViewsLikes::$post_id." >={$offset} && ".ViewsLikes::$post_id." <={$offset_upperbound};";
 
 
 $query  .= "SELECT ".ReplyViewsLikes::$table_name.".".ReplyViewsLikes::$id." AS ".ReplyViewsLikes::$alias_of_id.",".ReplyViewsLikes::$table_name.".".ReplyViewsLikes::$reply_id." AS ".ReplyViewsLikes::$alias_of_reply_id.",".ReplyViewsLikes::$post_id." AS ".ReplyViewsLikes::$alias_of_post_id.",".ReplyViewsLikes::$table_name.".".ReplyViewsLikes::$comment_id."    AS ".ReplyViewsLikes::$alias_of_comment_id.",".ReplyViewsLikes::$table_name.".".ReplyViewsLikes::$user_id." AS ".ReplyViewsLikes::$alias_of_user_id.",".ReplyViewsLikes::$table_name.".".ReplyViewsLikes::$firstname." AS ".ReplyViewsLikes::$alias_of_firstname.",".ReplyViewsLikes::$table_name.".".ReplyViewsLikes::$lastname." AS ".ReplyViewsLikes::$alias_of_lastname.",".ReplyViewsLikes::$table_name.".".ReplyViewsLikes::$likes_time."  AS ".ReplyViewsLikes::$alias_of_likes_time." FROM ".ReplyViewsLikes::$table_name." WHERE ".ReplyViewsLikes::$post_id." >={$offset} && ".ReplyViewsLikes::$post_id." <={$offset_upperbound};"; 
 
 $query .= " SELECT * FROM ".LinkUsers::$table_name." WHERE ".LinkUsers::$linker_id."
=4";




$views_likes_user_ids = [];
$reply_views_likes_user_ids  = [];
$linked_users          = [];
$array = [];
  if($db->multi_query($query)){
	  
	  do{
		  
		  if($result = $db->store_result()){
			  if($result->num_rows > 0){
			  while($row = $result->fetch_assoc()){
				 $array[] = $row; 
			  if(isset($row[ViewsLikes::$alias_of_id]) && isset($row[ViewsLikes::$alias_of_user_id]) && $row[ViewsLikes::$alias_of_id] > 0 && $row[ViewsLikes::$alias_of_user_id] > 0){
				  
				 
				  $views_likes_user_ids[$row[ViewsLikes::$alias_of_id]][] = $row[ViewsLikes::$alias_of_user_id];
			  }elseif(isset($row[ReplyViewsLikes::$alias_of_id]) && isset($row[ReplyViewsLikes::$alias_of_user_id]) && $row[ReplyViewsLikes::$alias_of_id] > 0 && $row[ReplyViewsLikes::$alias_of_user_id] > 0){
				  
				  $reply_views_likes_user_ids[$row[ReplyViewsLikes::$alias_of_id]][] = $row[ReplyViewsLikes::$alias_of_user_id];
			  }elseif(isset($row[LinkUsers::$linker_id])){
				  $linked_users[] = $row;
			  }
			  elseif(trim($db->error) != ""){
				  
				   print j(["false" =>"Sorry please server problem"]);
				   return;
			  }else{
				  echo "entered here";
				  return;
			  }
			  
		  }
	  }
	  }
		  
	  }while($db->more_results() && $db->next_result());
	  
  } 
  
  
  
  
  
  echo "<pre>";
 echo "views likes"."<br />";
print_R($views_likes_user_ids);
echo "reply views likes"."<br />";
print_r($reply_views_likes_user_ids);
echo "linked users"."<br />";
print_r($linked_users);  
  echo "</pre>";
 
 /* print_r(Pagination::get_reply_likes_user_ids(220,250)); */
/*  echo $_SESSION["id"];
if(in_array($_SESSION["id"],[4])){
	echo "us;";
}else{
	echo "e";
}
 Pagination::get_infinite_scroll("mainstream");  */
 ?>
 <i class="fal fa-check-circle"></i>
 </body>
  </html>
