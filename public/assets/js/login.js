
(function($){
  "use strict";
})();
$("#button_login").on("click",function(e){
   
//    if(($.trim($("#email").val()) == "" || $.trim($("#password").val()) == "" || $.trim($("#password").val()).length < 8) && (false === true)){
//      console.log("please the password is first");
//        if($(".errors") != "null" && ($.trim($("#password").val()).length < 1) ){
//            console.log("please the password is second"); 
//            $(".errors").remove();
//             $("#login_err").append("<p class=\"errors\">Please all the fields are required!</p>");
//        }
//        if($.trim($("#password").val()).length >= 1 && ($.trim($("#password").val()).length < 8)){
//            console.log("please the password is 8");
//            $(".errors").remove();
//             $("#login_err").append("<p class=\"errors\">Please the password has to be atleast 8 characters long(without spaces)!</p>");
//        }
//         $("#login_err").show();
//         e.preventDefault();
//        console.log("please the password is after the prevention of default!!!");
//         
//}else{
// console.log("it got here");
   var element = e.target;
    //$(element).attr("disabled","disabled");
    // create a variable(bucket) for the img sibling
    // of the submit button and set it's dispaly 
    // property to inline to show the loading ajax gif
   var bucket = $(element).find("img")[0];
     $(bucket).css("display","inline");
   
  // bucket = $(element).parentsUntil(".login-area")[2];
   $("#form").unbind("submit");  
    $("#form").submit(function(e){

        $.ajax({
            url:"login.php",
            type:"POST",
            data:$("#form").serialize(),
            dataType:"html"
  }).done(function(response,status){
        response = JSON.parse(response);
if(status  === "success"){
	
	$.each(response,function(index,value){
		console.log(value);
		 if(value == true)
		 {
		  location.href="home.php";
		 }else
    if(index == "false"){
     var errors = "";
     $.each(response,function(index,value){
   errors += "<li>"+value+"</li>";
   });  
    $("#login_err").show();
    if($(".errors") != "null"  ){
      $(".errors").remove();
    }
     $("#login_err").append("<ul class=\"errors\">" + errors + "</ul>");  
     $(element).removeAttr("disabled");
     $(bucket).css("display","none");     
      
}
           
});
	}

       
});
e.preventDefault();
//
$(element).attr("disabled","disabled");        
    });

 
});


