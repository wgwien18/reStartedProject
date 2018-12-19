<?php
require_once("../private/initialize.php");


class FetchPost extends DatabaseObject{

    public static $class_name = "FetchPost";
    public static $table_name = "normal_post_table";
    public static $posts = "";
    public static $JOYFUL      = "joyful";
    public static $MEH         =  "meh";
    public static $LOVE        =  "love";
    public static $FLATTERED   = "flatterd";
    public static $CRAZY       = "crazy";
    public static $COOL        = "cool";
    public static $TIRED       = "tired";
    public static $CONFUSED    = "confused";
    public static $SPEECCHLESS = "speechless";
    public static $CONFIDENT   =  "confident";
    public static $RELAXED     =  "relaxed";
    public static $STRONG      = "strong";
    public static $HAPPY       = "happy";
    public static $ANGRY       = "angry";
    public static $SAD         = "sad";
    public static $SICK        = "sick";
    public static $BLESSED     = "blessed";




    function __construct(){
    return self::top_trends();
}

 public static function post_with_views($post = "",$data = ""){

$array = array_unique($post);
foreach ($array as $array_value) {
  echo " <hr /> <hr /><br />".$array_value;
foreach ($data as $key => $value) {

  if($value["post"] == $array_value){
   echo $value["view"]." ".$value["view_time"]." ".$value["view_type"].$value["view_post_id"]."<br />";
  }
 }
  
}
 }


// the top trends for a specific interest...or label.

public static function top_specific_trends($interest = ""){

	global $db;

	$query = " SELECT post_table.* ,firstname,lastname,profile_image,user_id,reaction, FROM post_table LEFT JOIN users ON post_table.uploader_id = users.id LEFT JOIN reactions ON reactions.post_id = post_table.id WHERE label = ? GROUP BY post_table.id ORDER BY (post_table.support + post_table.oppose) DESC ";


$stmt = self::S_query($query);

if(!$stmt){

	die("Prepare failed: top_trends() || show_post_class.php ".$db->error);
}

if(!$stmt->bind_param("s",$interest)){
 
 die("Binding failed: top_trends() || show_post_class.php".$db->error);
}

if(!$stmt->execute()){


die("Execution failed: top_trends() || show_post_class.php ".$db->error);
}


// store the result
$result = $stmt->get_result();

//free the resources
self::free_resources($stmt,$db);

// return the result
return $result;

}//top_interest_trends()

// shows the top trending news accross the globe uploaded by people
public static function top_trends($where_clause = ""){

	global $db;
//
//$query = " SELECT ".self::$post_table.".*,firstname,lastname,profile_image,reaction,reactions.user_id AS user_id,trybe.user_id AS trybe_user_id,trybe.uploader_id AS member_id,time_trybed,follow.user_id AS follow_user_id,follow.post_id AS follow_post_id,time_followed   FROM ".self::$post_table." LEFT JOIN ".users$S_table_name." ON ".users$S_table_name.".id = ".self::$post_table.".uploader_id LEFT JOIN ".Reaction::$table_name." ON ".Reaction::$table_name.".post_id = ".self::$post_table.".id LEFT JOIN trybe ON trybe.post_id = post_table.id OR trybe.user_id = users.id LEFT JOIN follow ON follow.post_id = post_table.id  {$where_clause} GROUP BY post_table.id ORDER BY (support+oppose) DESC LIMIT 10 ";
//
////
//  //$query = "SELECT * FROM test";
//// $query = "SELECT post_table.* ,post_table.support AS post_support,post_table.oppose AS post_oppose,firstname,lastname,users.profile_image AS uploader_image,reactions.user_id AS reactions_user_id,views.*,post_table.id AS post_table_id,reactions.reaction AS post_reaction FROM post_table LEFT JOIN users ON post_table.uploader_id = users.id LEFT JOIN reactions ON reactions.post_id = post_table.id LEFT JOIN views ON views.post_id = post_table.id GROUP BY views.id  ";
//
//
//// $query = "SELECT post_table.* ,post_table.support AS post_support,post_table.oppose AS post_oppose,firstname,lastname,users.profile_image AS uploader_image,reactions.user_id AS reactions_user_id,views.*,post_table.id AS post_table_id,reactions.reaction AS post_reaction FROM post_table LEFT JOIN users ON post_table.uploader_id = users.id LEFT JOIN reactions ON reactions.post_id = post_table.id LEFT JOIN views ON views.post_id <= post_table.id OR views.post_id >= post_table.id GROUP BY views.id";
//
//// $stmt = self::S_query($query);
//
//// if(!$stmt){
//// 	//$id,$real_filename,$upload_time,$caption,$label,$location
//
//// 	die("Prepare failed: top_trends() || show_post_class.php ".$db->error);
//// }
//
//// $interest = "'' OR 1 = 1";
//// // if(!$stmt->bind_param("s",$interest)){
//
//// //  die("Binding failed: top_trends() || show_post_class.php".$db->error);
//// // }
//
//// if(!$stmt->execute()){
//
//
//// die("Execution failed: top_trends() || show_post_class.php ".$db->error);
//// }
//
//
//// // store the result
//// $result = $stmt->get_result();
//
//// //free the resources
//// self::free_resources($stmt,$db);
//
//// // $real_result = [];
//// // $data = [];
//// // // return the result
//// // while($row = $result->fetch_array(MYSQLI_ASSOC)){
//// //   // equate the posts to their individual captions
//// //   $real_result[$row["post"]] = [$row["post_table_id"],$row["uploader_id"],$row["upload_time"],$row["caption"],$row["label"],$row["location"],$row["post_support"],$row["post_oppose"],$row["view_support"],$row["view_oppose"],$row["uploader_image"],$row["firstname"],$row["lastname"],$row["reactions_user_id"],$row["firstname"],$row["post_reaction"]];
//// //   // advance the array pointer of the real_result
//// //   next($real_result);
//// //   // store the entire row data in the $data array
//// //   $data [] = $row;
//
//// // }
////  // SELECT post_table.* ,firstname,lastname,users.profile_image AS uploader_image,reactions.user_id,views.*,post_table.id AS post_table_id FROM post_table LEFT JOIN users ON post_table.uploader_id = users.id LEFT JOIN reactions ON reactions.post_id = post_table.id RIGHT JOIN views ON views.post_id = post_table.id GROUP BY views.id
//// // return [$real_result,$data];
  $query = " SELECT firstname,lastname,post_table.* FROM post_table JOIN users ON users.id = uploader_id
   WHERE post_table.id >= ((SELECT MAX(id) FROM post_table) - 3) LIMIT 10";

  $query = $db->query($query);
if(!$query){ 
   printf("Errormessage: %s\n", $db->error);
}else{
  $result_array = [];
    while($row = $query->fetch_array(MYSQLI_ASSOC)){

     $result_array[] = $row;
    }
//    print_r($result_array);
    return $result_array;
//$post_path = "../lqUgAuP7zZlempzC9gN9lIm8yiqnAYfExk/FnjP4kkPmLiF3lAq1nHx7AnbiBTogWwfhvTI/";
$post_path = "../".PRIVATE_DIR."/".PRIVATE_MEDIA."/";
$interest = "";
 
  echo "<form></form>";
 $checked = Reaction::$checked;
 $not_checked = Reaction::$not_checked;

 $post_ids = [];
 $user_ids = [];
 
 echo "<a href=\"#\" role='button' id=\"link\">LInk</a>";
while ($row = $query->fetch_assoc()){
    
    $post_ids[] = $row["id"];
    $user_ids[] = $row["uploader_id"];
    


  // check the post type for each row to determine
  // where to use an image,video or <p> tags accordingly
  if($row["caption"] != PostImage::$post_text_unique_string){
  
  //get the individual filenames from the post
  $exploded_filenames = explode(",",$row["post"]);

 // remove the last element from the array since 
 // this will be null 
 array_pop($exploded_filenames);


echo  "<figure class=\" figure\">
<div class=\"text-center\">

 <a href=\"profile.php?user_id=".u($row["uploader_id"])."\" class=\"profile_image\"><img src=$post_path".$row["profile_image"]." class=\"figure-img img-fluid rounded-circle\" width = \"60px\" height = \"60px\" alt=\"profile_image\"></a></div>";

 echo "<div id='display_post'>";

 foreach ($exploded_filenames as $value) {

   $extension =  trim(substr_replace(strrchr($value,"."),"",0,1));
 if(in_array($extension,FileUpload::$allowed_extensions_images)){
  
  echo "<a href=\"post.php?id=".u($row["id"])."\"><img src=$post_path".$value." class=\"figure-img img-fluid round \" width = \"60px\" height = \"60px\" alt=\"image\" /></a>";

   }elseif(in_array($extension,FileUpload::$allowed_extensions_videos)){
  echo "<video src=\"".$post_path.$value."\"   width=\"480px\" controls>
Sorry, your browser doesn't support embedded videos, 
but don't worry, you can <a href=\"videofile.webm\">download it</a>
and watch it with your favorite video player!
</video>";
   }

}

 echo "</div>";
if(is_null($row["trybe_user_id"])){

  echo "yes it is null";
}
 
 echo $row["follow_user_id"];
// check if the the person who uploaded the file it's
// not the person who  is viewing it 
// to prevent the user from trybe or following himself
if($row["uploader_id"] != $_SESSION["id"]){
  
   if($_SESSION["id"] != $row["trybe_user_id"]) {
     
   // either show the trybe link or untrybe link
echo "<a href=\"#\" role=\"button\" class=\"trybe_member\" name=\"trybe_member\" id=\"trybe_".u($row["uploader_id"])."_".u($row["id"])."\" >trybe</a>&nbsp&nbsp".$row["trybe_user_id"];
 }elseif($_SESSION["id"] == $row["trybe_user_id"]){
  
 echo "<a href=\"#\" role=\"button\" class=\"trybe_member\" name=\"trybe_member\" id=\"trybe_".u($row["uploader_id"])."\" >Untrybe</a>&nbsp&nbsp";
}


// either show the unfollow link or the follow link
if($_SESSION["id"] != $row["follow_user_id"]){
 echo "<a href=\"#\" role=\"button\" class=\"follow_post\" name=\"follow_post\" id=\"follow_".u($row["id"])."\" >follow</a>";
}else{

  echo "<a href=\"#\" role=\"button\" class=\"follow_post\" name=\"follow_post\" id=\"follow_".u($row["id"])."\" >Unfollow Up</a>";
}


}
 echo "<figcaption class=\"figure-caption text-justify\">".$row["firstname"]." ".$row["lastname"]."     A ".$row["label"]."  issue @ ".$row["location"]."  --".$row['caption']."--".upload_time($row["upload_time"])."--

  <p  id= \"".Reaction::$support.$row["id"]."\" >".$row["support"]."</p> 

  <p id= \"".Reaction::$oppose.$row["id"]."\" >".$row["oppose"]."</p></figcaption>

</figure><br />

   <form  action= \"home.php\" method = \"POST\" enctype= \"text/plain\">
  Support <input type=\"radio\" name= \"reaction\" id=\"sup".$row["id"]."\" value =\"".(isset($row["reaction"]) && $row["user_id"] == $_SESSION["id"] ? "{$checked}": "{$not_checked}")."/".$row["id"]."/1\" ".(isset($row["reaction"])  && $row["reaction"] == 1 && $row["user_id"] == $_SESSION["id"] ? "checked = 'checked'" : "")."
/>

  Oppose  <input type= \"radio\" name =\"reaction\" id=\"opp".$row["id"]."\" value =\"".(isset($row["reaction"]) && $row["user_id"] == $_SESSION["id"] ? "{$checked}": "{$not_checked}")."/".$row["id"]."/0\" 

  ".(isset($row["reaction"]) && $row["reaction"] == 0 && $row["user_id"] == $_SESSION["id"] ? "checked='checked'": "")."/>

 <div class='support_div'>
   <div class=\"display_reaction\" id =\"views_".$row["id"]."\">
 </div>
<button id=\"support_button\">Support View</button>
  <textarea cols=\"50px\" width = \"50px\" id=\"t/1/".$row["id"]."/1\" class= \"view support\" placeholder=\"support\" name = \"reaction_view\" >".(isset($text_area) && !empty(trim($text_area)) ? $text_area : "")."</textarea>


<button id=\"oppose_button\">Oppose View</button>
<textarea cols=\"50px\" width = \"50px\" id=\"t/0/".$row["id"]."/0\" class= \"view oppose\"placeholder=\"oppose\" name = \"reaction_view\" >".(isset($text_area) && !empty(trim($text_area)) ? $text_area : "")."</textarea>
</div>
  </form>


";

}else{



  echo "<p>".$row["post"]."--</p>";

  echo "<p  id= \"".Reaction::$support.$row["id"]."\" >".$row["support"]."</p> 

  <p id= \"".Reaction::$oppose.$row["id"]."\" >".$row["oppose"]."</p>";
      
echo " <form  action= \"home.php\" method = \"POST\" enctype= \"text/plain\">
  Support <input type=\"radio\" name= \"reaction\" id=\"sup".$row["id"]."\" value =\"".(isset($row["reaction"]) && $row["user_id"] == $_SESSION["id"] ? "{$checked}": "{$not_checked}")."/".$row["id"]."/1\" ".(isset($row["reaction"])  && $row["reaction"] == 1 && $row["user_id"] == $_SESSION["id"] ? "checked = 'checked'" : "")."
/>

  Oppose  <input type= \"radio\" name =\"reaction\" id=\"opp".$row["id"]."\" value =\"".(isset($row["reaction"]) && $row["user_id"] == $_SESSION["id"] ? "{$checked}": "{$not_checked}")."/".$row["id"]."/0\" 

  ".(isset($row["reaction"]) && $row["reaction"] == 0 && $row["user_id"] == $_SESSION["id"] ? "checked='checked'": "")."/>

 <div class='support_div'>
   <div class=\"display_reaction\" id=\"views_\"".$row["id"].">
 </div>
<button id=\"support_button\">Support View</button>
  <textarea cols=\"50px\" width = \"50px\" id=\"t/1/".$row["id"]."/1\" class= \"view support\" placeholder=\"support\" name = \"reaction_view\" >".(isset($text_area) && !empty(trim($text_area)) ? $text_area : "")."</textarea>


<button id=\"oppose_button\">Oppose View</button>
<textarea cols=\"50px\" width = \"50px\" id=\"t/0/".$row["id"]."/0\" class= \"view oppose\"placeholder=\"oppose\" name = \"reaction_view\" >".(isset($text_area) && !empty(trim($text_area)) ? $text_area : "")."</textarea>
</div>
  </form>

";

      

}

 }

$_SESSION["post_ids"] = $post_ids;
$_SESSION["user_ids"] = $user_ids;
$post_ids = json_encode($post_ids);
 echo "<input type=\"hidden\" value=".$post_ids." name=\"get_views\" class=\"dis\" id=\"post_ids\"/>";


}
}//top_trends()



public static function get_user($id = null){

  global $db;


  $result= $db->query("SELECT * FROM post_table ");

}






public static function show_post(){


$query = "SELECT * FROM ".self::$table_name;
	return FetchPost::find_all($query);

	
}



public static function get_views($id = ""){



    global $db;

    $query = "SELECT * FROM views WHERE id >= ((SELECT MAX(id) FROM views) - 6 ) LIMIT 10";

  $result = $db->query($query);
  $result_array = [];
  while($row = $result->fetch_assoc()){
      $result_array[$row["post_id"]] = $row;
  }

 print j($result_array);
    return;
  $id = json_decode($id);

  $id = (array)$id;
 //return print_r($id);
  $query = "SELECT *,(SELECT COUNT(*) FROM views WHERE post_id = ?) AS num FROM views WHERE post_id = ? ORDER BY views.view_time DESC";
 
 // $query = "SELECT * FROM views WHERE post_id = ?";

  $stmt = self::S_query($query);
  if(!$stmt){

 die("Preparation failed: ".$db->error." statement Error ".$stmt->error);
  }

$real_post_id = $id[0];

  if(!$stmt->bind_param("ii",$real_post_id,$real_post_id)){

    die("Binding failed: ".$stmt->error);

  }


$views = [];
$views_values = [];

 if(!$stmt->execute()){


  die("Execution failed: ".$stmt->error);
  }


 $result =  $stmt->get_result();


while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
  
  // $views_values[] = "<p>".$row["post_id"]." ".$row["view"]." ".$row["view_time"]." ".$row["view_type"]." ".$row["num"]." ".$row["id"]."</p>";
  $views_values[] = $row;
}
$views[$real_post_id] = $views_values;
// return print_r($views_values);
  
//  $views[] = $views_values;
// // $views[$real_post_id] = $views_values;
//  return print_r($views);

// //array_shift($post_id);

 
foreach($id AS $post_id){
      
     $real_post_id = $post_id;
  if(!$stmt->execute()){


  die("Execution failed: ".$stmt->error);
  }


 $result =  $stmt->get_result();


while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
  
  $views_values[] = $row;
}
 $views[$real_post_id] = $views_values;

}

//return print_r($views);
return json_encode($views);

  // global $db;

  // $query = "SELECT * FROM post_table WHERE post_id ={$id}";

  // $result = $db->query("SELECT * FROM views WHERE post_id ={$id}");
  // while($row = $result->fetch_assoc()){
  //  echo $row["view"]."<br />";
  // }

//   $result = $db->query("SELECT * FROM test");

//   while($row = $result->fetch_assoc()){

//   echo $row["value"]."<br />";
// }

}


/**************************************************************************************************************************/


// function __construct(){
//    self::$post = new FetchPost();
//
//}
//public static function getPost()
//{
//
//    $results = FetchPost::top_trends("");
//    foreach ($results as $result => $resultSet ) {
//        echo $resultSet["firstname"];
//    }
//}
//
//public static function stream_header_mood_and_files_template($mood,$files){
//
//     return "<div class=\"ps-stream-header\">
//
//      <!-- post author avatar -->
//      <div class=\"ps-avatar-stream\">
//          <a href=\"https://demo.peepso.com/profile/demo/\">
//              <img data-author=\"2\" src=\"https://demo.peepso.com/wp-content/peepso/users/2/avatar-full.jpg\" alt=\"Patricia Currie avatar\">
//          </a>
//      </div>
//      <!-- post meta -->
//      <div class=\"ps-stream-meta\">
//          <div class=\"reset-gap\">
//              <a class=\"ps-stream-user\" href=\"https://demo.peepso.com/profile/demo/\"><img src=\"https://demo.peepso.com/wp-content/plugins/peepso-extras-vip/classes/../assets/svg/def_3.svg\" alt=\"VIP\" title=\"VIP\" class=\"ps-img-vipicons ps-js-vip-badge\" data-id=\"2\"> Patricia Currie</a> <span class=\"ps-stream-action-title\"> uploaded 1 photo</span>                 <span class=\"ps-js-activity-extras\">          <span>
//              <i class=\"ps-emoticon ps-emo-1\"></i>
//              <span> feeling Joyful</span>
//          </span>
//          </span>
//          </div>
//          <small class=\"ps-stream-time\" data-timestamp=\"1537987149\">
//              <a href=\"https://demo.peepso.com/activity/?status/2-2-1537958349/\">
//                  <span class=\"ps-js-autotime\" data-timestamp=\"1537987149\" title=\"September 26, 2018 6:39 pm\">22 mins ago</span>                </a>
//          </small>
//                      <span class=\"ps-dropdown ps-dropdown-privacy ps-stream-privacy ps-js-dropdown ps-js-privacy--511\">
//              <a href=\"#\" data-value=\"\" class=\"ps-dropdown__toggle ps-js-dropdown-toggle\">
//                  <span class=\"dropdown-value\">
//                      <i class=\"ps-icon-globe\"></i>                 </span>
//              <!--<span class=\"dropdown-caret ps-icon-caret-down\"></span>-->
//              </a>
//              <input type=\"hidden\" id=\"_privacy_wpnonce_511\" name=\"_privacy_wpnonce_511\" value=\"29e654a561\"><input type=\"hidden\" name=\"_wp_http_referer\" value=\"/peepsoajax/postbox.post\">              <div class=\"ps-dropdown__menu ps-js-dropdown-menu\"><a href=\"#\" data-option-value=\"10\" onclick=\"return activity.change_post_privacy(this, 511)\"><i class=\"ps-icon-globe\"></i><span>Public</span></a><a href=\"#\" data-option-value=\"20\" onclick=\"return activity.change_post_privacy(this, 511)\"><i class=\"ps-icon-users\"></i><span>Site Members</span></a><a href=\"#\" data-option-value=\"30\" onclick=\"return activity.change_post_privacy(this, 511)\"><i class=\"ps-icon-user2\"></i><span>Friends Only</span></a><a href=\"#\" data-option-value=\"40\" onclick=\"return activity.change_post_privacy(this, 511)\"><i class=\"ps-icon-lock\"></i><span>Only Me</span></a></div>         </span>
//                  </div>
//      <!-- post options -->
//      <div class=\"ps-stream-options\">
//          <div class=\"ps-dropdown ps-dropdown--stream ps-js-dropdown\">
//<a href=\"#\" class=\"ps-dropdown__toggle ps-js-dropdown-toggle\" data-value=\"\">
//<span class=\"dropdown-caret ps-icon-caret-down\"></span>
//</a>
//<div class=\"ps-dropdown__menu ps-js-dropdown-menu\" style=\"display: none;\">
//<a href=\"#\" onclick=\"activity.option_edit(967, 511); return false\" data-post-id=\"967\"><i class=\"ps-icon-edit\"></i><span>Edit Post</span>
//</a>
//<a href=\"#\" onclick=\"return activity.action_delete(967);\" data-post-id=\"967\"><i class=\"ps-icon-trash\"></i><span>Delete Post</span>
//</a>
//<a href=\"#\" onclick=\"return activity.action_pin(967, 1);\" data-post-id=\"967\"><i class=\"ps-icon-move-up\"></i><span>Pin to top</span>
//</a>
//</div>
//</div>
//      </div>
//  </div>";
//
//}
//
//public static function stream_header_mood_and_files_location_template($mood,$files,$location){
//     return "<div class=\"ps-stream-header\">
//
//      <!-- post author avatar -->
//      <div class=\"ps-avatar-stream\">
//          <a href=\"https://demo.peepso.com/profile/demo/\">
//              <img data-author=\"2\" src=\"https://demo.peepso.com/wp-content/peepso/users/2/avatar-full.jpg\" alt=\"Patricia Currie avatar\">
//          </a>
//      </div>
//      <!-- post meta -->
//      <div class=\"ps-stream-meta\">
//          <div class=\"reset-gap\">
//              <a class=\"ps-stream-user\" href=\"https://demo.peepso.com/profile/demo/\"><img src=\"https://demo.peepso.com/wp-content/plugins/peepso-extras-vip/classes/../assets/svg/def_3.svg\" alt=\"VIP\" title=\"VIP\" class=\"ps-img-vipicons ps-js-vip-badge\" data-id=\"2\"> Patricia Currie</a> <span class=\"ps-stream-action-title\"> uploaded 2 photos</span>                <span class=\"ps-js-activity-extras\">          <span>
//              <i class=\"ps-emoticon ps-emo-1\"></i>
//              <span> feeling Joyful</span>
//          </span>
//                      <span>
//                <a href=\"#\" title=\"GT BANK\" onclick=\"pslocation.show_map(5.6327791, -0.17236560000003465, 'GT BANK'); return false;\">
//                    <i class=\"ps-icon-map-marker\"></i>GT BANK                </a>
//          </span>
//          </span>
//          </div>
//          <small class=\"ps-stream-time\" data-timestamp=\"1537987521\">
//              <a href=\"https://demo.peepso.com/activity/?status/2-2-1537958721/\">
//                  <span class=\"ps-js-autotime\" data-timestamp=\"1537987521\" title=\"September 26, 2018 6:45 pm\">25 mins ago</span>                </a>
//          </small>
//                      <span class=\"ps-dropdown ps-dropdown-privacy ps-stream-privacy ps-js-dropdown ps-js-privacy--514\">
//              <a href=\"#\" data-value=\"\" class=\"ps-dropdown__toggle ps-js-dropdown-toggle\">
//                  <span class=\"dropdown-value\">
//                      <i class=\"ps-icon-globe\"></i>                 </span>
//              <!--<span class=\"dropdown-caret ps-icon-caret-down\"></span>-->
//              </a>
//              <input type=\"hidden\" id=\"_privacy_wpnonce_514\" name=\"_privacy_wpnonce_514\" value=\"02d9bec056\"><input type=\"hidden\" name=\"_wp_http_referer\" value=\"/peepsoajax/postbox.post\">              <div class=\"ps-dropdown__menu ps-js-dropdown-menu\"><a href=\"#\" data-option-value=\"10\" onclick=\"return activity.change_post_privacy(this, 514)\"><i class=\"ps-icon-globe\"></i><span>Public</span></a><a href=\"#\" data-option-value=\"20\" onclick=\"return activity.change_post_privacy(this, 514)\"><i class=\"ps-icon-users\"></i><span>Site Members</span></a><a href=\"#\" data-option-value=\"30\" onclick=\"return activity.change_post_privacy(this, 514)\"><i class=\"ps-icon-user2\"></i><span>Friends Only</span></a><a href=\"#\" data-option-value=\"40\" onclick=\"return activity.change_post_privacy(this, 514)\"><i class=\"ps-icon-lock\"></i><span>Only Me</span></a></div>         </span>
//                  </div>
//      <!-- post options -->
//      <div class=\"ps-stream-options\">
//          <div class=\"ps-dropdown ps-dropdown--stream ps-js-dropdown\">
//<a href=\"#\" class=\"ps-dropdown__toggle ps-js-dropdown-toggle\" data-value=\"\">
//<span class=\"dropdown-caret ps-icon-caret-down\"></span>
//</a>
//<div class=\"ps-dropdown__menu ps-js-dropdown-menu\">
//<a href=\"#\" onclick=\"activity.option_edit(969, 514); return false\" data-post-id=\"969\"><i class=\"ps-icon-edit\"></i><span>Edit Post</span>
//</a>
//<a href=\"#\" onclick=\"return activity.action_delete(969);\" data-post-id=\"969\"><i class=\"ps-icon-trash\"></i><span>Delete Post</span>
//</a>
//<a href=\"#\" onclick=\"return activity.action_pin(969, 1);\" data-post-id=\"969\"><i class=\"ps-icon-move-up\"></i><span>Pin to top</span>
//</a>
//</div>
//</div>
//      </div>
//  </div>";
//}
//
//public static function hellow(){
//
//}
//public static function post_meta($firstname,$lastname,$files,$time,$location){
//    $count = count($files);
//     return "<div class=\"ps-stream-meta\">
//          <div class=\"reset-gap\">
//              <a class=\"ps-stream-user\" href=\" ://demo.peepso.com/profile/demo/\"> $firstname $lastname</a> <span class=\"ps-stream-action-title\"> added $count photos to the album: <a href=\" ://demo.peepso.com/profile/demo/photos/album/37\">What a fantastic trip!</a></span>               <span class=\"ps-js-activity-extras\">          <span>
//          <a href=\"javascript:\" title=\"Siem Reap Province\" onclick=\"pslocation.show_map(13.6915377, 104.10013260000005, 'Siem Reap Province');\">
//              <i class=\"ps-icon-map-marker\"></i>$location           </a>
//          </span>
//          </span>
//          </div>
//          <small class=\"ps-stream-time\" data-timestamp=\"1528749581\">
//              <a href=\" ://demo.peepso.com/activity/?status/2-2-1528720781/\">
//                  <span class=\"ps-js-autotime\" data-timestamp=\"1528749581\" title=\"June 11, 2018 8:39 pm\">$time</span>               </a>
//          </small>
//                      <span class=\"ps-dropdown ps-dropdown-privacy ps-stream-privacy ps-js-dropdown ps-js-privacy--482\">
//              <a href=\"javascript:\" data-value=\"\" class=\"ps-dropdown__toggle ps-js-dropdown-toggle\">
//                  <span class=\"dropdown-value\">
//                      <i class=\"ps-icon-globe\"></i>                 </span>
//              <!--<span class=\"dropdown-caret ps-icon-caret-down\"></span>-->
//              </a>
//              </div>";
//    }
//
//// mood template, will return the mood and it's template
//public static function mood_template(){
//
//}
//
//// label template, will return the label and it's template
//public static function label_and_post_manipulation_template($label){
//
//   return "<div class=\"ps-stream__post-pin\" style=\"display:block\">
//      <span style=\"background-color: rgb(21, 73, 66);\">$label</span>
//          </div>";
//}
//
//public static function post_status_template($status){
// return "<div class=\"ps-stream-attachment cstream-attachment ps-js-activity-content ps-js-activity-content--482\">
//<div class=\"peepso-markdown\"><p>It was an amazing trip! Visited Siem Reap in Cambodia and with it Angkor Wat and a few other temples. We didn't have time to see it all. In all seriousness, you'd need a few weeks to get to see it all. </p>
//<br><p>Totally worth it and unforgettable.</p></div></div>";
//}
//public static function edit_post_options(){
//
//    return  "<div class=\"ps-stream-options\">
//          <div class=\"ps-dropdown ps-dropdown--stream ps-js-dropdown\">
//<a href=\"javascript:\" class=\"ps-dropdown__toggle ps-js-dropdown-toggle\" data-value=\"\">
//<span class=\"dropdown-caret ps-icon-caret-down\"></span>
//</a>
//<div class=\"ps-dropdown__menu ps-js-dropdown-menu\" style=\"display: none;\">
//<a href=\"javascript:\" onclick=\"activity.option_edit(930, 482); return false\" data-post-id=\"930\"><i class=\"ps-icon-edit\"></i><span>Edit Post</span>
//</a>
//<a href=\"javascript:\" onclick=\"return peepso.photos.delete_stream_album(930,482);\" data-post-id=\"930\"><i class=\"ps-icon-trash\"></i><span>Delete Album</span>
//</a>
//<a href=\"javascript:\" onclick=\"return activity.action_pin(930, 1);\" data-post-id=\"930\"><i class=\"ps-icon-move-up\"></i><span>Pin to top</span>
//</a>
//<a href=\"javascript:\" onclick=\"return activity.action_pin(930, 0);\" data-post-id=\"930\"><i class=\"ps-icon-move-down\"></i><span>Unpin</span>
//</a>
//<a href=\"javascript:\" onclick=\"window.open(&quot; ://demo.peepso.com/profile/demo/&quot;, &quot;_blank&quot;);return false\" data-post-id=\"930\"><i class=\"ps-icon-info-circled\"></i><span>Pinned by Patricia</span>
//</a>
//<a href=\"javascript:\" class=\"active\" onclick=\"return false\" data-post-id=\"930\"><i class=\"ps-icon-calendar\"></i><span>Pinned June 11, 2018 at 8:39 pm</span>
//</a>
//</div>
//</div>
//      </div>";
//}
//// time template with time in milliseconds
//public static function time_template($time){
//
//   return " <small class=\"ps-stream-time\" data-timestamp=\"1528749581\">
//              <a href=\" ://demo.peepso.com/activity/?status/2-2-1528720781/\">
//                  <span class=\"ps-js-autotime\" data-timestamp=\"1528749581\" title=\"June 11, 2018 8:39 pm\">3 weeks ago</span>             </a>
//          </small>
//
//          <span class=\"ps-dropdown ps-dropdown-privacy ps-stream-privacy ps-js-dropdown ps-js-privacy--482\">
//              <a href=\"javascript:\" data-value=\"\" class=\"ps-dropdown__toggle ps-js-dropdown-toggle\">
//                  <span class=\"dropdown-value\">
//                      <i class=\"ps-icon-globe\"></i></span>
//              <!--<span class=\"dropdown-caret ps-icon-caret-down\"></span>-->
//              </a>
//              <input type=\"hidden\" id=\"_privacy_wpnonce_482\" name=\"_privacy_wpnonce_482\" value=\"bb669eb258\"><input type=\"hidden\" name=\"_wp_http_referer\" value=\"/peepsoajax/activity.show_posts_per_page\"><div class=\"ps-dropdown__menu ps-js-dropdown-menu\"><a href=\"javascript:\" data-option-value=\"10\" onclick=\"return activity.change_post_privacy(this, 482)\"><i class=\"ps-icon-globe\"></i><span>Public</span></a><a href=\"javascript:\" data-option-value=\"20\" onclick=\"return activity.change_post_privacy(this, 482)\"><i class=\"ps-icon-users\"></i><span>Site Members</span></a><a href=\"javascript:\" data-option-value=\"30\" onclick=\"return activity.change_post_privacy(this, 482)\"><i class=\"ps-icon-user2\"></i><span>Friends Only</span></a><a href=\"javascript:\" data-option-value=\"40\" onclick=\"return activity.change_post_privacy(this, 482)\"><i class=\"ps-icon-lock\"></i><span>Only Me</span></a></div></span>
//          ";
//}
//
//
////ALL HEADER TEMPLATE
//public static function header_template(){
//     return "<div class=\"ps-stream-header\">
//      <!-- post meta -->
//      <div class=\"ps-stream-meta\">
//          <div class=\"reset-gap\">
//              <a class=\"ps-stream-user\" href=\"https://demo.peepso.com/profile/demo/\"> Patricia Currie</a> <span class=\"ps-stream-action-title\"> added 10 photos to the album: <a href=\"https://demo.peepso.com/profile/demo/photos/album/37\">What a fantastic trip!</a></span>                <span class=\"ps-js-activity-extras\">          <span>
//                <a href=\"#\" title=\"Siem Reap Province\" onclick=\"pslocation.show_map(13.6915377, 104.10013260000005, 'Siem Reap Province'); return false;\">
//                    <i class=\"ps-icon-map-marker\"></i>Siem Reap Province                </a>
//          </span>
//          </span>
//          </div>
//          <small class=\"ps-stream-time\" data-timestamp=\"1528749581\">
//              <a href=\"https://demo.peepso.com/activity/?status/2-2-1528720781/\">
//                  <span class=\"ps-js-autotime\" data-timestamp=\"1528749581\" title=\"June 11, 2018 8:39 pm\">4 months ago</span>                </a>
//          </small>
//                      <span class=\"ps-dropdown ps-dropdown-privacy ps-stream-privacy ps-js-dropdown ps-js-privacy--482\">
//              <a href=\"#\" data-value=\"\" class=\"ps-dropdown__toggle ps-js-dropdown-toggle\">
//                  <span class=\"dropdown-value\">
//                      <i class=\"ps-icon-globe\"></i>                 </span>
//              <!--<span class=\"dropdown-caret ps-icon-caret-down\"></span>-->
//              </a>
//              <input type=\"hidden\" id=\"_privacy_wpnonce_482\" name=\"_privacy_wpnonce_482\" value=\"0e2e69cadc\"><input type=\"hidden\" name=\"_wp_http_referer\" value=\"/peepsoajax/activity.show_posts_per_page\">              <div class=\"ps-dropdown__menu ps-js-dropdown-menu\"><a href=\"#\" data-option-value=\"10\" onclick=\"return activity.change_post_privacy(this, 482)\"><i class=\"ps-icon-globe\"></i><span>Public</span></a><a href=\"#\" data-option-value=\"20\" onclick=\"return activity.change_post_privacy(this, 482)\"><i class=\"ps-icon-users\"></i><span>Site Members</span></a><a href=\"#\" data-option-value=\"30\" onclick=\"return activity.change_post_privacy(this, 482)\"><i class=\"ps-icon-user2\"></i><span>Friends Only</span></a><a href=\"#\" data-option-value=\"40\" onclick=\"return activity.change_post_privacy(this, 482)\"><i class=\"ps-icon-lock\"></i><span>Only Me</span></a></div>         </span>
//                  </div>
//      <!-- post options -->
//      <div class=\"ps-stream-options\">
//          <div class=\"ps-dropdown ps-dropdown--stream ps-js-dropdown\">
//<a href=\"#\" class=\"ps-dropdown__toggle ps-js-dropdown-toggle\" data-value=\"\">
//<span class=\"dropdown-caret ps-icon-caret-down\"></span>
//</a>
//<div class=\"ps-dropdown__menu ps-js-dropdown-menu\">
//<a href=\"#\" onclick=\"activity.option_edit(930, 482); return false\" data-post-id=\"930\"><i class=\"ps-icon-edit\"></i><span>Edit Post</span>
//</a>
//<a href=\"#\" onclick=\"return peepso.photos.delete_stream_album(930,482);\" data-post-id=\"930\"><i class=\"ps-icon-trash\"></i><span>Delete Album</span>
//</a>
//<a href=\"#\" onclick=\"return activity.action_pin(930, 1);\" data-post-id=\"930\"><i class=\"ps-icon-move-up\"></i><span>Pin to top</span>
//</a>
//<a href=\"#\" onclick=\"return activity.action_pin(930, 0);\" data-post-id=\"930\"><i class=\"ps-icon-move-down\"></i><span>Unpin</span>
//</a>
//<a href=\"#\" onclick=\"window.open(&quot;https://demo.peepso.com/profile/demo/&quot;, &quot;_blank&quot;);return false\" data-post-id=\"930\"><i class=\"ps-icon-info-circled\"></i><span>Pinned by Patricia</span>
//</a>
//<a href=\"#\" class=\"active\" onclick=\"return false\" data-post-id=\"930\"><i class=\"ps-icon-calendar\"></i><span>Pinned June 11, 2018 at 8:39 pm</span>
//</a>
//</div>
//</div>
//      </div>
//  </div>";
//}
//
//
//public static function header_label_template(){
//     return "<div class=\"ps-stream-header\">
//
//      <!-- post author avatar -->
//      <div class=\"ps-avatar-stream\">
//          <a href=\"https://demo.peepso.com/profile/demo/\">
//              <img data-author=\"2\" src=\"https://demo.peepso.com/wp-content/peepso/users/2/avatar-full.jpg\" alt=\"Patricia Currie avatar\">
//          </a>
//      </div>
//      <!-- post meta -->
//      <div class=\"ps-stream-meta\">
//          <div class=\"reset-gap\">
//              <a class=\"ps-stream-user\" href=\"https://demo.peepso.com/profile/demo/\"><img src=\"https://demo.peepso.com/wp-content/plugins/peepso-extras-vip/classes/../assets/svg/def_3.svg\" alt=\"VIP\" title=\"VIP\" class=\"ps-img-vipicons ps-js-vip-badge\" data-id=\"2\"> Patricia Currie</a> <span class=\"ps-stream-action-title\"> added 10 photos to the album: <a href=\"https://demo.peepso.com/profile/demo/photos/album/37\">What a fantastic trip!</a></span>                <span class=\"ps-js-activity-extras\">          <span>
//                <a href=\"#\" title=\"Siem Reap Province\" onclick=\"pslocation.show_map(13.6915377, 104.10013260000005, 'Siem Reap Province'); return false;\">
//                    <i class=\"ps-icon-map-marker\"></i>Siem Reap Province                </a>
//          </span>
//          </span>
//          </div>
//          <small class=\"ps-stream-time\" data-timestamp=\"1528749581\">
//              <a href=\"https://demo.peepso.com/activity/?status/2-2-1528720781/\">
//                  <span class=\"ps-js-autotime\" data-timestamp=\"1528749581\" title=\"June 11, 2018 8:39 pm\">4 months ago</span>                </a>
//          </small>
//                      <span class=\"ps-dropdown ps-dropdown-privacy ps-stream-privacy ps-js-dropdown ps-js-privacy--482\">
//              <a href=\"#\" data-value=\"\" class=\"ps-dropdown__toggle ps-js-dropdown-toggle\">
//                  <span class=\"dropdown-value\">
//                      <i class=\"ps-icon-globe\"></i>                 </span>
//              <!--<span class=\"dropdown-caret ps-icon-caret-down\"></span>-->
//              </a>
//              <input type=\"hidden\" id=\"_privacy_wpnonce_482\" name=\"_privacy_wpnonce_482\" value=\"0e2e69cadc\"><input type=\"hidden\" name=\"_wp_http_referer\" value=\"/peepsoajax/activity.show_posts_per_page\">              <div class=\"ps-dropdown__menu ps-js-dropdown-menu\"><a href=\"#\" data-option-value=\"10\" onclick=\"return activity.change_post_privacy(this, 482)\"><i class=\"ps-icon-globe\"></i><span>Public</span></a><a href=\"#\" data-option-value=\"20\" onclick=\"return activity.change_post_privacy(this, 482)\"><i class=\"ps-icon-users\"></i><span>Site Members</span></a><a href=\"#\" data-option-value=\"30\" onclick=\"return activity.change_post_privacy(this, 482)\"><i class=\"ps-icon-user2\"></i><span>Friends Only</span></a><a href=\"#\" data-option-value=\"40\" onclick=\"return activity.change_post_privacy(this, 482)\"><i class=\"ps-icon-lock\"></i><span>Only Me</span></a></div>         </span>
//                  </div>
//      <!-- post options -->
//      <div class=\"ps-stream-options\">
//          <div class=\"ps-dropdown ps-dropdown--stream ps-js-dropdown\">
//<a href=\"#\" class=\"ps-dropdown__toggle ps-js-dropdown-toggle\" data-value=\"\">
//<span class=\"dropdown-caret ps-icon-caret-down\"></span>
//</a>
//<div class=\"ps-dropdown__menu ps-js-dropdown-menu\">
//<a href=\"#\" onclick=\"activity.option_edit(930, 482); return false\" data-post-id=\"930\"><i class=\"ps-icon-edit\"></i><span>Edit Post</span>
//</a>
//<a href=\"#\" onclick=\"return peepso.photos.delete_stream_album(930,482);\" data-post-id=\"930\"><i class=\"ps-icon-trash\"></i><span>Delete Album</span>
//</a>
//<a href=\"#\" onclick=\"return activity.action_pin(930, 1);\" data-post-id=\"930\"><i class=\"ps-icon-move-up\"></i><span>Pin to top</span>
//</a>
//<a href=\"#\" onclick=\"return activity.action_pin(930, 0);\" data-post-id=\"930\"><i class=\"ps-icon-move-down\"></i><span>Unpin</span>
//</a>
//<a href=\"#\" onclick=\"window.open(&quot;https://demo.peepso.com/profile/demo/&quot;, &quot;_blank&quot;);return false\" data-post-id=\"930\"><i class=\"ps-icon-info-circled\"></i><span>Pinned by Patricia</span>
//</a>
//<a href=\"#\" class=\"active\" onclick=\"return false\" data-post-id=\"930\"><i class=\"ps-icon-calendar\"></i><span>Pinned June 11, 2018 at 8:39 pm</span>
//</a>
//</div>
//</div>
//      </div>
//  </div>";
//}



// helper method to get the appropriate mood
//
    public static function get_mood_template($mood){
        $mood = trim($mood);
        if(!isset($mood) || empty($mood)){
            return;
        }

        switch ($mood){

            case trim(self::$JOYFUL):
                return "&nbsp;&nbsp;<i class=\"ps-emoticon ps-emoticon ps-emo-1\"></i><span>&nbsp;feeling {$mood}&nbsp;&nbsp;</span>";
                break;
            case trim(self::$MEH):
                return "&nbsp;&nbsp;<i class=\"ps-emoticon ps-emoticon ps-emo-2\"></i><span>&nbsp;feeling {$mood}&nbsp;&nbsp;</span>";
                break;
            case trim(self::$LOVE):
                return "&nbsp;&nbsp;<i class=\"ps-emoticon ps-emoticon ps-emo-3\"></i><span>&nbsp;feeling {$mood}&nbsp;&nbsp;</span>";
                break;
            case trim(self::$FLATTERED):
                return "&nbsp;&nbsp;<i class=\"ps-emoticon ps-emoticon ps-emo-4\"></i><span>&nbsp;feeling {$mood}&nbsp;&nbsp;</span>";
                break;
            case trim(self::$CRAZY):
                return "&nbsp;&nbsp;<i class=\"ps-emoticon ps-emoticon ps-emo-5\"></i><span>&nbsp;feeling {$mood}&nbsp;&nbsp;</span>";
                break;
            case trim(self::$COOL):
                return "&nbsp;&nbsp;<i class=\"ps-emoticon ps-emoticon ps-emo-6\"></i><span>&nbsp;feeling {$mood}&nbsp;&nbsp;</span>";
                break;
            case trim(self::$TIRED):
                return "&nbsp;&nbsp;<i class=\"ps-emoticon ps-emoticon ps-emo-7\"></i><span>&nbsp;feeling {$mood}&nbsp;&nbsp;</span>";
                break;
            case trim(self::$CONFUSED):
                return "&nbsp;&nbsp;<i class=\"ps-emoticon ps-emoticon ps-emo-8\"></i><span>&nbsp;&nbsp;&nbsp;feeling {$mood}&nbsp;&nbsp;&nbsp;&nbsp;</span>";
                break;
            case trim(self::$SPEECCHLESS):
                return "&nbsp;&nbsp;<i class=\"ps-emoticon ps-emoticon ps-emo-9\"></i><span>&nbsp;feeling {$mood}&nbsp;&nbsp;</span>";
                break;
            case trim(self::$CONFIDENT):
                return "&nbsp;&nbsp;<i class=\"ps-emoticon ps-emoticon ps-emo-10\"></i><span>&nbsp;feeling {$mood}&nbsp;&nbsp;</span>";
                break;
            case trim(self::$RELAXED):
                return "&nbsp;&nbsp;<i class=\"ps-emoticon ps-emoticon ps-emo-11\"></i><span>&nbsp;feeling {$mood}&nbsp;&nbsp;</span>";
                break;
            case trim(self::$STRONG):
                return "&nbsp;&nbsp;<i class=\"ps-emoticon ps-emoticon ps-emo-12\"></i><span>&nbsp;feeling {$mood}&nbsp;&nbsp;</span>";
                break;
            case trim(self::$HAPPY):
                return "&nbsp;&nbsp;<i class=\"ps-emoticon ps-emoticon ps-emo-13\"></i><span>&nbsp;feeling {$mood}&nbsp;&nbsp;</span>";
                break;
            case trim(self::$ANGRY):
                return "&nbsp;&nbsp;<i class=\"ps-emoticon ps-emoticon ps-emo-15\"></i><span>&nbsp;feeling {$mood}&nbsp;&nbsp;</span>";
                break;
            case trim(self::$SAD):
                return "&nbsp;&nbsp;<i class=\"ps-emoticon ps-emoticon ps-emo-16\"></i><span>&nbsp;feeling {$mood}&nbsp;&nbsp;</span>";
                break;
            case trim(self::$SICK):
                return "&nbsp;&nbsp;<i class=\"ps-emoticon ps-emoticon ps-emo-17\"></i><span>&nbsp;feeling {$mood}&nbsp;&nbsp;</span>";
                break;
            case trim(self::$BLESSED):
                return "&nbsp;&nbsp;<i class=\"ps-emoticon ps-emoticon ps-emo-18\"></i><span>&nbsp;feeling {$mood}&nbsp;&nbsp;</span>";
                break;
            default : return false;
        }



    }
    public function test(){



        return "<div id=\"postbox-mood\" class=\"ps-dropdown__menu ps-dropdown__menu--moods ps-js-postbox-mood placeholder\" style=\"display: flex;\">
                            
                <a class=\"mood-list\" id=\"postbox-mood-1\" href=\"javascript:\" data-option-value=\"1\" data-option-display-value=\"joyful\">
                    <i class=\"ps-emoticon ps-emo-1\"></i><span>joyful</span>
                </a>
                <a class=\"mood-list\" id=\"postbox-mood-2\" href=\"javascript:\" data-option-value=\"2\" data-option-display-value=\"meh\">
                    <i class=\"ps-emoticon ps-emo-2\"></i><span>meh</span>
                </a>
                <a class=\"mood-list\" id=\"postbox-mood-3\" href=\"javascript:\" data-option-value=\"3\" data-option-display-value=\"love\">
                    <i class=\"ps-emoticon ps-emo-3\"></i><span>love</span>
                </a>
                <a class=\"mood-list\" id=\"postbox-mood-4\" href=\"javascript:\" data-option-value=\"4\" data-option-display-value=\"flattered\">
                    <i class=\"ps-emoticon ps-emo-4\"></i><span>flattered</span>
                </a>
                <a class=\"mood-list\" id=\"postbox-mood-5\" href=\"javascript:\" data-option-value=\"5\" data-option-display-value=\"crazy\">
                    <i class=\"ps-emoticon ps-emo-5\"></i><span>crazy</span>
                </a>
                <a class=\"mood-list\" id=\"postbox-mood-6\" href=\"javascript:\" data-option-value=\"6\" data-option-display-value=\"cool\">
                    <i class=\"ps-emoticon ps-emo-6\"></i><span>cool</span>
                </a>
                <a class=\"mood-list\" id=\"postbox-mood-7\" href=\"javascript:\" data-option-value=\"7\" data-option-display-value=\"tired\">
                    <i class=\"ps-emoticon ps-emo-7\"></i><span>tired</span>
                </a>
                <a class=\"mood-list\" id=\"postbox-mood-8\" href=\"javascript:\" data-option-value=\"8\" data-option-display-value=\"confused\">
                    <i class=\"ps-emoticon ps-emo-8\"></i><span>confused</span>
                </a>
                <a class=\"mood-list\" id=\"postbox-mood-9\" href=\"javascript:\" data-option-value=\"9\" data-option-display-value=\"speechless\">
                    <i class=\"ps-emoticon ps-emo-9\"></i><span>speechless</span>
                </a>
                <a class=\"mood-list\" id=\"postbox-mood-10\" href=\"javascript:\" data-option-value=\"10\" data-option-display-value=\"confident\">
                    <i class=\"ps-emoticon ps-emo-10\"></i><span>confident</span>
                </a>
                <a class=\"mood-list\" id=\"postbox-mood-11\" href=\"javascript:\" data-option-value=\"11\" data-option-display-value=\"relaxed\">
                    <i class=\"ps-emoticon ps-emo-11\"></i><span>relaxed</span>
                </a>
                <a class=\"mood-list\" id=\"postbox-mood-12\" href=\"javascript:\" data-option-value=\"12\" data-option-display-value=\"strong\">
                    <i class=\"ps-emoticon ps-emo-12\"></i><span>strong</span>
                </a>
                <a class=\"mood-list\" id=\"postbox-mood-13\" href=\"javascript:\" data-option-value=\"13\" data-option-display-value=\"happy\">
                    <i class=\"ps-emoticon ps-emo-13\"></i><span>happy</span>
                </a>
                <a class=\"mood-list\" id=\"postbox-mood-14\" href=\"javascript:\" data-option-value=\"14\" data-option-display-value=\"angry\">
                    <i class=\"ps-emoticon ps-emo-14\"></i><span>angry</span>
                </a>
                <a class=\"mood-list\" id=\"postbox-mood-15\" href=\"javascript:\" data-option-value=\"15\" data-option-display-value=\"scared\">
                    <i class=\"ps-emoticon ps-emo-15\"></i><span>scared</span>
                </a>
                <a class=\"mood-list\" id=\"postbox-mood-16\" href=\"javascript:\" data-option-value=\"16\" data-option-display-value=\"sick\">
                    <i class=\"ps-emoticon ps-emo-16\"></i><span>sick</span>
                </a>
                <a class=\"mood-list\" id=\"postbox-mood-17\" href=\"javascript:\" data-option-value=\"17\" data-option-display-value=\"sad\">
                    <i class=\"ps-emoticon ps-emo-17\"></i><span>sad</span>
                </a>
                <a class=\"mood-list\" id=\"postbox-mood-18\" href=\"javascript:\" data-option-value=\"18\" data-option-display-value=\"blessed\">
                    <i class=\"ps-emoticon ps-emo-18\"></i><span>blessed</span>
                </a>
                            <button id=\"postbox-mood-remove\" class=\"ps-btn ps-btn-danger ps-remove-location\" style=\"width:100%; display:none\"><i class=\"ps-icon-remove\"></i>Remove Mood</button>
                        </div>";
    }

    public static function get_fullname($firstname,$lastname){

        return "<div class=\"ps-stream-header\"><div class=\"ps-stream-meta\"><div class=\"reset-gap\"><a class=\"ps-stream-user\" href=\"https://demo.peepso.com/profile/demo/\">". $firstname." ".$lastname."</a>";
    }

    public static function get_post_title($count,$label){

        $photos_string = ((int)$count === 1) ? "a photo" : "{$count} photos";
        return "<span class=\"ps-stream-action-title\"> uploaded {$photos_string} about a  <a href=\"https://demo.peepso.com/profile/demo/photos/album/37\">".h($label)."  issue</a></span>";
    }

    public static function get_location_template($location){

        return trim("<span class=\"ps-js-activity-extras\">         <span>
                <a href=\"#\" title=\"Siem Reap Province\" onclick=\"pslocation.show_map(13.6915377, 104.10013260000005, 'Siem Reap Province'); return false;\">
                    <span class=\"at-location ps-js-autotime\">@</span> <i class=\"ps-icon-map-marker\"></i>".$location."</a>
            </span>
            </span></div>");
    }

    public static function get_time_template($time){

        return "<small class=\"ps-stream-time\" data-timestamp=\"1528749581\">
                <a href=\"https://demo.peepso.com/activity/?status/2-2-1528720781/\">
                    <span class=\"ps-js-autotime\" data-timestamp=\"1528749581\" title=\"June 11, 2018 8:39 pm\">".self::time_converter($time)."</span>             </a>
            </small></div></div>";
    }

    public static function get_stream_options_template($post_id,$uploader_id){

        return "<div class=\"ps-stream-options\">
            <div class=\"ps-dropdown ps-dropdown--stream ps-js-dropdown\">
<a href=\"#\" class=\"ps-dropdown__toggle ps-js-dropdown-toggle\" data-value=\"\">
<span class=\"dropdown-caret ps-icon-caret-down\"></span>
</a>
<div class=\"ps-dropdown__menu ps-js-dropdown-menu\">
<a href=\"#\" onclick=\"activity.option_edit(930, 482); return false\" data-post-id=\"930\"><i class=\"ps-icon-edit\"></i><span>Edit Post</span>
</a>
<a href=\"#\" onclick=\"return peepso.photos.delete_stream_album(930,482);\" data-post-id=\"930\"><i class=\"ps-icon-trash\"></i><span>Delete Album</span>
</a>
<a href=\"#\" onclick=\"return activity.action_pin(930, 1);\" data-post-id=\"930\"><i class=\"ps-icon-move-up\"></i><span>Pin to top</span>
</a>
<a href=\"#\" onclick=\"return activity.action_pin(930, 0);\" data-post-id=\"930\"><i class=\"ps-icon-move-down\"></i><span>Unpin</span>
</a>
<a href=\"#\" onclick=\"window.open(&quot;https://demo.peepso.com/profile/demo/&quot;, &quot;_blank&quot;);return false\" data-post-id=\"930\"><i class=\"ps-icon-info-circled\"></i><span>Pinned by Patricia</span>
</a>
<a href=\"#\" class=\"active\" onclick=\"return false\" data-post-id=\"930\"><i class=\"ps-icon-calendar\"></i><span>Pinned June 11, 2018 at 8:39 pm</span>
</a>
</div>
</div>
        </div>";
    }
    
    // get the recently uploaded post
    public static function get_uploaded_post($post_id)
    {
        global $db;
		
	
                $query = " 
				SELECT firstname,lastname,".PostImage::$table_name.".* ,
				(SELECT COUNT(*) FROM ".FetchPost::$table_name." WHERE post_id ={$post_id}) AS count 
				FROM ".PostImage::$table_name."  JOIN ".user::$table_name." ON 
				".PostImage::$table_name.".uploader_id = ".user::$table_name.".id WHERE 
				".PostImage::$table_name.".id = {$post_id} LIMIT 1";
    $result_array = [];
      
	if($result = $db->query($query)){
       
       if($row = $result->fetch_assoc()){
		  
            $result_array = $row;
        }
	}
	
        return $result_array;
    
    }// get_uploaded_post();
    
	
	public static function fetch_images($post_ids = [])
	{
		global $db;
		$query = "SELECT * FROM ".self::$table_name." WHERE post_id = ? ";
		
		$stmt = $db->prepare($query);
		
		if(!$stmt)
		{
			log_action(__CLASS__,"Statement preparation failed on line ".__LINE__." in ".__FILE__);
			
		}
		
	   if(!$stmt->bind_param("i",$post_ids)){
		   log_action(__CLASS__,"Statement binding failed on line ".__LINE__." in ".__FILE__);
	   }	
	   
	   if(!$stmt->execute()){
		   log_action(__CLASS__,"Statement execution failed on line ".__LINE__." in ".__FILE__);
	   }
	   
	   if(is_array($post_ids)){
		   foreach($post_ids as $post_id)
		   {
			   if(!$stmt->execute())
			   {
				   log_action(__CLASS__,"Statement execution of post_id {$post_id} failed on line ".__LINE__." in ".__FILE__);
			   }
		   }
		   
	   }
	   
	   return $stmt->get_result();
	}// fetch_images();
	
	
	

    public static function get_label_template($label){

        return "<div class=\"ps-stream ps-js-activity ps-stream__post--pinned ps-js-activity-pinned ps-js-activity--482\">
     <div class=\"ps-stream__post-pin\" style=\"display:block\">
        <span style=\"background-color: rgb(210, 73, 66);\">".$label."</span>
            </div>";
    }

    public static function time_converter($upload_time){

        $upload_time = (integer)$upload_time;
        if (isset($upload_time) && !empty($upload_time) && is_integer($upload_time)) {

            if ((time() - $upload_time) > -1 ) {
                try {

                    //echo $upload_time
                    $min = 60;
                    $hr = $min * 60;
                    $day = $hr * 24;
                    $mid_night = $hr * 18;
                    $wk = $day * 7;
                    $mon = ($wk * 4) + 2;
                    $yr = $mon * 12;

                    $upload_time = time()  - $upload_time;
//             echo "mon: ".$mon ." upload_time ". $upload_time;
//             $abs = abs(($mon - $upload_time));
//             echo "difference between them: ".$abs;
//             echo "the number of days of days are: ". ceil($abs/ $day)."<br />";

                    if ($upload_time <= $min) {
                        return "just now";

                        //if it is more than or equal to a minute but less than or equal to an hour
                    } elseif($upload_time > $min && $upload_time < $hr) {
                        // if it is exactly a minute
                        if(round($upload_time/$min) == 1){
                            return "a minute ago";
                            //if it is exactly an hour
                        }else{
                            // if it is between an hour and a minute
                            return round($upload_time/$min)." minutes ago";
                        }
                        //  greater than an hour and less than a day
                    } elseif($upload_time >= $hr && $upload_time < $day) {
                        // about 18 hours ago
                        if((int)round($upload_time / $hr) === 1) {

                            return "about an hour ago";
                            // if the it is greater than an hour and less than $mid_night hours
                        }elseif(round($upload_time / $hr) > 1 && round($upload_time / $hr) < $mid_night){

                            return  ceil($upload_time / $hr) . " hrs ago";

                        }elseif(floor($upload_time /$hr) >= $mid_night) {
                            return "since yesterday";
                        }else{
                            return "some time ago";
                        }
                        // greater than or equal to a day and less than or equal to a week
                    }elseif($upload_time  >= $day && $upload_time <= $wk) {
                        // if it is just a day ago
                        if(ceil($upload_time / $day) == 1){
                            return "since yesterday";
                        }elseif($upload_time == $wk){
                            return "a week ago";
                        }else{
                            // within the week
                            return ceil( $upload_time / $day) . " days ago";
                        }
                        // greater than or equal to a week or less than or equal to a month
                    } elseif($upload_time >= $wk && $upload_time <= $mon) {
                        if($upload_time == $wk){
                            return "a week ago";

                        }elseif ($upload_time > $wk && $upload_time < $mon) {
                            // return only the weeks
                            return  ceil($upload_time / $wk). " weeks ago";
                        }elseif($upload_time > $wk && $upload_time == $mon){

                            return "a month ago";
                        }else{
                            return "some time ago";
                        }
                    }elseif($upload_time >= $mon && $upload_time <= $yr ){
                        // if it is a month and some weeks

                        if(($upload_time > $wk && $upload_time >= $mon) &&
                            ((int)floor($upload_time / $mon)  === 1) &&
                            $upload_time < (($mon * 2)-($day * 6))){
//
                            return "a month ".self::sub_time_converter($upload_time,$mon,$wk,"weeks")." ago";

                        }elseif(($upload_time > $wk && $upload_time >= $mon) &&
                            ((int)floor($upload_time / $mon)  === 1) &&
                            $upload_time < ($mon * 2)){

                            return "about ".round($upload_time / $mon)." months ".self::sub_time_converter($upload_time,$mon,$wk,"weeks")." ago";;

                        }elseif($upload_time > $mon && $upload_time > $wk && $upload_time == $yr){
                            return "about a year ago";
                        }else{
                            return "some time ago";
                        }

                    }elseif($upload_time >= $yr && $upload_time <= ($yr * 10)){
                        if($upload_time == $yr){
                            return "about a year ago";
                        }elseif($upload_time > $yr && $upload_time < ($yr * 10)){

                            return "10 years ago";
                        }elseif($upload_time == ($yr * 10)){
                            return "about 10 years ago";
                        }elseif($upload_time > ($yr * 10)){
                            return "more than 10 years ago";
                        }else{
                            return "some time ago";
                        }
                    }else{
                        return "some time ago";
                    }
                }catch (Exception $e) {
                    return $e;
                }
            }
        }

        return "some time ago" ;
    }



    public static  function sub_time_converter($dividend = 0,$divisor = 0,$fallback = 0,$fallback_name = ""){

        if(isset($divisor) && !empty($divisor)
            && isset($divisor) && !empty($dividend)
            && isset($fallback_name) && !empty(trim($fallback_name))
            && fmod($dividend,$divisor) > 0 ){

            $time =fmod($dividend,$divisor) ;
            if(floor($time/$fallback) > 1 && round($time/$fallback) < 4){
                return "and ".round($time/$fallback)." $fallback_name ";
            }

        }

    }

// GET THE FULL HEADER
// brings back the header of the post
    public static function get_full_post($posts_info = "",$images = "",$flag = ""){

        $headers = [];
	
   if(empty($posts_info) || empty($images)){
      log_action(__CLASS__," {$flag}");
    return;   
   }
        // fetch the do be displayed post from the database
        //$posts = self::top_trends("");
    print_r($posts_info);
	return;
        // for every single post,...
        foreach ($posts_info as $post_info) {
//            $images = jd(jd($post_info["post"]));
//            $count  = count($images);
//            foreach($images as $image){
//                $image = explode("/",$image);
//                $images[] = $image[(count($image) - 1)];
//            }
    
            $full_header = "";
           // $full_body   = self::get_post_body_wrapper($images,$post_info["caption"],$post_info["count"],$post_info["id"],$post_info["support"],$post_info["oppose"]);
              $full_body = "";
			// brings back the begining of the post wrapper too
            $full_header  = self::get_label_template($post_info["label"]);
            // gets the full name
            $full_header .= self::get_fullname($post_info["firstname"],$post_info["lastname"]);
            // gets the number of files uploaded and label of the issue
            $full_header .= self::get_post_title($count,$post_info["label"]);
            // gets the mood of the post
            $full_header .= self::get_mood_template($post_info["mood"]);
            // gets the location of the post
            $full_header .= self::get_location_template($post_info["location"]);
            // gets the time the post was uploaded
            $full_header .= self::get_time_template($post_info["upload_time"]);
            $headers[$post_info["id"]] = $full_header.$full_body;
        }
        print j($headers);
        return;
    }

    public static function get_post_files_display($files){

        return "<div class=\"ps-stream-attachments cstream-attachments\"><div class=\"cstream-attachment photo-attachment\">
	<div class=\"ps-media-photos ps-media-grid  ps-clearfix\" data-ps-grid=\"photos\" style=\"position: relative; width: 100%; max-width: 600px; min-width: 200px; max-height: 1200px; overflow: hidden;\">
		<a href=\" ://demo.peepso.com/activity/?status/2-2-1528720781/\" class=\"ps-media-photo ps-media-grid-item \" data-ps-grid-item=\"\" onclick=\"return ps_comments.open(200, 'photo');\" style=\"float: left; width: 50%; padding-top: 50%;\">
	<div class=\"ps-media-grid-padding\">
		<div class=\"ps-media-grid-fitwidth\">
			<img src=\"../lqUgAuP7zZlempzC9gN9lIm8yiqnAYfExk/FnjP4kkPmLiF3lAq1nHx7AnbiBTogWwfhvTI/IPmHxc3tDbNYWfiGz6FB5LHml2RJNTykk6uxED1bLs/5ae0d503466b11.15907101.png\" class=\"ps-js-fitted\" style=\"width: auto; height: 100%;\">
								</div>
	</div>
</a>
<a href=\" ://demo.peepso.com/activity/?status/2-2-1528720781/\" class=\"ps-media-photo ps-media-grid-item \" data-ps-grid-item=\"\" onclick=\"return ps_comments.open(201, 'photo');\" style=\"float: left; width: 50%; padding-top: 50%;\">
	<div class=\"ps-media-grid-padding\">
		<div class=\"ps-media-grid-fitwidth\">
			<img src=\"../lqUgAuP7zZlempzC9gN9lIm8yiqnAYfExk/FnjP4kkPmLiF3lAq1nHx7AnbiBTogWwfhvTI/IPmHxc3tDbNYWfiGz6FB5LHml2RJNTykk6uxED1bLs/5ae0d503466b11.15907101.png\" class=\"ps-js-fitted\" style=\"width: auto; height: 100%;\">
								</div>
	</div>
</a>
<a href=\" ://demo.peepso.com/activity/?status/2-2-1528720781/\" class=\"ps-media-photo ps-media-grid-item \" data-ps-grid-item=\"\" onclick=\"return ps_comments.open(195, 'photo');\" style=\"float: left; width: 33.3%; padding-top: 33.3%;\">
	<div class=\"ps-media-grid-padding\">
		<div class=\"ps-media-grid-fitwidth\">
			<img src=\"../lqUgAuP7zZlempzC9gN9lIm8yiqnAYfExk/FnjP4kkPmLiF3lAq1nHx7AnbiBTogWwfhvTI/IPmHxc3tDbNYWfiGz6FB5LHml2RJNTykk6uxED1bLs/5ae0d503466b11.15907101.png\" class=\"ps-js-fitted\" style=\"width: auto; height: 100%;\">
								</div>
	</div>
</a>
<a href=\" ://demo.peepso.com/activity/?status/2-2-1528720781/\" class=\"ps-media-photo ps-media-grid-item \" data-ps-grid-item=\"\" onclick=\"return ps_comments.open(196, 'photo');\" style=\"float: left; width: 33.3%; padding-top: 33.3%;\">
	<div class=\"ps-media-grid-padding\">
		<div class=\"ps-media-grid-fitwidth\">
			<img src=\"../lqUgAuP7zZlempzC9gN9lIm8yiqnAYfExk/FnjP4kkPmLiF3lAq1nHx7AnbiBTogWwfhvTI/IPmHxc3tDbNYWfiGz6FB5LHml2RJNTykk6uxED1bLs/5ae0d503466b11.15907101.png\" class=\"ps-js-fitted\" style=\"width: auto; height: 100%;\">
								</div>
	</div>
</a>
<a href=\" ://demo.peepso.com/activity/?status/2-2-1528720781/\" class=\"ps-media-photo ps-media-grid-item \" data-ps-grid-item=\"\" onclick=\"return ps_comments.open(197, 'photo');\" style=\"float: left; width: 33.3%; padding-top: 33.3%;\">
	<div class=\"ps-media-grid-padding\">
		<div class=\"ps-media-grid-fitwidth\">
			<img src=\"../lqUgAuP7zZlempzC9gN9lIm8yiqnAYfExk/FnjP4kkPmLiF3lAq1nHx7AnbiBTogWwfhvTI/IPmHxc3tDbNYWfiGz6FB5LHml2RJNTykk6uxED1bLs/5ae0d503466b11.15907101.png\" class=\"ps-js-fitted\" style=\"width: 100%; height: auto;\">
									<div class=\"ps-media-photo-counter\" style=\"top:0; left:0; right:0; bottom:0;\">
				<span>+6</span>
			</div>
					</div>
	</div>
</a>
		
	</div>
</div>
</div>";
    }


// brings back the template of the the image file with the dackened overlay

// brings back the dody of the post
    public static function get_post_full_body(){


        return "<div id=\"ps-activitystream-recent\" class=\"ps-stream-container\" style=\"\"><div class=\"ps-stream ps-js-activity  ps-js-activity--507\" data-id=\"507\" data-post-id=\"965\" style=\"\">

	
	<div class=\"ps-stream__post-pin\" style=\"\">
		<span style=\"background-color: rgb(210, 73, 66);\">Pinned</span>
        	</div>

	<div class=\"ps-stream-header\">

		<!-- post author avatar -->
		
		<!-- post meta -->
		<div class=\"ps-stream-meta\">
			<div class=\"reset-gap\">
				<a class=\"ps-stream-user\" href=\"https://demo.peepso.com/profile/demo/\"> Patricia Currie</a> <span class=\"ps-stream-action-title\"> uploaded 6 photos</span> 				<span class=\"ps-js-activity-extras\">			<span>
				<i class=\"ps-emoticon ps-emo-1\"></i>
				<span> feeling Joyful</span>
			</span>
			 			<span>
                <a href=\"#\" title=\"Black Park Ltd. (Tesano)\" onclick=\"pslocation.show_map(5.5984168, -0.22774119999996856, 'Black Park Ltd. (Tesano)'); return false;\">
                    <i class=\"ps-icon-map-marker\"></i>Black Park Ltd. (Tesano)                </a>
			</span>
			</span>
			</div>
			<small class=\"ps-stream-time\" data-timestamp=\"1538524754\">
				<a href=\"https://demo.peepso.com/activity/?status/2-2-1538495954/\">
					<span class=\"ps-js-autotime\" data-timestamp=\"1538524754\" title=\"October 2, 2018 11:59 pm\">10 mins ago</span>				</a>
			</small>
						
					</div>
		<!-- post options -->
		<div class=\"ps-stream-options\">
			<div class=\"ps-dropdown ps-dropdown--stream ps-js-dropdown\">
<a href=\"#\" class=\"ps-dropdown__toggle ps-js-dropdown-toggle\" data-value=\"\">
<span class=\"dropdown-caret ps-icon-caret-down\"></span>
</a>
<div class=\"ps-dropdown__menu ps-js-dropdown-menu\" style=\"display: none;\">
<a href=\"#\" onclick=\"activity.option_edit(965, 507); return false\" data-post-id=\"965\"><i class=\"ps-icon-edit\"></i><span>Edit Post</span>
</a>
<a href=\"#\" onclick=\"return activity.action_delete(965);\" data-post-id=\"965\"><i class=\"ps-icon-trash\"></i><span>Delete Post</span>
</a>
<a href=\"#\" onclick=\"return activity.action_pin(965, 1);\" data-post-id=\"965\"><i class=\"ps-icon-move-up\"></i><span>Pin to top</span>
</a>
</div>
</div>
		</div>
	</div>

	<!-- post body -->
	<div class=\"ps-stream-body\">
		<div class=\"ps-stream-attachment cstream-attachment ps-js-activity-content ps-js-activity-content--507\"><div class=\"peepso-markdown\"><p>Test post just now</p></div></div>
		<div class=\"ps-js-activity-edit ps-js-activity-edit--507\" style=\"display:none\"></div>
		<div class=\"ps-stream-attachments cstream-attachments\"><div class=\"cstream-attachment photo-attachment\">
	<div class=\"ps-media-photos ps-media-grid  ps-clearfix\" data-ps-grid=\"photos\" style=\"position: relative; width: 100%; max-width: 600px; min-width: 200px; max-height: 1200px; overflow: hidden;\">
		<a href=\"https://demo.peepso.com/activity/?status/2-2-1538495954/\" class=\"ps-media-photo ps-media-grid-item \" data-ps-grid-item=\"\" onclick=\"return ps_comments.open(202, 'photo');\" style=\"float: left; width: 50%; padding-top: 50%;\">
	<div class=\"ps-media-grid-padding\">
		<div class=\"ps-media-grid-fitwidth\">
			<img src=\"https://demo.peepso.com/wp-content/peepso/users/2/photos/PRIVATE_MEDIA."/"/31d0a1284e65a77d610f976e2d1cecf7_l.jpg\" class=\"ps-js-fitted\" style=\"width: auto; height: 100%;\">
								</div>
	</div>
</a>
<a href=\"https://demo.peepso.com/activity/?status/2-2-1538495954/\" class=\"ps-media-photo ps-media-grid-item \" data-ps-grid-item=\"\" onclick=\"return ps_comments.open(203, 'photo');\" style=\"float: left; width: 50%; padding-top: 50%;\">
	<div class=\"ps-media-grid-padding\">
		<div class=\"ps-media-grid-fitwidth\">
			<img src=\"https://demo.peepso.com/wp-content/peepso/users/2/photos/PRIVATE_MEDIA."/"/c02256ddf00f01ad010f80e7ddb399ce_l.jpg\" class=\"ps-js-fitted\" style=\"width: auto; height: 100%;\">
								</div>
	</div>
</a>
<a href=\"https://demo.peepso.com/activity/?status/2-2-1538495954/\" class=\"ps-media-photo ps-media-grid-item \" data-ps-grid-item=\"\" onclick=\"return ps_comments.open(204, 'photo');\" style=\"float: left; width: 33.3%; padding-top: 33.3%;\">
	<div class=\"ps-media-grid-padding\">
		<div class=\"ps-media-grid-fitwidth\">
			<img src=\"https://demo.peepso.com/wp-content/peepso/users/2/photos/PRIVATE_MEDIA."/"/c5e99fcf8efd5547cf6c2efcc3eb82a3_l.jpg\" class=\"ps-js-fitted\" style=\"width: auto; height: 100%;\">
								</div>
	</div>
</a>
<a href=\"https://demo.peepso.com/activity/?status/2-2-1538495954/\" class=\"ps-media-photo ps-media-grid-item \" data-ps-grid-item=\"\" onclick=\"return ps_comments.open(205, 'photo');\" style=\"float: left; width: 33.3%; padding-top: 33.3%;\">
	<div class=\"ps-media-grid-padding\">
		<div class=\"ps-media-grid-fitwidth\">
			<img src=\"https://demo.peepso.com/wp-content/peepso/users/2/photos/PRIVATE_MEDIA."/"/2394fed0c093c7451abf41fb64ca1780_l.jpg\" class=\"ps-js-fitted\" style=\"width: auto; height: 100%;\">
								</div>
	</div>
</a>
<a href=\"https://demo.peepso.com/activity/?status/2-2-1538495954/\" class=\"ps-media-photo ps-media-grid-item \" data-ps-grid-item=\"\" onclick=\"return ps_comments.open(206, 'photo');\" style=\"float: left; width: 33.3%; padding-top: 33.3%;\">
	<div class=\"ps-media-grid-padding\">
		<div class=\"ps-media-grid-fitwidth\">
			<img src=\"https://demo.peepso.com/wp-content/peepso/users/2/photos/PRIVATE_MEDIA."/"/4198cdc6af05b8b0faef589d03a5346e_l.jpg\" class=\"ps-js-fitted\" style=\"width: auto; height: 100%;\">
									<div class=\"ps-media-photo-counter\" style=\"top:0; left:0; right:0; bottom:0;\">
				<span>+2</span>
			</div>
					</div>
	</div>
</a>
		
	</div>
</div>
</div>
	</div>

	<!-- post actions -->
	<div class=\"ps-stream-actions stream-actions\" data-type=\"stream-action\"><nav class=\"ps-stream-status-action ps-stream-status-action\">
<a data-stream-id=\"507\" onclick=\"reactions.action_reactions(this, 507); return false;\" href=\"#\" class=\"ps-reaction-toggle--507 ps-js-reaction-toggle ps-icon-reaction liked ps-reaction-emoticon-8\"><img src=\"https://demo.peepso.com/wp-content/plugins/peepso-core/assets/images/ajax-loader.gif\"></a>
</nav>
</div>

				<div id=\"act-reactions-507\" class=\"ps-reactions cstream-reactions-options ps-js-reaction-options ps-js-act-reactions-options--507\" data-count=\"\" style=\"display: none;\">
			<ul class=\"ps-reaction-options\">
									<li>
						<a title=\"Like\" class=\"ps-reaction-emoticon-0 ps-tooltip ps-tooltip--reaction ps-reaction-option ps-reaction-option--507 ps-reaction-option-0--507\" href=\"#\" data-tooltip=\"Like\" onclick=\"reactions.action_react(this, 507, 965, 0); return false;\">
						</a>
					</li>
									<li>
						<a title=\"Love\" class=\"ps-reaction-emoticon-1 ps-tooltip ps-tooltip--reaction ps-reaction-option ps-reaction-option--507 ps-reaction-option-1--507\" href=\"#\" data-tooltip=\"Love\" onclick=\"reactions.action_react(this, 507, 965, 1); return false;\">
						</a>
					</li>
									<li>
						<a title=\"Haha\" class=\"ps-reaction-emoticon-2 ps-tooltip ps-tooltip--reaction ps-reaction-option ps-reaction-option--507 ps-reaction-option-2--507\" href=\"#\" data-tooltip=\"Haha\" onclick=\"reactions.action_react(this, 507, 965, 2); return false;\">
						</a>
					</li>
									<li>
						<a title=\"Wink\" class=\"ps-reaction-emoticon-3 ps-tooltip ps-tooltip--reaction ps-reaction-option ps-reaction-option--507 ps-reaction-option-3--507\" href=\"#\" data-tooltip=\"Wink\" onclick=\"reactions.action_react(this, 507, 965, 3); return false;\">
						</a>
					</li>
									<li>
						<a title=\"Wow\" class=\"ps-reaction-emoticon-4 ps-tooltip ps-tooltip--reaction ps-reaction-option ps-reaction-option--507 ps-reaction-option-4--507\" href=\"#\" data-tooltip=\"Wow\" onclick=\"reactions.action_react(this, 507, 965, 4); return false;\">
						</a>
					</li>
									<li>
						<a title=\"Sad\" class=\"ps-reaction-emoticon-5 ps-tooltip ps-tooltip--reaction ps-reaction-option ps-reaction-option--507 ps-reaction-option-5--507\" href=\"#\" data-tooltip=\"Sad\" onclick=\"reactions.action_react(this, 507, 965, 5); return false;\">
						</a>
					</li>
									<li>
						<a title=\"Angry\" class=\"ps-reaction-emoticon-6 ps-tooltip ps-tooltip--reaction ps-reaction-option ps-reaction-option--507 ps-reaction-option-6--507\" href=\"#\" data-tooltip=\"Angry\" onclick=\"reactions.action_react(this, 507, 965, 6); return false;\">
						</a>
					</li>
									<li>
						<a title=\"Crazy\" class=\"ps-reaction-emoticon-7 ps-tooltip ps-tooltip--reaction ps-reaction-option ps-reaction-option--507 ps-reaction-option-7--507\" href=\"#\" data-tooltip=\"Crazy\" onclick=\"reactions.action_react(this, 507, 965, 7); return false;\">
						</a>
					</li>
									<li>
						<a title=\"Speechless\" class=\"ps-reaction-emoticon-8 ps-tooltip ps-tooltip--reaction ps-reaction-option ps-reaction-option--507 ps-reaction-option-8--507\" href=\"#\" data-tooltip=\"Speechless\" onclick=\"reactions.action_react(this, 507, 965, 8); return false;\">
						</a>
					</li>
									<li>
						<a title=\"Grateful\" class=\"ps-reaction-emoticon-9 ps-tooltip ps-tooltip--reaction ps-reaction-option ps-reaction-option--507 ps-reaction-option-9--507\" href=\"#\" data-tooltip=\"Grateful\" onclick=\"reactions.action_react(this, 507, 965, 9); return false;\">
						</a>
					</li>
									<li>
						<a title=\"Celebrate\" class=\"ps-reaction-emoticon-10 ps-tooltip ps-tooltip--reaction ps-reaction-option ps-reaction-option--507 ps-reaction-option-10--507\" href=\"#\" data-tooltip=\"Celebrate\" onclick=\"reactions.action_react(this, 507, 965, 10); return false;\">
						</a>
					</li>
				
				
				<li class=\"ps-reaction-option-delete--507\" style=\"display: none;\">
					<a class=\"ps-reaction-option ps-reaction-option-delete ps-reaction-option--507 ps-reaction-option-delete--507\" href=\"#\" data-tooltip=\"Remove\" onclick=\"reactions.action_react_delete(this, 507, 965); return false;\" style=\"display: none;\">
					   <i class=\"ps-icon-remove\"></i>
					</a>
				</li>

				</ul>
			</div>
						<div id=\"act-react-507\" class=\"ps-reaction-likes ps-stream-status cstream-reactions ps-js-act-reactions--507 ps-stream-reactions-hidden  \" data-count=\"\" style=\"opacity: 0.5;\">
							</div>
		
		<div id=\"act-like-507\" class=\"ps-stream-status cstream-likes ps-js-act-like--507\" data-count=\"0\" style=\"display:none\"></div>
			<div class=\"ps-comment cstream-respond wall-cocs\" id=\"wall-cmt-507\">
		<div class=\"ps-comment-container comment-container ps-js-comment-container ps-js-comment-container--507\" data-act-id=\"507\" style=\"display: none;\">
					</div>

						<div id=\"act-new-comment-507\" class=\"ps-comment-reply cstream-form stream-form wallform ps-js-comment-new ps-js-newcomment-507\" data-id=\"507\" data-type=\"stream-newcomment\" data-formblock=\"true\">
			<a class=\"ps-avatar cstream-avatar cstream-author\" href=\"https://demo.peepso.com/profile/demo/\">
				<img data-author=\"2\" src=\"https://demo.peepso.com/wp-content/peepso/users/2/avatar-full.jpg\" alt=\"\">
			</a>
			<div class=\"ps-textarea-wrapper cstream-form-input\">
				<div class=\"ps-tagging-wrapper\"><div class=\"ps-tagging-beautifier\"></div><textarea data-act-id=\"507\" class=\"ps-textarea cstream-form-text ps-tagging-textarea\" name=\"comment\" oninput=\"return activity.on_commentbox_change(this);\" placeholder=\"Write a comment...\"></textarea><input type=\"hidden\" class=\"ps-tagging-hidden\"><div class=\"ps-tagging-dropdown\"></div></div>
				<div class=\"ps-commentbox__addons ps-js-addons\">
<div class=\"ps-commentbox__addon ps-js-addon-giphy\" style=\"display:none\">
	<div class=\"ps-popover__arrow ps-popover__arrow--up\"></div>
	<img class=\"ps-js-img\" alt=\"photo\" src=\"\">
	<div class=\"ps-commentbox__addon-remove ps-js-remove\">
		<i class=\"ps-icon-remove\"></i>
	</div>
</div>
<div class=\"ps-commentbox__addon ps-js-addon-photo\" style=\"display:none\">
	<div class=\"ps-popover__arrow ps-popover__arrow--up\"></div>

	<img class=\"ps-js-img\" alt=\"photo\" src=\"\" data-id=\"\">

	<div class=\"ps-loading ps-js-loading\">
		<img src=\"https://demo.peepso.com/wp-content/plugins/peepso-core/assets/images/ajax-loader.gif\" alt=\"loading\">
	</div>

	<div class=\"ps-commentbox__addon-remove ps-js-remove\">
		<input type=\"hidden\" id=\"_wpnonce_remove_temp_comment_photos\" name=\"_wpnonce_remove_temp_comment_photos\" value=\"a42df904ae\"><input type=\"hidden\" name=\"_wp_http_referer\" value=\"/peepsoajax/postbox.post\">		<i class=\"ps-icon-remove\"></i>
	</div>
</div>
</div>
<div class=\"ps-commentbox-actions\">
<a onclick=\"peepso.photos.comment_attach_photo(this); return false;\" title=\"Upload photos\" href=\"#\" class=\"ps-postbox__menu-item ps-icon-camera\"><span></span></a>
<a onclick=\"return false;\" title=\"Send gif\" href=\"#\" class=\"ps-list-item ps-js-comment-giphy ps-icon-giphy\"></a>
</div>
			</div>
			<div class=\"ps-comment-send cstream-form-submit\" style=\"display:none;\">
				<div class=\"ps-comment-loading\" style=\"display:none;\">
					<img src=\"https://demo.peepso.com/wp-content/plugins/peepso-core/assets/images/ajax-loader.gif\" alt=\"\">
					<div> </div>
				</div>
				<div class=\"ps-comment-actions\" style=\"display:none;\">
					<button onclick=\"return activity.comment_cancel(507);\" class=\"ps-btn ps-button-cancel\">Clear</button>
					<button onclick=\"return activity.comment_save(507, this);\" class=\"ps-btn ps-btn-primary ps-button-action\" disabled=\"\">Post</button>
				</div>
			</div>
		</div>
			</div>
</div></div>";
    }

    public static function get_full_image_post(){

        return "<a href=\"https://demo.peepso.com/activity/?status/2-2-1538497253/\" class=\"ps-media-photo ps-media-grid-item \" data-ps-grid-item=\"\" onclick=\"return ps_comments.open(203, 'photo');\" style=\"width: 100%; padding-top: 66.6%;\">
	<div class=\"ps-media-grid-padding\">
		<div class=\"ps-media-grid-fitwidth\">
			<img src=\"https://demo.peepso.com/wp-content/peepso/users/2/photos/PRIVATE_MEDIA."/"/4208a8ecc2060db20502f8f21870c7ea_l.jpg\" class=\"ps-js-fitted\" style=\"width: auto; height: 100%;\">
								</div>
	</div>
</a>";
    }




    public static function get_single_bottom_extra_small_image_template_last($file = "",$count = 0){

        if(empty(trim($file) && is_int($count) && $count > 0 &&
            !file_exists("../".UPLOAD_DIR."/".$file))){
            return null;
        }
        $count = $count - 4;
        return "<a href=\" ://demo.peepso.com/activity/?status/2-2-1528720781/\" class=\"ps-media-photo ps-media-grid-item \" data-ps-grid-item=\"\" onclick=\"return ps_comments.open(197, 'photo');\" style=\"float: left; width: 33.3%; padding-top: 33.3%;\">
	<div class=\"ps-media-grid-padding\">
		<div class=\"ps-media-grid-fitwidth\">
			<img src=\"../".UPLOADS_DIR."/{$file} class=\"ps-js-fitted\" style=\"width: 100%; height: auto;\">
									<div class=\"ps-media-photo-counter\" style=\"top:0; left:0; right:0; bottom:0;\">
				<span>{$count}</span>
			</div>
					</div>
	</div>
</a>";
    }
    public static function get_post_body_wrapper($images = "", $caption = "", $count = 0,$id = 0,$support = 0,$oppose = 0){

        if(!isset($images) || $count > 0 ){

        }
        $caption ? "<p>{$caption}</p>" : "" ;
        return "<div class=\"ps-stream-body\" id=\"post_$id\">
		<div class=\"ps-stream-attachment cstream-attachment ps-js-activity-content ps-js-activity-content--516\">
		<div class=\"peepso-markdown\">{$caption}</div></div>
		<div class=\"ps-js-activity-edit ps-js-activity-edit--516\" style=\"display:none\"></div>
		<div class=\"ps-stream-attachments cstream-attachments\"><div class=\"cstream-attachment photo-attachment\">
	<div class=\"ps-media-photos ps-media-grid  ps-clearfix\" data-ps-grid=\"photos\" style=\"position: relative; width: 100%; max-width: 600px; min-width: 200px; max-height: 1200px; overflow: hidden;\">
		
		".self::get_images_arrangement_template($images,$caption,$id = 0,$count)."<!-- Post Actions -->
		".self::get_reaction_template($support,$oppose,$id)."
	</div></div></div>";
    }

    public static function get_single_top_images_template($image = ""){

        return "<a href=\"https://demo.peepso.com/activity/?status/2-2-1538497582/\" class=\"ps-media-photo ps-media-grid-item \" data-ps-grid-item=\"\" onclick=\"return ps_comments.open(207, 'photo');\" style=\"width: 100%; padding-top: 66.6%;\">
	<div class=\"ps-media-grid-padding\">
		<div class=\"ps-media-grid-fitwidth\">
			<img src=\"../".UPLOAD_DIR."/".$image."\" class=\"ps-js-fitted\" style=\"width: auto; height: 100%;\">
								</div>
	</div>
</a>";
    }

    public static function get_single_file_template($image = ""){

        if(!isset($image) || empty($image)){

            return "the photo {$image} does not exists ";
        }
        return "<a href=\"https://demo.peepso.com/activity/?status/2-2-1538497582/\" class=\"ps-media-photo ps-media-grid-item \" data-ps-grid-item=\"\" onclick=\"return ps_comments.open(207, 'photo');\" style=\"width: 100%; padding-top: 66.6%;\">
	<div class=\"ps-media-grid-padding\">
		<div class=\"ps-media-grid-fitwidth\">
			<img src=\"../".UPLOAD_DIR."/".$image."\" class=\"ps-js-fitted\" style=\"width: auto; height: 100%;\">
								</div>
	</div>
</a>";
    }

    public static function get_single_bottom_extra_small_image_template($file = ""){

        $file ?? "";
        if(empty(trim($file)) && !file_exists($file)){
            return null;
        }

        return "<a href=\"https://demo.peepso.com/activity/?status/2-2-1538497253/\" class=\"ps-media-photo ps-media-grid-item \" data-ps-grid-item=\"\" onclick=\"return ps_comments.open(204, 'photo');\" style=\"float: left; width: 33.3%; padding-top: 33.3%;\">
	<div class=\"ps-media-grid-padding\">
		<div class=\"ps-media-grid-fitwidth\">
			<img src=\"../".UPLOAD_DIR."/".$file."\" class=\"ps-js-fitted\" style=\"width: auto; height: 100%;\">
								</div>
	</div>
</a>";
    }

    public static function get_images_arrangement_template($images = "",$caption = "",$id = 0,$count = 0){


//
//    foreach($images as $image){
//        //check the if they are really populated
//        if(empty(trim($image)) || !is_int($count) || $count < 0 || !file_exists(PRIVATE_MEDIA."/"."/".$image)){
//            return PRIVATE_MEDIA."/"."/".$image;
//        }
//        $image ?? "";
//        $caption ?? "";
//    }



//  return "it processed here";
// if there is only one file then make it fill the entire page
        if((int)$count === 1){

            return self::get_single_file_template($images[0]);

        }

        // declare the variable for the entire images string
        $body_string = "";
        for($x = 0; $x < $count ;$x++){
            if($x < 2){
                // give this to the first two files
                $body_string .= self::get_single_top_images_template($images[$x]);
                continue;
            }

            // this template for the subsequest ones
            $body_string .= self::get_single_bottom_extra_small_image_template($images[$x]);

        }
        // this template for the fourth one then hide the rest to the ui
        if($x == 4){
           
            $body_string .= self::get_single_bottom_extra_small_image_template_last($images[$x],$x);
        }


        return $body_string;
    }

    public static function get_full_image_post_body_display($image = ""){

        if(!isset($image) || empty(trim($image)) || !file_exists("../".UPLOAD_DIR."/".$image)){
            return null;
        }

        return "
<a href=\"https://demo.peepso.com/activity/?status/2-2-1538497582/\" class=\"ps-media-photo ps-media-grid-item \" data-ps-grid-item=\"\" onclick=\"return ps_comments.open(208, 'photo');\" style=\"float: left; width: 50%; padding-top: 33.3%;\">
	<div class=\"ps-media-grid-padding\">
		<div class=\"ps-media-grid-fitwidth\">
			<img src=\"../".UPLOAD_DIR."/".$image."\" class=\"ps-js-fitted\" style=\"width: auto; height: 100%;\">
								</div>
	</div>
</a>
<a href=\"https://demo.peepso.com/activity/?status/2-2-1538497582/\" class=\"ps-media-photo ps-media-grid-item \" data-ps-grid-item=\"\" onclick=\"return ps_comments.open(209, 'photo');\" style=\"float: left; width: 50%; padding-top: 33.3%;\">
	<div class=\"ps-media-grid-padding\">
		<div class=\"ps-media-grid-fitwidth\">
			<img src=\"https://demo.peepso.com/wp-content/peepso/users/2/photos/PRIVATE_MEDIA."/"/2aa084ca66a5bd5f638bf2f6195de775_l.jpg\" class=\"ps-js-fitted\" style=\"width: auto; height: 100%;\">
								</div>
	</div>
</a>
		
	</div>
</div>
</div>
	</div>";
    }


public static function get_reaction_template($support = 0, $oppose = 0,$post_id){

  return "	<!-- post actions -->
<div class=\"ps-stream-actions stream-actions\" data-type=\"stream-action\" style=\"margin-top:0.5em\">
    <nav class=\"ps-stream-status-action ps-stream-status-action\">
<!--<a data-stream-id=\"482\" onclick=\"return reactions.action_reactions(this, 482);\" href=\"javascript:\" class=\"ps-reaction-toggle--482 ps-reaction-emoticon-0 ps-js-reaction-toggle ps-icon-reaction\"><span>Like</span></a>-->
<!--</nav>-->
        <!--Reaction buttons(support)-->
        <input type=\"radio\" name=\"reaction\" id=\"support_$post_id\" value=\"support\" class=\"reactionCheckboxRadio\">
        <label for=\"support_$post_id\"> Suppport(".$support.")</label>
        <!--Reaction buttons(oppose) -->
        <input type=\"radio\" name=\"reaction\" id=\"oppose_$post_id\" value=\"oppose\" class=\"reactionCheckboxRadio\">
        <label for=\"oppose_$post_id\"> Oppose (".$oppose.")</label>

</div>";
}


}



?>