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
}

$("#apply_leave").click(function(){
	/*if($("#start_date").val() == ""){
		showAlertMessage("Please enter start date","error");
		return false;
	}

	if($("#end_date").val() == ""){
		showAlertMessage("Please enter End date","error");
		return false;
	}

	if($("#leave_types").val() == ""){
		showAlertMessage("Please Select leave type","error");
		return false;
	}

	if($("#leave_days").val() == ""){
		showAlertMessage("Please enter leave days","error");
		return false;
	}

	if($("#leave_remarks").val() == ""){
		showAlertMessage("Please enter remarks","error");
		return false;
	}

	var date_diff = (days_between()+1)
	if($("#leave_days").val() != date_diff){
		showAlertMessage("Please Check your Dates","error");
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
				showAlertMessage("Post successfully","notify");
				$("#dialog").dialog("destroy");
				objReport.renderReport();
			}else{
				showAlertMessage("Server Connection Problem...Try again","error");
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
			showAlertMessage("Please Select Any action","error");
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
				showAlertMessage("Post successfully","notify");
				$("#dialog").dialog("destroy");
				objReport.renderReport();
			}else{
				showAlertMessage("Server Connection Problem...Try again","error");
			}*/
			console.log(data);
		});
}

$("#leave_approve").click(function(){
	var slno = get_selected_members ();
	/*if(slno.length  ==  "0") {
		showAlertMessage("Please Select Atleast one","error");
		return false;
	}*/
	save_team_leave_status (slno,"APPROVED");
});

$("#leave_reject").click(function(){
	var slno = get_selected_members ();
	save_team_leave_status (slno,"REJECTED");
});