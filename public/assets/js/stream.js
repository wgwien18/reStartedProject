


$(window).scroll(function() {
    if ($(window).scrollTop() == $(document).height() - $(window).height()) {
     $("#ps-activitystream-loading").show();
  $.ajax({
			 url:"../private/neutral_ajax.php",
			 type: "POST",
			 data: {request_type: "mainstream"},
			 datatype: "html"
		 }).done(function(response){
			console.log(response);
			response = JSON.parse(response);

			  
			   

			if($.trim(response["pending"]) == "waiting"){
				
			$("#ps-activitystream-loading").show();
			
				 }else{
					 if(response["false"] != undefined && response["false"] == "login"){
 						location.href="login.php";
					 	 return;
						}else if($.trim(response["false"]) != ""){
					 	 utility.showErrorDialogBox("Please it is our fault but please try again.");
					 	  $("#ps-activitystream-loading").hide();
						 return;
					 }
					 
					 if($.trim($("#ps-activitystream")) != "" && $("#ps-activitystream") != null ){
					$.each(response,function(index,value){
					$("#ps-activitystream").append(value);
				 });
				
				  
				  }else{
						 console.log("not found");
					 }
					
					  $("#ps-activitystream-loading").hide();
			
			 }
				 
				  
			/* $("body").append('<div class="big-box"><h1>Page ' + response + '</h1></div>'); 
			*/	
	}).fail(function(error){
			 alert(error);
		 });  
      
    }
});


