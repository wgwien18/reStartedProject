
  class stream {


		static togglePageScroll(toggle = ""){
			if($.trim(toggle) == ""){
				return false;
			}
			
			let toggleElement = $("#page_scroll");
		if($.trim(toggle) == "active" && $(toggleElement).val() == "inactive"){
			
		 $(toggleElement).val("active"); return true;
				
			}else if($.trim(toggle) == "inactive" && $(toggleElement).val() == "active"){
			 $(toggleElement).val("inactive"); return true;
			}else{
				return false;
			}
		}
		
		
	
		static getMainStream(streamType = "", textAreaElement = ""){
	 
		 
		if($.trim(streamType) == "" || $.trim(streamType) != "mainstream"){
		return;
		}
		
		// activate the scrolling of the page
			if($("#page_scroll").val() == "inactive"){
					stream.togglePageScroll("active");
		}
		
	 if($("#ps-activitystream-loading").css("display") == "none"){
			$("#ps-activitystream-loading").show();
	 }
	
		 if($("#stream_type").val() != "mainstream"){
			 $("#stream_type").val("mainstream")
		 }
		
			$("#stream_type").val("");
		 $.ajax({
				 url:"../private/neutral_ajax.php",
				 type: "POST",
				 data: {request_type: "scroll",stream_type:streamType},
				 datatype: "html"
			 }).done(function(response){
				console.log(response);
			try{
				response = JSON.parse(response);
	
			 if(response["false"] != undefined && response["false"] == "login"){
	 location.href="login.php";
			}
			// incase of an error
			else if($.trim(response["false"]) != ""){
								utility.showErrorDialogBox(response["false"]);
								 $("#ps-activitystream-loading").hide();
								 $("#stream_type").val("mainstream")
							 return;
						 
		}else if($.trim(response["true"]) != ""){
		 if($.trim(response["true"]) == "no_posts"){
					 $("#ps-no-posts").show();
				
				 // if there are no more posts
				 }else if($.trim(response["true"]) == "no_more_posts"){
					 $("#ps-no-more-posts").show();
				
				 }
			// prevent the request of more data
			stream.togglePageScroll("inactive");	     	
		}else if($.trim(response) != ""){
			// if($.trim($("#ps-activitystream").html()) == ""){
			// 	$.each(response,function(index,value){
					
			// 	$("#ps-activitystream").append(value);
			// });
			// }else if($.trim($("#ps-activitystream").html()) != ""){
			// $.each(response,function(index,value){
					
			// 	$("#ps-activitystream").append(value);
			// });	
			// }
			$.each(response,function(index,value){
					
			$("#ps-activitystream").append(value);
				 });

			
		}else{
		    $("#posts_display_error").show();
		    // activate the scrolling of the page
			if($("#page_scroll").val() == "active"){
					stream.togglePageScroll("inactive");
		}
		
		}
		$("#stream_type").val("mainstream");
			}catch(e){
				}finally{
					
					 $("#ps-activitystream-loading").hide();
						
				}	 	
		}).fail(function(error){
				 alert(error);
			 }); 
	
	
		
	}
	
	
	
		// get your own stream( posts that you posted) 
		static getSelfStream(streamType = "",targetElement = null){
	
		 
		// validate the stream type presence
		if($.trim(streamType) == ""){
		return;
		}
	
	
	
	 
	 
		
		if($("#page_scroll").val() == "inactive"){
			if($("#ps-no-more-posts").css("display") != "none"){
				$("#ps-no-more-posts").effect("shake");
				
			}else if($("#ps-no-posts").css("display") != "none"){
				$("#ps-no-posts").effect("shake");
			}else if($("#ps-no-posts-match").css("display") != "none"){
				$("#ps-no-posts-match").effect("shake");
			}
	
			return;
		}
		
	 
	
	
		// show the post loading gif
		if($("#ps-activitystream-loading").css("display") == "none"){
			$("#ps-activitystream-loading").show();
		}
	 
	 // get the stream type value
		let streamTypeValue = $.trim($("#stream_type").val());
		
		// adjust the ui to reflect the self post query process
		 if($.trim(targetElement) != null){
			$(targetElement).attr("disabled",true);
			$(targetElement).find("img").show();
			if( streamTypeValue != "self"){
			$("#ps-activitystream").html("");	
			}
		 }
			 // if the stream type value is 
		 //not self set it to self	
			 if(streamTypeValue != "self"){
			 $("#stream_type").val("self");
		 }
								
		 
	
	
	 
	
		 // activate the scrolling of the page
			if($("#page_scroll").val() == "inactive"){
					stream.togglePageScroll("active");
		}
	
		
	
			 $.ajax({
				 url:"../private/neutral_ajax.php",
				 type: "POST",
				 data: {request_type: "scroll",stream_type:streamType},
				 datatype: "html"
			 }).done(function(response){
				console.log(response);
	
		
				try{
				response = JSON.parse(response);
					 
				
		// incase of a redirect		
			if(response["false"] != undefined && response["false"] == "login"){
	location.href="login.php";
		 }
			// incase of an error
			else if($.trim(response["false"]) != ""){
		utility.showErrorDialogBox(response["false"]);
		$("#ps-activitystream-loading").hide();
		return;
		}
		 else if($.trim(response["true"])){
				 if($.trim(response["true"]) == "no_posts"){
					 $("#ps-no-posts").show();
			 // if there are no more posts
				 }else if($.trim(response["true"]) == "no_more_posts"){
					 $("#ps-no-more-posts").show();
				}
			// prevent the request of more data
			stream.togglePageScroll("inactive");
			}
			// if the request was successful 
		// and the results is not empty
	else if($.trim($("#ps-activitystream")) != "" && $("#ps-activitystream") != null && $.trim(response) != ""){
		$.each(response,function(index,value){
				$("#ps-activitystream").append(value);
			});
			$("#ps-activitystream-loading").hide();
			}
						
			}catch(e){
						 }finally{
				$(targetElement).attr("disabled",false);
				$(targetElement).find("img").hide();
				if($("#ps-activitystream-loading") && $("#ps-activitystream-loading").css("display") == "block" && emptyResponseGif === false){
				 $("#ps-activitystream-loading").hide();
				}
				
					}	 	
		}).fail(function(error){
				 alert(error);
			 }); 
	
		}
	
		
			// get the posts by the user 
		// get your own stream( posts that you posted) 
		static getProfileStream(streamType = ""){
	
		 
		// validate the stream type presence
		if($.trim(streamType) == ""){
		return;
		}
	
	
	
	 
	 
		
		if($("#page_scroll").val() == "inactive"){
			if($("#ps-no-more-posts").css("display") != "none"){
				$("#ps-no-more-posts").effect("shake");
				
			}else if($("#ps-no-posts").css("display") != "none"){
				$("#ps-no-posts").effect("shake");
			}else if($("#ps-no-posts-match").css("display") != "none"){
				$("#ps-no-posts-match").effect("shake");
			}
	
			return;
		}
		
	 
	
	
		// show the post loading gif
		if($("#ps-activitystream-loading").css("display") == "none"){
			$("#ps-activitystream-loading").show();
		}
	 
	 // get the stream type value
		let streamTypeValue = $.trim($("#stream_type").val());
		
		
			 // if the stream type value is 
		 //not self set it to self	
			 if(streamTypeValue != streamType){
			 $("#stream_type").val(streamType);
		 }
								
		 
	
	
	 
	
		 /* // activate the scrolling of the page
			if($("#page_scroll").val() == "inactive"){
					stream.togglePageScroll("active");
		} */
	
		 
			 $.ajax({
				 url:"../private/neutral_ajax.php",
				 type: "POST",
				 data: {request_type: "scroll",stream_type:streamType},
				 datatype: "html"
			 }).done(function(response){
				console.log(response);
	
		
				try{
				response = JSON.parse(response);
					 
				
		// incase of a redirect		
			if(response["false"] != undefined && response["false"] == "login"){
	location.href="login.php";
		 }// incase of an error
		 else if($.trim(response["false"]) != ""){
		utility.showErrorDialogBox(response["false"]);
		$("#ps-activitystream-loading").hide();
		console.log("her");
		return;
		}
		 else if($.trim(response["true"])){
				 if($.trim(response["true"]) == "no_posts"){
					 $("#ps-no-posts").show();
			 // if there are no more posts
				 }else if($.trim(response["true"]) == "no_more_posts"){
					 $("#ps-no-more-posts").show();
				}
			// prevent the request of more data
			stream.togglePageScroll("inactive");
			}
			// if the request was successful 
		// and the results is not empty
	else if($.trim($("#ps-activitystream")) != "" && $("#ps-activitystream") != null && $.trim(response) != ""){
		$.each(response,function(index,value){
				$("#ps-activitystream").append(value);
				console.log(index);
			});
	
		//    for(index in response){
			//    $("#ps-activitystream").append(value);
		//    }
	
			 for (index in response) {
				 console.log(index);
				 console.log(response[index]);
				 console.log();
			if (response.hasOwnProperty(index)) {
				$("#ps-activitystream").append(response[index]);
			}
		}
			 console.log(response);
			$("#ps-activitystream-loading").hide();
	
	
			}
						
			}catch(e){
						 }finally{
				
				if($("#ps-activitystream-loading") && $("#ps-activitystream-loading").css("display") == "block"){
				 $("#ps-activitystream-loading").hide();
				}
				
					}	 	
		}).fail(function(error){
				 alert(error);
			 }); 
	
		}// getProfileStream();
	
	
	
	
	
		static getCommunityStream(streamType = ""){
	
			
	
	 
	 
		 
		if($.trim(streamType) == ""){
		return;
		}
		
		// activate the scrolling of the page
			if($("#page_scroll").val() == "inactive"){
					stream.togglePageScroll("active");
		}
		
	 if($("#ps-activitystream-loading").css("display") == "none"){
			$("#ps-activitystream-loading").show();
	 }
	
			
		 if($("#stream_type").val() != streamType){
			 $("#stream_type").val(streamType)
		 }
		
		 $.ajax({
				 url:"../private/neutral_ajax.php",
				 type: "POST",
				 data: {request_type: "scroll",stream_type:streamType},
				 datatype: "html"
			 }).done(function(response){
				console.log(response);
	
	
				try{
				response = JSON.parse(response);
	
			 if(response["false"] != undefined && response["false"] == "login"){
	 location.href="login.php";
			}
			// incase of an error
			else if($.trim(response["false"]) != ""){
								utility.showErrorDialogBox(response["false"]);
								 $("#ps-activitystream-loading").hide();
							 return;
						 
		}else if($.trim(response["true"]) != ""){
		 if($.trim(response["true"]) == "no_posts"){
					 $("#ps-no-posts").show();
				 console.log("here");
				 // if there are no more posts
				 }else if($.trim(response["true"]) == "no_more_posts"){
					 $("#ps-no-more-posts").show();
				
				 }
			// prevent the request of more data
			stream.togglePageScroll("inactive");	     	
		}else if($.trim(response) != ""){
			$.each(response,function(index,value){
				$("#ps-activitystream").append(value);
			});
		}
			}catch(e){
				}finally{
					
					 $("#ps-activitystream-loading").hide();
						
				}	 	
		}).fail(function(error){
				 alert(error);
			 }); 
	
	
		
	
	
	
	
		}
		
	
		static getProfile(){
	
	
		}
	
	
	
	
	
	 /*  static get_stream(streamType = "",targetElement = null){
	
	
		if($.trim(streamType) == ""){
		return;
		}
	
	
	 $("#ps-activitystream-loading").show();
		 let page_scroll_value = $("#request_type").val();
		 if($.trim(targetElement) != null){
	
				$(targetElement).attr("disabled","");
			$(targetElement).find("img").show();
			$("#request_type").val("")
			$("#ps-activitystream").html("");
								
		 }
	
			 $.ajax({
				 url:"../private/neutral_ajax.php",
				 type: "POST",
				 data: {request_type: "scroll",stream_type:streamType},
				 datatype: "html"
			 }).done(function(response){
				console.log(response);
	
	
				try{
				response = JSON.parse(response);
	
				// if there are no post for now
				if($.trim(response["pending"]) == "waiting" && $("#ps-activitystream-loading").css("display") == "none" ){
							if( $("#ps-activitystream-loading").css("display") == "none" ){
								$("#ps-activitystream-loading").show();
							}
				}
					 
			else{
		// incase of a redirect		
	if(response["false"] != undefined && response["false"] == "login"){
	 location.href="login.php";
			}
			// incase of an error
			else if($.trim(response["false"]) != ""){
								utility.showErrorDialogBox("Please it is our fault but please try again.");
								 $("#ps-activitystream-loading").hide();
							 return;
						 
						 }
	
			if($.trim($("#ps-activitystream")) != "" && $("#ps-activitystream") != null && $.trim(targetElement) != null && streamType != "mainstream"){
								 if(response["true"] == "upclose_empty"){
									 $("#ps-no-posts-match").show();
									 $("#deactivate_scroll").toggle().val("active");
								 }
								$(targetElement).attr("disabled","");
								$(targetElement).find("img").hide();
								$("#request_type").val(page_scroll_value)
								$("#ps-activitystream").html("");
								 // if there are no post for now
				if($("#ps-activitystream-loading") && $("#ps-activitystream-loading").css("display") == "block" ){
							
								$("#ps-activitystream-loading").hide();
						
				}
							return;
								
					 }
	
							
						 
						 if($.trim($("#ps-activitystream")) != "" && $("#ps-activitystream") != null ){
						$.each(response,function(index,value){
						$("#ps-activitystream").append(value);
					 });
					
						
						}
						
							$("#ps-activitystream-loading").hide();
					 
				 }
					 
				}catch(e){
							utility.showErrorDialogBox("Sorry request failed,please refresh the page and try again.");
							console.log(e);
				}finally{
						$(targetElement).attr("disabled","");
						$(targetElement).find("img").hide();
						$("#request_type").val(page_scroll_value);
						
				}	 	
		}).fail(function(error){
				 alert(error);
			 }); 
	
	
		}
	 */
	
	
		static getCallMethod(streamType = ""){
				if($.trim(streamType) == ""){
					return;
				}
	
	
				streamType = $.trim(streamType);
	
				switch(streamType){
					case "mainstream":
	
					stream.getMainStream(streamType);
					break;
					case "self":
					stream.getSelfStream(streamType);
					break;
					case "profile":
					stream.getProfileStream(streamType);
					break;
					case "community":
					stream.getCommunityStream(streamType);
					break;
					default: ;
				}
		}
	
		}
	
	
	$(window).scroll(function() {
			 if($("#page_scroll").val() == "inactive"){
					 return;
				 }
				 
			if ($(window).scrollTop() == $(document).height() - $(window).height()) {
					 let streamType = $("#stream_type").val();
							 if(streamType.split("_")[0] === "community"){
									 stream.getCommunityStream(streamType);
									 return;
							 }
	
							stream.getCallMethod($("#stream_type").val());
					
					
				 
			}
	});
	
	$(document).ready(function(){
				let streamType = $("#stream_type").val();
			
				if(streamType.split("_")[0] === "community"){
					stream.getCommunityStream(streamType);
					return;
			 }else if(streamType.split("_")[0] === "profile"){
					stream.getProfileStream(streamType);
					return;
			 }
		 
		stream.getCallMethod($("#stream_type").val());
	});
	
	