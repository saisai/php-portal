var show_notification = function (msg,cls){
	$("#notification_msg").html("&nbsp;"+msg+"&nbsp;");
	$(".notification_box").slideDown("slow");
	$("#notification_msg").addClass("alert-"+cls);
	setTimeout("hide_notification('"+cls+"')",2000);
};

var hide_notification = function(cls){
	$(".notification_box").slideUp("slow");
	$("#notification_msg").removeClass("alert-"+cls);
};

$("#update_password").click(function(){
	var getJsonData =  {
			className 	: "Portal",
			methodName 	: "update_user_password",
			new_password : $("#new_password").val()
	};
	var data = JSON.stringify(getJsonData);
	$.post("ajaxpage.php",{submit : "commonAction",data:data},
	function(data)
	{
		console.log(data)
	});
});

/*
$('#my_settings_form').validator().on('submit', function (e) {
  if (e.isDefaultPrevented()) {
    // handle the invalid form...
    console.log("some error is there");
  } else {
	var getJsonData =  {
		className 	: "Portal",
		methodName 	: "update_user_password",
		new_password : $("#new_password").val()
	};
	var data = JSON.stringify(getJsonData);
	$.post("ajaxpage.php",{submit : "commonAction",data:data},function(data){
		console.log(data)
	});
  }
})
*/

$('#selected_all_box').click(function(event) {  //on click
    if(this.checked) { // check select status
        $('.get_selected_box').each(function() { //loop through each checkbox
            this.checked = true;  //select all checkboxes with class "checkbox1"              
        });
    }else{
        $('.get_selected_box').each(function() { //loop through each checkbox
            this.checked = false; //deselect all checkboxes with class "checkbox1"                      
        });        
    }
});

var days_between = function (start_date, end_Date) {
	// First we split the values to arrays date1[0] is the year, [1] the month and [2] the day
	var date1 = $("#"+start_date).val()
	var date2 = $("#"+end_Date).val()

	var split1 = date1.split("-");
	var split2 = date2.split("-");
	
	// Now we convert the array to a Date object, which has several helpful methods
	var date_1 = new Date(split1[2], split1[1], split1[0]);
	var date_2 = new Date(split2[2], split2[1], split2[0]);

	// We use the getTime() method and get the unixtime (in milliseconds, but we want seconds, therefore we divide it through 1000)
	var date1_unixtime = parseInt(date_1.getTime() / 1000);
	var date2_unixtime = parseInt(date_2.getTime() / 1000);

	// This is the calculated difference in seconds
	var timeDifference = date2_unixtime - date1_unixtime;

	// in Hours
	var timeDifferenceInHours = timeDifference / 60 / 60;

	// and finaly, in days :)
	var timeDifferenceInDays = timeDifferenceInHours  / 24;

	return(timeDifferenceInDays);
};

$("#apply_leave").click(function(){
	/*if($("#start_date").val() == ""){
		show_notification("Please enter start date","danger");
		return false;
	}

	if($("#end_date").val() == ""){
		show_notification("Please enter End date","danger");
		return false;
	}

	if($("#leave_types").val() == ""){
		show_notification("Please Select leave type","danger");
		return false;
	}

	if($("#leave_days").val() == ""){
		show_notification("Please enter leave days","danger");
		return false;
	}

	if($("#leave_remarks").val() == ""){
		show_notification("Please enter remarks","danger");
		return false;
	}

	var date_diff = (days_between()+1)
	if($("#leave_days").val() != date_diff){
		show_notification("Please Check your Dates","danger");
		return false;
	}*/

		var getJsonData =  {
			className 	: "Portal",
			methodName 	: "save_apply_leave",
			start_date : $("#start_date").val(),
			end_date  : $("#end_date").val(),
			leave_types :$("#leave_types").val(),
			leave_days : $("#leave_days").val(),
			leave_remarks :$("#leave_remarks").val()
		};
		var data = JSON.stringify(getJsonData);
		$.post("ajaxpage.php",{submit : "commonAction",data:data},
		function(data)
		{
			/*if(data == 1){
				show_notification("Post successfully","success");
				$("#dialog").dialog("destroy");
				objReport.renderReport();
			}else{
				show_notification("Server Connection Problem...Try again","danger");
			}
			removeLoading();*/
			console.log(data);
		});
});

var get_selected_members = function (){
	var slno = [];
	$(".get_selected_box").each(function(index){
		if($(this).is(":checked")){
			slno.push($(this).attr("name"));
		}
	});
	return slno;
}

var save_team_leave_status = function (slno,status){
		/*if($("#action_status").val() == ""){
			show_notification("Please Select Any action","danger");
			return false;
		}
		*/
		var getJsonData =  {
			className 	: "Portal",
			methodName 	: "save_team_leave_status",
			sl_no : slno,
			status : status,
			action_remarks : $("#action_remarks").val()
		};
		var data = JSON.stringify(getJsonData);
		$.post("ajaxpage.php",{submit : "commonAction",data:data},
		function(data)
		{
			/*if(data == 1){
				show_notification("Post successfully","success");
				$("#dialog").dialog("destroy");
				objReport.renderReport();
			}else{
				show_notification("Server Connection Problem...Try again","danger");
			}*/
			console.log(data);
		});
}

$("#leave_approve").click(function(){
	var slno = get_selected_members ();
	/*if(slno.length  ==  "0") {
		show_notification("Please Select Atleast one","danger");
		return false;
	}*/
	save_team_leave_status (slno,"APPROVED");
});

$("#leave_reject").click(function(){
	var slno = get_selected_members ();
	save_team_leave_status (slno,"REJECTED");
});