(function ($) {

 "use strict";


  $(".checkboxradio").checkboxradio();

    var clickables = ["#ps-upload-containter", ".post-status"];
    var fileuploadUrl = "../lqUgAuP7zZlempzC9gN9lIm8yiqnAYfExk/post_event.php";
    var commentUrl = "../s2CxARWoyfS608LFDZxNvOC8OoZR9Qg/neutral_ajax.php";
   // fileuploadUrl     = "../s2CxARWoyfS608LFDZxNvOC8OoZR9Qg/testupload.php";

    // required fields
    var requiredLabel;
    var requiredLocation;
    var title;





    // Global variables
    var firstPostMirror = $(".ps-postbox-mirror:eq(0)");
    var postStatusMood = $(".ps-postbox-addons:eq(1)");
    var postButton = $(".postbox-submit") ? $(".postbox-submit") : "";
    var cancelDiv = $("#post_action_div") ? $("#post_action_div") : "";
    var cancelButton = $("#cancel_button") ? $("#cancel_button") : "";
    var postbox = $("#post_caption") ? $("#post_caption") : "";
    var postbox_mood = $("#postbox-mood") ? $("#postbox-mood") : "";
    var poststatus = $("#poststatus") ? $("#poststatus") : "";
    var sizeSent = $(".ps-postbox-loading:eq(2)");

    // post parameters variables
    // var label         = label ? label : "transport";
    var post_mood          = [];
    // var post_location      = post_location ? post_location : "Accra";
    //var post_location      = $("#location-tab") ? $("#location-tab") :  "";
    var post_location      =  "Accra";
  //  var post_links       = post_links ? post_links : ["Yussif","Muniru","Kareem","Ganiu"];
    var post_caption       = $("#post_caption") ? $("#post_caption"): false;
    var post_selectedLabel = post_selectedLabel ? post_selectedLabel : "";
    var post_title         = $(".ps-videos-url") ? $(".ps-videos-url") : "this is just a placeholder title" ;


    // End of  Global Variables
    function refreshLabel() {
        //$(selectedLabel).controlgroup();
        $(".checkboxradio").prop("checked", false);
        $(".checkboxradio").checkboxradio("refresh");
    }

  
    // helper function to convert the file
    // size to a more human readable format
    function convertFileSize(size) {
        var selectedSize = 0;
        var selectedUnit = "b";
        var conversionBase = 1024;
        var FileSizeUnits = {
            tb: "TB",
            gb: "GB",
            mb: "MB",
            kb: "KB",
            b: "b"
        };

        if (size > 0) {
            var units = ['tb', 'gb', 'mb', 'kb', 'b'];

            for (var i = 0; i < units.length; i++) {
                var unit = units[i];
                var cutoff = Math.pow(conversionBase, 4 - i) / 10;

                if (size >= cutoff) {
                    selectedSize = size / Math.pow(conversionBase, 4 - i);
                    selectedUnit = unit;
                    break;
                }
            }

            selectedSize = Math.round(10 * selectedSize) / 10; // Cutting of digits
        }

        return "<strong>" + selectedSize + "</strong> " + FileSizeUnits[selectedUnit];
    }


    ////   Dropzone  ///
    Dropzone.options.postcontent = false;
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone("#postcontent", {
        url: fileuploadUrl,
        paramName: "file",
        autoProcessQueue: false,
        parallelUploads: 10,
        uploadMultiple: true,
        maxFilesize: 70,
        maxFiles: 10,
        //    filesizeBase: 1000000,
        acceptedFiles: 'image/gif,image/png,image/jpeg,image/jpg,video/x-ms-wmv,video/mp4,video/3gpp,audio/mpeg,audio/mp4,audio/mp3',
        renameFile: true,
        clickable: ["#ps-upload-container", ".dropzone"],
        //    forceFallback: true,
        dictDefaultMessage: "",
        dictRemoveFileConfirmationn: true,
        init: function () {
            var dz = this;
            $(postButton).click(function () {
               // if the label is not selected
                if ($.trim(post_selectedLabel) == "" && (post_title) != null) {
                   if(!labelError()){
                      return;
                    }
                  }
               // if the title is not selected
                if($.trim($(post_title).val()) == "" && $(post_title) != null){
                     if(!titleError()){
                      return;
                     }
                 }

                 if($("#location-tab") != null && $.trim(post_location) == ""){
                   locationError();
                  return;
                }
                // console.log("all passed now lets proceed");
               // console.log(post_mood["span"]);
                console.log($(post_caption).val());
                console.log($(post_title).first().val());
                //console.log(post_links);
                console.log(post_selectedLabel);
                console.log(post_location);
               // refresh the label selection
                try{
                     dz.processQueue();
                     reset_postbox();
                }catch(e){
                 console.log(e);
                }


            });
            $(cancelButton).click(function () {
                dz.removeAllFiles(true);

            });
        }
    });


    // when a file is added
    myDropzone.on("addedfile", function () {
          // console.log(post_selectedLabel);
        // if the post textarea is not empty or null
        // fill the poststatus with the contents of the // post textarea
        if ($(firstPostMirror).length && $(poststatus).length && ($(firstPostMirror).html() != null || $(firstPostMirror).html().trim() != "")) {
            // populate the poststatus if a file is added
            $(poststatus).val($(firstPostMirror).html());

            let firstMood = $(".ps-postbox-status:eq(0) div:nth-child(1) span:nth-child(2)")[0];

            if ($(postStatusMood.length) && $(firstMood).length && post_mood["span"] != null && post_mood["icon"] != null) {
                // set the selected mood on the status of the post
                $(postStatusMood).replaceWith(selectedMoodTemplate(post_mood["icon"], post_mood["span"]));
                // remove the placeholder from the status of the post textarea when the file is added
                $("textarea:eq(1)").attr("placeholder", "");
            }
        } else {
            // console.log("Not found!!!");
        }
        // show the post status textarea
        $(".ps-postbox-status:eq(1)").show();
        // show the post button
        $(postButton).css("display","inline");
        $(cancelButton).show();

    });


    // just when a file is about to be sent to the server
    myDropzone.on("sendingmultiple", function (file, xhr, formData) {
        // show the cancel button
        toggleCancelButton("show");
        // show the upload progress bar with the percentage and byte rate sent.
        if (sizeSent != null &&
            uploadprogress != null && (
                $(sizeSent).hasClass("hidden") ||
                $(uploadprogress).hasClass("hidden"))) {
            $(sizeSent).removeClass("hidden");
            $(uploadprogress).removeClass("hidden");
        }
        formData.append("label", post_selectedLabel);
       // formData.append("mood", post_mood["span"]);
        formData.append("caption",$(post_caption).val());
        formData.append("location",post_location);
        //formData.append("media", post_links);
        formData.append("title",post_title.val());
        formData.append("csrf_token", $.trim($("#csrf").prop("value")));
        // console.log("label: " + selectedLabel + " mood: " + mood["span"] + " caption " + caption + " location: " + location + " media: " + links + " csrf: " + $("#csrf").prop("value"));

    });


    // if the file sending completed without errors
    myDropzone.on("success", function (file, response) {
        console.log(response);
        return;
         //hide the upload progress bar
        if(!$(uploadprogress).hasClass("hidden")){
            $(uploadprogress).addClass("hidden");
        }

//    JSON.parse(response,function(key,value){
// console.log(key);
//    });
   let result;
    if(result = JSON.parse(response)){
     if(result.true){
       showDialog("#success-dialog",true,result.true);       
        
  }else if(result.false){
    //post box error handling
    let label = "label",location = "location",title = "title";
    switch ($.trim(result.false)) {
    case $.trim(label):
    labelError(4);
    break;
    case $.trim(location):
    locationError(4);
    break;
     case $.trim(title):
     titleError(4);
     break;
     default:
     if(result.ERROR){
  showDialog("#postbox-main",false,result.ERROR);
     }
   
     break;
}
                     // $("#error-dialog").html(response.trim());
}
}

});// dropzone.success();


    // if the file sending completed without errors
    myDropzone.on("error", function (file, error) {
        // console.log("The error was poorly handled " + error);
    });

    // tracks the progress of the file
    myDropzone.on("uploadprogress", function (file, percentage, bytes) {
        var progressBar = $("#uploadprogress").find("span");
        var size = convertFileSize(bytes);
        if ($(progressBar)) {
            $(progressBar).css("width", Math.ceil(percentage) + "%");

        }
        if ($(sizeSent)) {
            $(sizeSent).html(Math.floor(percentage) + "% ( " + size + " ) ");
        }
        //  console.log($(progressBar).prev());
        //        console.log("ceil: " + Math.ceil(percentage) +" round: " + Math.round(percentage));
    });

    //when the file upload completes
    myDropzone.on("complete", function () {
        //    // show the upload progress bar with the percentage and byte rate sent.
        var progressBar = $("#uploadprogress").find("span");
        $("#uploadprogress").find("span").css("width", "0%");
        if (sizeSent != null &&
            uploadprogress != null && (
                !$(sizeSent).hasClass("hidden") ||
                !$(uploadprogress).hasClass("hidden"))) {
            $(sizeSent).addClass("hidden");
            $(uploadprogress).addClass("hidden");
        }
        this.removeAllFiles();

        if($(postButton) && $(postButton).show()){
            $(postButton).hide();
        }
  if($(cancelButton) && $(cancelButton).show()){
            $(cancelButton).hide();
        }



    });




  
  function showDialog(selector,key,text = null){

    if(key === true){

(test != "" || text != false || text != null) ? ($("#success-dialog").html(text)) : $("#success-dialog").html("SUCCESS");
           
             // success dialog box
            $("#success-dialog").dialog({

                        dialogClass: "no-close",
                        show: {effect: "scale", duration: 400},
                        hide: {effect: "fadeOut", duration: 50},
                        draggable: false,
                        height: "auto",
                        maxHeight: 400,
                        minHeight: 200,
                        modal: true,
                        minWidth: 200,
                        resizable: false,
                        closeOnEscape: true,
                        buttons: [
                            {
                                text: "Ok",
                                icon: "ui-icon-heart",
                                click: function () {
                                    $(this).dialog("close");
                                }

                            }
                        ]
                    });
            $("#success-dialog").show();

    }else if(key === false){
       (text === null) ?  ($("#error-dialog").html(text)) : ""; 
                  
                    // success dialog box
                    $("#error-dialog").dialog({
                        dialogClass: "no-close",
                        show: {effect: "scale", duration: 400},
                        hide: {effect: "fadeOut", duration: 50},
                        draggable: false,
                        height: 50,
                        maxHeight: 100,
                        minHeight: 50,
                        modal: true,
                        minWidth: 200,
                        resizable: false,
                        closeOnEscape: true,
                        buttons: [
                            {
                                text: "Close",
                                icon: "ui-icon-heart",
                                click: function () {
                                    $(this).dialog("close");
                                }

                            }
                        ]
                    });

                    $('.ui-dialog-titlebar').addClass("error-titlebar");
                    $(".ui-dialog-title").addClass("error-title");
                  $("#error-dialog").show();

    }

  }




    // display the error for the label
    function labelError(count = null) {
   
     if(!count){

     
       $("fieldset").effect("shake");
        if($(".label-error") != null && $(".label-error").hide()){
        $(".label-error").show();
           $( "fieldset" ).tooltip({
             classes: {
        "ui-tooltip": "tooltip-error",
         "ui-tooltip-content":"tooltip-content-error"
 }
});
        }
        

    }else if(count){
  for (var i = 0; i <= count ; i++) {
      
  
       $("fieldset").effect("shake");
        if($(".label-error") != null && $(".label-error").hide()){
        $(".label-error").show();
           $( "fieldset" ).tooltip({
             classes: {
        "ui-tooltip": "tooltip-error",
         "ui-tooltip-content":"tooltip-content-error"
 }
});
        }
  
}
    }

return false;
    }

    // display the error for the title
    function titleError(count = null){
         if(!count){

         
        if($("#post_title") != null) {
            $("#post_title").effect("shake");
        }
      
      if($("sup") != null){
             $("sup").show();
         }
        $(".ps-videos-url").effect("highlight","#c61140");
        $( "#post_title_div" ).tooltip({
          classes: {
     "ui-tooltip": "tooltip-error",
      "ui-tooltip-content":"tooltip-content-error"
}
});

}else if(count){

     for (var i = 0; i <= count; i++) {
            
        if($("#post_title") != null) {
            $("#post_title").effect("shake");
        }
      
      if($("sup") != null){
             $("sup").show();
         }
        $(".ps-videos-url").effect("highlight","#c61140");
        $( "#post_title_div" ).tooltip({
          classes: {
     "ui-tooltip": "tooltip-error",
      "ui-tooltip-content":"tooltip-content-error"
}
});

     }
}

return false;
    }

    // display the error for the location
    function locationError(count = null){
    
       if(!count){

       
        if($("#location-tab") != null && $("#location-tab")){
         //$("#location_wrapper").effect("shake");
            $("#post_menu").effect("shake");
            $("#location_wrapper").css("background","red");
            $("#post_menu").css("background","red");
            $( "#location_wrapper" ).tooltip({
              classes: {
         "ui-tooltip": "tooltip-error",
          "ui-tooltip-content":"tooltip-content-error"
  }

});

 if($(".label-error").last() != null){
     $(".label-error").last().show();
 }
  }

}else if(count){
   

    for (var i = 0; i <= count ; i++) {
         
        if($("#location-tab") != null && $("#location-tab")){
         //$("#location_wrapper").effect("shake");
            $("#post_menu").effect("shake");
            $("#location_wrapper").css("background","red");
            $("#post_menu").css("background","red");
            $( "#location_wrapper" ).tooltip({
              classes: {
         "ui-tooltip": "tooltip-error",
          "ui-tooltip-content":"tooltip-content-error"
  }

});

 if($(".label-error").last() != null){
     $(".label-error").last().show();
 }
  }
 
    }

}
  return false;
    }
    // hide the mood picker
    function toggleMoodPicker(param) {
        // show the mood picker
        if (param.trim() === "show") {

            if ($(postbox_mood) != null) {
                $(postbox_mood).css("display", "flex");
                if (!$(postbox_mood).hasClass("placeholder")) {
                    $(postbox_mood).addClass("placeholder");
                }
            }

        } else
        if (param.trim() === "hide") {

            if ($(postbox_mood) != null) {
                $(postbox_mood).css("display", "none");
                if ($(postbox_mood).hasClass("placeholder")) {
                    $(postbox_mood).removeClass("placeholder");
                }
            }
        }

    } // hideMoodPicker();


    // hide the location picker
    function toggleLocationPicker(param) {

        // show the location picker if the param is show
        if (param.trim() === "show") {

            if ($("#location_wrapper") != null && $("#pslocation") != null) {
                $("#pslocation").removeClass("hidden");

            } // hide the location picker if the param is hide
            else
            if (param.trim() === "hide") {

                if ($("#location_wrapper") != null && $("#pslocation") != null) {
                    $("#pslocation").addClass("hidden");
                }

            }
        }
    } //toggleLocationPicker()


    // toggle the peoples picker
    function togglePeoplesPicker(param) {
        // show the peoples picker
        if (param.trim() === "show") {

            if ($("#people_wrapper") != null && $("#people_wrapper_div") != null) {
                $("#people_wrapper_div").css("display", "block");
            }
        } else

        if (param.trim() === "hide") {

            if ($("#people_wrapper") != null && $("#people_wrapper_div") != null) {
                $("#people_wrapper_div").css("display", "none");
            }
        }

    }


    // toggle the rest of the pickers apart from
    // the picker with id as the param
    function toggleTheRest(elementId) {

        if (elementId.length) {
            switch (elementId.trim()) {

                // TURNING ON THE PEOPLE WRAPPER
                case "people_wrapper_div":
                    // hide the mood picker
                    toggleMoodPicker("hide");
                    // hide the location picker
                    toggleLocationPicker("hide");
                    break;

                    //TURNING ON THE LOCATION
                case "location_wrapper":
                    // turn off the mood selector
                    toggleMoodPicker("hide");
                    // turn off the peoples wrapper
                    togglePeoplesPicker("hide");
                    break;

                    //TURNING ON THE MOOD SELECTOR
                case "mood_wrapper":
                    // hide the peoples picker
                    togglePeoplesPicker("hide");

                    // hide the location picker
                    toggleLocationPicker("hide");
                    break;

                    // TURNING ALL OFF WITH THE CANCEL UPLOAD BUTTON
                case "all":
                    // hide the peoples picker
                    togglePeoplesPicker("hide");

                    // hide the location picker
                    toggleLocationPicker("hide");

                    // hide the mood picker
                    toggleMoodPicker("hide");
                    break;
                default:
                    console.log("");
            }

        }
    } //toggleTheRest


    // return the specific mood chosen by using
    // the iconClass and the mood(as plain text)
    function selectedMoodTemplate(iconClass, mood) {

        return "<span class='ps-postbox-addons' style='display: inline;'>— <i class='ps-emoticon " + iconClass + "'></i> <b> feeling " + post_mood + "</b></span>";

    } // selectedMoodTemplate()


    // helper method to toggle the cancel button
    function toggleCancelButton(toggle) {
        if (toggle != null && $(cancelButton) != null) {
            switch (toggle) {
                case "show":
                    $(cancelButton).show();
                    // console.log("cancel button shown here");
                    break;
                case "hide":
                    $(cancelButton).hide();
                    break;
                default:
                    // console.log("technical fault with toggling the cancel button");
            }
        } else {
            // console.log("Missing Cancel button");
        }
    }


    // reset the entire postbox
    function reset_postbox(key) {
      
      // if(myDropzone != null){
      //   myDropzone.removeAllFiles(true);
      // }
      refreshLabel();
      toggleCancelButton("hide");
      post_selectedLabel = "";

      if($(".label-error") && $(".label-error").show()){
           $(".label-error").hide();
      }


      $(post_title).val("");
      var span = $("span.ps-postbox-addons");

      if (span.length) {
          span.css("display", "none");
      }

      // if there is a caption
       if(  $(post_caption) &&   $.trim($(post_caption).val()) != ""){
           $(post_caption).val("");
       }

       // if the post title has has text
       if(  $(post_title) &&   $.trim($(post_title).val("")) != ""){
           $(post_title).val("");
       }

       if($("#location_wrapper") != null){

           $("#location_wrapper").css("background","");
       }
     //$(".ps-postbox-loading:eq(2)").show();

        // hide the cancel button
        // toggleCancelButton("hide");
        $(postButton).hide();
        $(cancelButton).hide();
        // show the post box
        $(".ps-postbox-status:eq(0)").show();
        if($("#post").attr("placeholder") == ""){
            $("#post").attr("placeholder","Provide a short description of your post(optional)...");
        }
        // hide the post box ("comment")
        $(".ps-postbox-status:eq(1)").css("display") != "none" ? $(".ps-postbox-status:eq(1)").show() : "";
        $("#postcontent").show();
        $("#images").css("display", "none");
        $(postbox).val("");
        // reset the height of the textarea
        $(postbox).css("height", "36px");
        // reset the mirrored post
        $(firstPostMirror).html("");
        // reset the mood if any if chosen
        $(".ps-postbox-addons").html("");
        // reset the mood variable
        post_mood = [];
        toggleTheRest("all");
    } //reset_postbox()


    // autosize the postbox when the enter key is pressed
    $(post_caption).on("focus", function () {
        var textarea = $(this);
        $(textarea).keypress(function (e) {
            if (e.which == 13) {
                var height = $(textarea).css("height");
                height = height.split("p");
                height = parseInt(height[0]);
                // console.log(height + 8);
                $(textarea).css("height", height + 8 + "px");
                // console.log($(textarea).css("height"));
            }
        });
    });


    $(document).ready(function () {

         $(".ps-videos-url").keyup(function(){
          if($(".label-error").first() && $(".label-error").first().show()){
             $(".label-error").first().hide();
           }

         });

        //  get the label
        $(".labelset").prop("checked", true).change(function (e) {
             // label = $(e.target).attr("value");
            // selectedLabel = e.target;
            post_selectedLabel = $(e.target).attr("value");
            if($(post_selectedLabel) != "" && $(".label-error").eq(1).show()){
            $(".label-error").eq(1).hide();
            }
    // $(".label-error").eq(1).hide();
            //console.log(post_title);
 // console.log(selectedLabel);
        });


        // get the mood
        $(".mood-list").on("click", function () {
            if($(".ps-postbox-addons").hide()){
                $(".ps-postbox-addons").show();
            }
            var $trigger = $(this);
            var $icon = $trigger.find("i"); // will return a jQuery list of items (list.length === 0 means not found)

            post_mood["span"] = $trigger.find("span"); // will return a jQuery list of items (list.length === 0 means not found)
            //console.log($trigger.get(0));
            if ($icon.length) {
                let iconClass = $icon.attr("class");
                var icon = $icon.attr("class");
                //    console.log(iconClass);
                post_mood["icon"] = icon;
            } else {
                //console.log("icon not found");
            }
            post_mood["span"] = post_mood["span"].html();
            if (post_mood["span"].length) {
             // console.log(post_mood["span"]);
            } else {
                //  console.log("span not found");
            }

            // store the selected post_mood
            var $selec = selectedMoodTemplate(icon, post_mood["span"]);

            // get the element to replace with
            var mood_span = $(".ps-postbox-status:eq(0) div:nth-child(1) span:nth-child(2)")[0];

            // replace the former element with the new
            // selected mood
            $(mood_span).replaceWith($selec);

            // get the textarea and set remove the placeholder
            $(postbox).attr("placeholder", "");
            toggleCancelButton("show");
        });


        // when the image /video div is clicked
        // display the clickable elements
        $(".media-upload").click(function () {
            // hide the status textarea that reads(say something...)
            $(".ps-postbox-status:eq(0)").show();
            // $("#images").css("display","block");
            // show the cancel button
            $(cancelButton).show();
            //say something about the post
            $("#postcontent").show();

        });


        // if the location icon is clicked?
        // then toggle the class hidden
        if ($("#location_wrapper") != null) {
            $("#location_wrapper").click(function () {
                $("#pslocation").toggleClass("hidden");
                // turns off the display of the other popovers
                toggleTheRest("location_wrapper");
            });
        }


        // show the drop down if the people wrapper is clicked
        if ($("#people_wrapper") != null && $("#people_wrapper_div") != null) {
            $("#people_wrapper").click(function () {
                $("#people_wrapper_div").toggle();
                // turns off the display of the other popovers
                toggleTheRest("people_wrapper_div");
            });

        }

        // when the mood icon is clicked
        if ($("#mood_wrapper") != null && $(postbox_mood) != null) {
            $("#mood_wrapper").click(function () {
                if (!$(postbox_mood).hasClass("placeholder")) {
                    // show the mood picker
                    toggleMoodPicker("show");
                    // turns off the display of the other popovers
                    toggleTheRest("mood_wrapper");
                } else if ($(postbox_mood).hasClass("placeholder")) {
                    toggleMoodPicker("hide");
                }

            });
        }


        // show the cancel button when the postbox has focus and hide the ajax loaging gif
        $(postbox).focus(function () {
            toggleCancelButton("show");
        });

        // when they start typing in the title bar
        $(".ps-videos-url").keyup(function(e){
            if($(".ps-videos-url") && $.trim($(".ps-videos-url").val()) != "" && $("sup").show()){
                 $("sup").hide();
            }
        });

        // when the location tab has being clicked hide the error;
        $("#location-tab").click(function(){
           
          if($(".label-error").last() != null){
          if( $(".label-error").css("display") != "none"){
                  $(".label-error").last().hide();
                  if($(".label-error").last().css("background") == "red"){
                     $(".label-error").last().css("background","")

                  }
             }
          }

        });

        // when the user starts to type display the post button
        // || .ps-textarea
        $(post_caption).keyup(function (e) {
            // if($.trim($(postbox).val()) == ""){
            //   $(".ps-postbox-addons").css("margin-left","0.5em");
            //   return;
            // }

            if($(post_caption) && $.trim($(post_caption).val()) != ""){
                 toggleCancelButton("show");
               //  $(postButton).css("display","inline");
            }
             // let stringLength = $.trim($(postbox).val()).length;
           
             if($(".ps-postbox-addons") != null && $(postbox_mood) != "") {

                     let initialMargin = $(".ps-postbox-addons").css("margin-left");
                        if(initialMargin == "0px"){
                            $(".ps-postbox-addons").css("margin-left","0.5em");
                       }else {
                            // console.log(initialMargin)
                         initialMargin = initialMargin.split("p");
                            $(".ps-postbox-addons").css("margin-left",   0.07+ "em");
                        }
                 }else{
                     console.log("element not found@@@");
                 }

            // if the post textarea is not empty or null
            // fill the poststatus with the contents of the // post textarea
            if ($(post_caption).val() != null || $.trim($(post_caption).val())!= "") {
                $(firstPostMirror).html($(post_caption).val());
        } else {
                // console.log($(post));
            }

        });


        // when the user post's the content empty the textarea and display
        // // the ajax loading gif in place of the cancel and post buttons
        // $(postButton).click(function () {
        //     //    $(".ps-postbox__action.ps-postbox-action:eq(0)").css("display","none");
        //   reset_postbox();
        // });


        //when the cancel button is clicked discard all input
        $(cancelButton).click(function () {
            reset_postbox();

          });
    });

})(jQuery);
