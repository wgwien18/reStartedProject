<?php

require_once("../private/initialize.php");

class ConnectUsers Extends DatabaseObject{
	

  public static $table_name = "link_users";


  public static $id         = "id";
  public static $link      = "link";
  public static $linker_id  = "linker_id";
  public static $firstname  = "firstname";
  public static $lastname   = "lastname";
  public static $time       = "time";


  public static $session_string = "linked_users_ids";

  public static function connect_user($user_id  = 0, $post_id = 0){
	   
	   global $db;

      if(isset($_SESSION[user::$id]) && $_SESSION[user::$id] > 0 && trim($_SESSION[user::$firstname]) != "" && trim($_SESSION[user::$lastname]) != "" ){

      }

	      $user_id = $db->real_escape_string($user_id);
		  $post_id = $db->real_escape_string($post_id);
		  
		  
	   $query = "CALL user_connection_request({$user_id},".$_SESSION[user::$id].",".time().")";
      


	   if($db->multi_query($query)){
          
      
			
			do{
       
			if($result = $db->store_result()){
				
				 if($result->num_rows > 0){
			  if($row = $result->fetch_assoc()){
         
				  if(isset($row) && $row["result"] > 0){
					  print j(["true" =>"success"]);
					  return;
				  }elseif(isset($row) && $row["result"] == "disconnected"){
					  
					  print j(["disconnected" => "success"]);
					  return;
				  }elseif(isset($row) && $row["result"] == "invalid_connection" ){
                      Errors::trigger_error(RETRY);
					  return;
				  }
                  
                  elseif(trim($db->error) != ""){
				    Errors::trigger_error(RETRY);
					  return;
				  }
			  }	
			
			}
				 }else{
					 log_action(__CLASS__,$db->error);
					 print j(["false" => "Please refresh the page and try again. here"]);
					 return;
				 }
			}while($db->more_results() && $db->next_result());
				  
	   }else{
      echo $db->error;
     }
   
   }// link_user();
	

}







