<?php

require_once("../private/initialize.php");



class Pagination extends DatabaseObject {
	
// get infinite scroll	
public static function get_infinite_scroll($stream_type = ""){
	
 global $db;

	  if(!isset($_SESSION)){
		  print j(["false"=>"login"]);
		  return;
	  }
	  
	  if(!isset($_SESSION[$stream_type])){
		 
return;		 
	  }

	   $_SESSION[$stream_type] = 220;
	$offset = (isset($_SESSION[$stream_type]) && isset($_SESSION[$stream_type]) && (int)$_SESSION[$stream_type] > 0) ? (int)$_SESSION[$stream_type] + 20 : (int)$_SESSION[$stream_type] = 220;
	
// $offset =(int)$_SESSION[$stream_type];



$returned_array              = [];
$reactions_user_ids	         = [];
$views_likes_user_ids        = [];
$reply_views_likes_user_ids  = [];
$comments                    = [];
$offset_upperbound           = $offset + 10;
 
 
	$query = " SELECT  ".user::$firstname.",".user::$lastname.",".user::$table_name.".".user::$user_category.",".PostImage::$table_name.".*,".FetchPost::$table_name.".*,".PostImage::$table_name.".".PostImage::$id." AS ".PostImage::$alias_of_id.",".FetchPost::$table_name.".".FetchPost::$id." AS ".FetchPost::$alias_of_id.",".Reaction::$table_name.".".Reaction::$user_id." AS ".Reaction::$alias_of_user_id.",".Reaction::$table_name.".".Reaction::$reaction_type." FROM ".PostImage::$table_name."
JOIN ".user::$table_name." ON 
".PostImage::$table_name.".".PostImage::$uploader_id." = ".user::$table_name.".id LEFT JOIN  ".FetchPost::$table_name." ON ".FetchPost::$table_name.".".FetchPost::$post_id." = ".PostImage::$table_name.".".PostImage::$id."  LEFT JOIN  ".Reaction::$table_name." ON ".Reaction::$table_name.".".Reaction::$post_id." =  ".PostImage::$table_name.".".PostImage::$id." WHERE ".PostImage::$table_name.".".PostImage::$id." >={$offset} && ".PostImage::$table_name.".".PostImage::$id."<= {$offset_upperbound}  LIMIT 200";

$results = $db->query($query);
	


$row_count = $results->num_rows;
	  
	if($db->error){
		echo $db->error;
		
	}
// validate the returning of results	
 if($results->num_rows > 0){
	 
 
	while($row = $results->fetch_assoc()){
		
		
		 
	if(isset($reactions_user_ids) && array_key_exists($row[FetchPost::$post_id],$reactions_user_ids)){
		    if(isset($reactions_user_ids) && isset($reactions_user_ids[$row[FetchPost::$post_id]]) && !in_array($row[Reaction::$alias_of_user_id],$reactions_user_ids[$row[FetchPost::$post_id]])){
				$reactions_user_ids[$row[FetchPost::$post_id]][$row[Reaction::$alias_of_user_id]] = $row[ Reaction::$reaction_type];
			}
		
	}else{
		$reactions_user_ids[$row[FetchPost::$post_id]][$row[Reaction::$alias_of_user_id]] = $row[ Reaction::$reaction_type];
	}
	
	
	// accumulate and re-structure all the post and their respective files
	if(isset($returned_array) && array_key_exists($row[FetchPost::$post_id],$returned_array)){
	 
	if(isset($returned_array[$row[FetchPost::$post_id]]) &&
	array_key_exists("filenames_".$row[FetchPost::$post_id],
	$returned_array[$row[FetchPost::$post_id]])){
	 
				
 $returned_array[$row[FetchPost::$post_id]]["filenames_".$row[FetchPost::$post_id]][$row[FetchPost::$alias_of_id]] = $row[FetchPost::$filename];
	
	}
	
	
	
	// else add the incident to the incidents array 	
}else{
	
		// with the post_table_id as the key
		$returned_array[$row[FetchPost::$post_id]][] = $row;
		$returned_array[$row[FetchPost::$post_id]]["filenames_".$row[FetchPost::$post_id]][$row[FetchPost::$alias_of_id]] = $row[FetchPost::$filename];
		
}
	}
 }else{
	
	$query = "SELECT MIN(id) AS min_id FROM post_table WHERE id > ".((int)$_SESSION[$stream_type]." LIMIT 1000");
	 $result = $db->query($query); 
	   
	 if($result->num_rows > 0 && $row = $result->fetch_assoc()){
		  $_SESSION[$stream_type] = $row["min_id"];
		   $result->free();
	 }else{
		$_SESSION[$stream_type] = 0;
		print j(["pending"=>"waiting"]);
		$result->free();
        	return;	
	 }
	
 } 
  // check if the returned post is empty
  if(empty($returned_array)){
	 print j(["pending"=>"waiting"]);
	 log_action(__CLASS__,"Empty post (This is an emergency that needs special attention(check the query very well)) on line: ".__LINE__." in file ".__FILE__);
return;	 
  }
  
  
  // Get the reactions to the incidents
  $query = "SELECT * FROM ".Reaction::$table_name." WHERE ".Reaction::$post_id." >= {$offset} && ".Reaction::$post_id."<= {$offset_upperbound}";
  
  $result = $db->query($query);
  
  if($db->error != ""){
	  print j(["false"=>"Server problem please refresh the page and try again"]);
	  return;
  }
  
  while($row = $result->fetch_assoc()){
	  $reactions_user_ids[$row[Reaction::$post_id]][$row[Reaction::$user_id]] = $row[Reaction::$reaction_type];
  }
  
  $result->free();
  
  

  
  if(class_exists("ReplyViews") && class_exists("user") && class_exists("Views")){

$query = "SELECT ".Views::$table_name.".*,".ReplyViews::$table_name.".*,".Views::$table_name.".".Views::$id." AS ".Views::$alias_of_id.",".ReplyViews::$table_name.".".ReplyViews::$id." AS ".ReplyViews::$alias_of_id.", ".ReplyViews::$table_name.".".ReplyViews::$firstname." AS ".ReplyViews::$alias_of_firstname." ,".ReplyViews::$table_name.".".ReplyViews::$lastname." AS ".ReplyViews::$alias_of_lastname.",".Views::$table_name.".".Views::$firstname." AS ".Views::$alias_of_firstname.",".Views::$table_name.".".Views::$lastname." AS ".Views::$alias_of_lastname.",".Views::$table_name.".".Views::$post_id." AS ".Views::$alias_of_post_id." ,".ReplyViews::$table_name.".".ReplyViews::$post_id." AS ".ReplyViews::$alias_of_post_id." FROM  ".Views::$table_name." 
 LEFT JOIN  ".ReplyViews::$table_name." ON ".ReplyViews::$table_name.".".ReplyViews::$comment_id." = ".Views::$table_name.".".Views::$id."  WHERE  ".Views::$table_name.".".Views::$post_id." >={$offset} && ".Views::$table_name.".".Views::$post_id." <= {$offset_upperbound} LIMIT 5000";

 log_action("",$query);
  $results = $db->query($query);
  $row_count = $results->num_rows;
	
	
	if($db->error){

		log_action(__CLASS__,"Query: {$db->error} on line: ".__LINE__." in file: ".__FILE__);
		echo $db->error;
		Print j(["false"=>"Server Problem please try again later."]);
		return;
	}

	
// validate the returning of results	
 if($results->num_rows > 0){
	 

	while($row = $results->fetch_assoc()){

	
	// if the post id has being set 
if(isset($comments) && isset($comments["postID_".$row[Views::$alias_of_post_id]])){
	
	// if the comment is present add its replys
	if(isset($comments["postID_".$row[Views::$alias_of_post_id]]["viewsID_".$row[Views::$alias_of_id]])&&
	array_key_exists("viewsID_".$row[Views::$alias_of_id],$comments["postID_".$row[Views::$alias_of_post_id]])){
	 
	// add its replys 	
 $comments["postID_".$row[Views::$alias_of_post_id]]["viewsID_".$row[Views::$alias_of_id]]["replys_".$row[Views::$alias_of_id]][$row[ReplyViews::$alias_of_id]] = $row;
	
	// else add the comment and its accompanying reply
}else{
			 
		// with the post_table_id as the key
		$comments["postID_".$row[Views::$alias_of_post_id]]["viewsID_".$row[Views::$alias_of_id]]["replys_".$row[Views::$alias_of_id]][$row[ReplyViews::$alias_of_id]] = $row;
		$comments["postID_".$row[Views::$alias_of_post_id]]["viewsID_".$row[Views::$alias_of_id]][] = $row;
	}
	
	
// else set the post id and add both a comment then the reply
}else{
			  
		
		// with the post_table_id as the key
		$comments["postID_".$row[Views::$alias_of_post_id]]["viewsID_".$row[Views::$alias_of_id]]["replys_".$row[Views::$alias_of_id]][$row[ReplyViews::$alias_of_id]] = $row;
		$comments["postID_".$row[Views::$alias_of_post_id]]["viewsID_".$row[Views::$alias_of_id]][] = $row;
				
	   }
	   
	}// end_of_while loop;
 }else{
	
	$query = "SELECT MIN(id) AS min_id FROM views WHERE id > ".((int)$_SESSION[$stream_type]." LIMIT 1000");
	 $result = $db->query($query); 
	   
	 if($result->num_rows > 0 && $row = $result->fetch_assoc()){
		  $_SESSION[$stream_type] = $row["min_id"];
		   $result->free();
	 }else{
		$_SESSION[$stream_type] = 0;
		print j(["pending" => "waiting"]);
		$result->free();
        	return;	
	 }
	
 } 


 
 
 $query = "SELECT ".ViewsLikes::$table_name.".".ViewsLikes::$id." AS ".ViewsLikes::$alias_of_id.",".ViewsLikes::$post_id." AS ".ViewsLikes::$alias_of_post_id.",".ViewsLikes::$table_name.".".ViewsLikes::$comment_id."    AS ".ViewsLikes::$alias_of_comment_id.",".ViewsLikes::$table_name.".".ViewsLikes::$user_id." AS ".ViewsLikes::$alias_of_user_id.",".ViewsLikes::$table_name.".".ViewsLikes::$alias_of_user_id.",".ViewsLikes::$table_name.".".ViewsLikes::$firstname." AS ".ViewsLikes::$alias_of_firstname.",".ViewsLikes::$table_name.".".ViewsLikes::$lastname." AS ".ViewsLikes::$alias_of_lastname.",".ViewsLikes::$table_name.".".ViewsLikes::$likes_time.".".ViewsLikes::$alias_of_likes_time." WHERE ".ViewsLikes::$post_id." >={$offset} && ".ViewsLikes::$post_id." <={$offset};"; 
 
 $query  .= "SELECT ".ReplyViewsLikes::$table_name.".".ReplyViewsLikes::$id." AS ".ReplyViewsLikes::$alias_of_id.",".ReplyViewsLikes::$post_id." AS ".ReplyViewsLikes::$alias_of_post_id.",".ReplyViewsLikes::$table_name.".".ReplyViewsLikes::$comment_id."    AS ".ReplyViewsLikes::$alias_of_comment_id.",".ReplyViewsLikes::$table_name.".".ReplyViewsLikes::$user_id." AS ".ReplyViewsLikes::$alias_of_user_id.",".ReplyViewsLikes::$table_name.".".ReplyViewsLikes::$alias_of_user_id.",".ReplyViewsLikes::$table_name.".".ReplyViewsLikes::$firstname." AS ".ReplyViewsLikes::$alias_of_firstname.",".ReplyViewsLikes::$table_name.".".ReplyViewsLikes::$lastname." AS ".ReplyViewsLikes::$alias_of_lastname.",".ReplyViewsLikes::$table_name.".".ReplyViewsLikes::$likes_time.".".ReplyViewsLikes::$alias_of_likes_time." WHERE ".ReplyViewsLikes::$post_id." >={$offset} && ".ReplyViewsLikes::$post_id." <={$offset};"; 
 
  if($db->multi_query($query)){
	  
	  do{
		  
		  if($row = $result->store_result()){
			  
			  if(isset($row[ViewsLikes::$alias_of_id]) && isset($row[ViewsLikes::$alias_of_user_id]) && $row[ViewsLikes::$alias_of_id] > 0 && $row[ViewsLikes::$alias_of_user_id] > 0){
				  $views_likes_user_ids[ViewsLikes::$alias_of_id][] = ViewsLikes::$alias_of_user_id;
			  }elseif(isset($row[ReplyViewsLikes::$alias_of_id]) && isset($row[ReplyViewsLikes::$alias_of_user_id]) && $row[ReplyViewsLikes::$alias_of_id] > 0 && $row[ReplyViewsLikes::$alias_of_user_id] > 0){
				  $views_likes_user_ids[ReplyViewsLikes::$alias_of_id][] = ReplyViewsLikes::$alias_of_user_id;
			  }elseif(trim($db->error) != ""){
				   print j(["false" =>"Sorry please server problem"]);
				   return;
			  }else{
				  return;
			  }
		  }
		  
	  }while($db->more_results() && $db->next_result());
	  
  }
  
 echo "<pre>";
 print_r($views_likes_user_ids);
 print_r($reply_views_likes_user_ids);
 echo "</pre>";
 return;
 
 
 /* 
 foreach($comments["postID_249"] AS $comment => $comment_info){
	 echo "<pre>";
	   $comment = array_pop($comment_info);
	   echo $comment["comment"]."<br />"." Post id: ".$comment[Views::$alias_of_post_id]." <br />";
	     echo $comment[Views::$alias_of_id];
		 foreach($comment_info["replys_".$comment[Views::$alias_of_id]] AS $index => $replys){
			 echo "Replys id: ".$replys[ReplyViews::$alias_of_id]." ".$replys["reply"]."<br />";
			 
			 
		 }
		 echo "</pre>";
  }
 
 echo "<pre>"; 
 print_r($returned_array);
 echo "</pre>";
 echo "<br />----------------------------------------------------<br />";
 echo "<pre>";
 print_r($comments);
 echo "</pre>";
 return; */
 
 FetchPost::get_full_post($returned_array,$comments,$reactions_user_ids,STREAM);
   $returned_array     = null;
   $comments           = null;
   $post_ids_array     = null;
   $reactions_user_ids = null;
	
	
	
	
}	
	
	
	
	
	
	
	
	
	
	
}
	
}

?>