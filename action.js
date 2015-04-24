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

var refresh_current_page = function (){
	location.reload();
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
		if(data >= 1){
			show_notification("New Password Saved Successfully","success");
			location.reload();
		}else{
			show_notification("New Password Save Failed... Try again","danger");
		}
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

$('.selected_all_box').click(function(event) {  //on click
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
			if(data == 1){
				show_notification("Leave Request Sent Successfully","success");
				location.reload();
			}else{
				show_notification("Leave Request Send Failed... Try again","danger");
			}
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
			if(data >= 1){
				show_notification("Leave Request Sent Successfully","success");
				location.reload();
			}else{
				show_notification("Leave Request Send Failed... Try again","danger");
			}
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



/*Start Emp Documents*/
$("#save_emp_documents").click(function (){
	if(!$("#document_form").valid()){
		return true;
	}

	var getJsonData =  {
			className 	: "Portal",
			methodName 	: "save_emp_documents",
			doc_code 	: $("#document_type").val(),
			doc_file 	: $("#doc_file").val(),
			course 		: $("#course_type").val(),
			in_hand 	: $("#in_hand").val(),
			remarks 	: $("#doc_remarks").val()
		};
		var data = JSON.stringify(getJsonData);
		$.post("ajaxpage.php",{submit : "commonAction",data:data},
		function(data)
		{
			if(data == 1){
				show_notification("Documents Saved Successfully","success");
				location.reload();
			}else{
				show_notification("Documents Saved Failed... Try again","danger");
			}
		});
});
/*End Emp Documents*/

/*Start Emp qualification*/

$("#save_emp_qualification").click(function (){

	if(!$("#qualification_form").valid()){
		return true;
	}

	var getJsonData =  {
			className 	 	: "Portal",
			methodName 	 	: "save_emp_qualification",
			course_type 	: $("#course_type").val(),
			board_name 	 	: $("#board_name").val(),
			course_name  	: $("#course_name").val(),
			total_mark 	 	: $("#total_mark").val(),
			obtained_mark 	: $("#obtained_mark").val(),
			percentage 	 	: $("#percentage").val(),
			year_of_passing : $("#year_of_passing").val(),
			grade 	 		: $("#grade").val(),
			remarks 	 	: $("#remarks").val()
		};
		var data = JSON.stringify(getJsonData);
		$.post("ajaxpage.php",{submit : "commonAction",data:data},
		function(data)
		{
			if(data == 1){
				show_notification("Qualification Saved Successfully","success");
				location.reload();
			}else{
				show_notification("Qualification Saved Failed... Try again","danger");
			}
		});
});
/*END Emp qualification*/

/*Start Emp Experience*/
$("#save_emp_experience").click(function (){
	if(!$("#experience_form").valid()){
		return true;
	}

	var getJsonData =  {
			className 	 		: "Portal",
			methodName 	 		: "save_emp_experience",
			company_name 		: $("#company_name").val(),
			total_experience 	: $("#total_experience").val(),
			date_join  			: $("#date_join").val(),
			date_leaving 	 	: $("#date_leaving").val(),
			designation 		: $("#designation").val(),
			role 	 			: $("#role").val(),
			team_size 			: $("#team_size").val(),
			ctc 	 			: $("#ctc").val(),
			remarks 	 		: $("#remarks").val()
		};
		var data = JSON.stringify(getJsonData);
		$.post("ajaxpage.php",{submit : "commonAction",data:data},
		function(data)
		{
			if(data == 1){
				show_notification("Experience Saved Successfully","success");
				location.reload();
			}else{
				show_notification("Experience Saved Failed... Try again","danger");
			}
		});
});

/*END Emp Experience*/

/*Start Emp Visa*/
$("#save_emp_visa").click(function (){

	if(!$("#visa_form").valid()){
		return true;
	}
	var getJsonData =  {
			className 	 		: "Portal",
			methodName 	 		: "save_emp_visa",
			type 				: $("#type").val(),
			country  			: $("#country").val(),
			start_date 	 		: $("#start_date").val(),
			end_date 			: $("#end_date").val(),
			remarks 	 		: $("#remarks").val()
		};
		var data = JSON.stringify(getJsonData);
		$.post("ajaxpage.php",{submit : "commonAction",data:data},
		function(data)
		{
			if(data == 1){
				show_notification("VisaExperience Saved Successfully","success");
				location.reload();
			}else{
				show_notification("VisaExperience Saved Failed... Try again","danger");
			}
		});
});
/*END Emp Visa*/

/*Start Emp Projects*/
$("#save_emp_projects").click(function (){
	
	if(!$("#project_form").valid()){
		return true;
	}

	var getJsonData =  {
			className 	 		: "Portal",
			methodName 	 		: "save_emp_projects",
			project_code		: $("#project_code").val(),
			company_name		: $("#company_name").val(),
			reporting_to		: $("#reporting_to").val(),
			is_completed		: '1', //'$("#is_completed").val()',
			remarks				: $("#remarks").val()
		};
		var data = JSON.stringify(getJsonData);
		$.post("ajaxpage.php",{submit : "commonAction",data:data},
		function(data)
		{
			if(data == 1){
				show_notification("Projects Saved Successfully","success");
				location.reload();
			}else{
				show_notification("Projects Saved Failed... Try again","danger");
			}
		});
});
/*END Emp Projects*/