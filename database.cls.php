<?php
include_once ('config.php');

class DataBase{
	function Database (){
		mysql_connect(HOST, MYSQL_USER, PASSWORD,true,65536) or die(mysql_error());
		mysql_select_db(MYSQL_DB) or die(mysql_error());
		mysql_query("SET NAMES UTF8");
	}

	function db_result($query) //Returns the dataset as a two dimensional array
	{
		$this->sqlLog($query);
		$resultArray = array();
		$res = mysql_query($query) or die('Mysql Error:'.mysql_error());
		while($row = mysql_fetch_assoc($res)){
			$resultArray[] = $row;
		}
		return $resultArray;
	}


	function db_write($query,$errorType='default') //This inserts to the db and returns the affected rows 
	{
		$this->sqlLog($query);
		if($errorType=='default')
		{
			mysql_query($query) or die('Mysql Error:'.mysql_error());
		}
		else if($errorType=='code')
		{
			mysql_query($query);
			$error_code = mysql_errno();
			if($error_code)
				return 'error~'.$error_code;
		}
		return mysql_affected_rows();
	}

	function getCompanyName()
	{
		$sql = "SELECT company_name,cal_year FROM sys_company_settings";
		$res = $this->db_result($sql);

		$_SESSION['Title'] = $res[0]['company_name']." >> Portal";
		$_SESSION['company_name'] = $res[0]['company_name'];
		$_SESSION['leave_year'] = $res[0]['cal_year'];
		$_SESSION['workDate'] = date("d-m-Y");
	}

	function checkUser($username,$password)
	{
		$sql = "SELECT 	name,
						user_id,
						id
				FROM employee 
				WHERE user_id = '". $username . "' AND 
					  password = PASSWORD('".$password."')";
		$res = $this->db_result($sql);
		$row=$res[0];		
		if(count($res)) {
			$_SESSION['emp_id'] = $row['id'];
			$_SESSION['user_id'] = $row['user_id'];
			$_SESSION['name'] = $row['name'];
			$this->getCompanyName();
			header("location: main.php");
		} else{
			header("location: index.php?error=1&msg=1");
		}
	}

	function sqlLog($query)
	{
		IF(SQL_LOGGER)
		{
			$file = fopen($_SESSION['BASE_PATH'] . "/sqlAccessLog.txt","a");
			fwrite($file, "\r\n" .$_SESSION['user_id']. " IP:" .$_SERVER['REMOTE_ADDR']." Dt ". date("d-M-y g:sA"). " :\r\n " . $query."\r\n------------------------------------------------\r\n");
			fwrite($file, $query);
			fclose($file);
		}
	}

	function php_log($query)
	{
		$file = fopen($_SESSION['BASE_PATH'] . "/php_log.txt","a");
		fwrite($file, date("d-M-y g:sA"). " : " . $query."\r\n------------------------------------------------\r\n");
		fclose($file);
	}

	function dmy2ymd($string){
		if (strlen($string)== 8){
			return substr($string, -2). '-' . substr($string, 3, 2) . '-' . substr($string, 0, 2);
		}elseif(strlen($string) == 10){
			return substr($string, -4). '-' . substr($string, 3, 2) . '-' . substr($string, 0, 2);
		}
	}
	
	function get_user_password ($emp_id, $user_id){
		$sql = "SELECT password
				FROM employee 
				WHERE user_id='".$user_id."'
				AND id='".$emp_id."'";
		$res = $this->db_result($sql);
		if(count($res)){
			return $res[0]['password'];
		}else{
			return 0;
		}
	}

	function get_employee_details (){
		$sql = "SELECT id,name,
					   middle_name,surname,
					   father_name,mother_name,
					   date_format(dob,'%d-%m-%Y')as dob,
					   year_of_passing,
					   sex,designation,
					   client_name,passport_no,
					   blood_group,
					   date_format(doj,'%d-%m-%Y')as doj,
					   emergency_contact,
					   secondary_contact,
					   mobile_2,msys_email,
					   temp_address,perm_address,
					   tel_no_,mobile_no_,email_id,
					   pan_number,photo
				FROM employee 
				WHERE user_id = '". $_SESSION['user_id'] . "'";
		$res = $this->db_result($sql);
		$html ='';
		if(count($res)){
			$row = $res[0];
			$html .='<div class="form-group col-lg-6">
                        <label>Employee ID</label>
                        <input class="form-control" name="emp_id" value="'.$row['id'].'" disabled>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Name</label>
                        <input class="form-control" name="name" value="'.$row['name'].'">
                    </div>';
            $html .='<div class="form-group col-lg-6">
                        <label>Middle Name</label>
                        <input class="form-control" name="middle_name" value="'.$row['middle_name'].'">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Surname</label>
                        <input class="form-control" name="surname" value="'.$row['surname'].'">
                    </div>';
            $html .='<div class="form-group col-lg-6">
                        <label>Father Name</label>
                        <input class="form-control" name="father_name" value="'.$row['father_name'].'">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Mother Name</label>
                        <input class="form-control" name="surname" value="'.$row['surname'].'">
                    </div>';
            $html .='<div class="form-group col-lg-6">
                        <label>Date of Birth</label>
                        <input class="form-control" name="dob" value="'.$row['dob'].'">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Passing Year</label>
                        <input class="form-control" name="year_of_passing" value="'.$row['year_of_passing'].'">
                    </div>';
            $html .='<div class="form-group col-lg-6">
                        <label>Sex</label>
                        <input class="form-control" name="sex" value="'.$row['sex'].'">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Designation</label>
                        <input class="form-control" name="designation" value="'.$row['designation'].'">
                    </div>';
            $html .='<div class="form-group col-lg-6">
                        <label>Client Name</label>
                        <input class="form-control" name="client_name" value="'.$row['client_name'].'">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Passport No</label>
                        <input class="form-control" name="passport_no" value="'.$row['passport_no'].'">
                    </div>';
            $html .='<div class="form-group col-lg-6">
                        <label>Blood Group</label>
                        <input class="form-control" name="blood_group" value="'.$row['blood_group'].'">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Date of Joining</label>
                        <input class="form-control" name="doj" value="'.$row['doj'].'" disabled>
                    </div>';
            $html .='<div class="form-group col-lg-6">
                        <label>Emergency Contact</label>
                        <input class="form-control" name="emergency_contact" value="'.$row['emergency_contact'].'">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Secondary Contact</label>
                        <input class="form-control" name="secondary_contact" value="'.$row['secondary_contact'].'">
                    </div>';
            $html .='<div class="form-group col-lg-6">
                        <label>Mobile</label>
                        <input class="form-control" name="mobile_2" value="'.$row['mobile_2'].'">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Msys Email</label>
                        <input class="form-control" name="msys_email" value="'.$row['msys_email'].'" disabled>
                    </div>';
            $html .='<div class="form-group col-lg-6">
                        <label>Tel No</label>
                        <input class="form-control" name="tel_no_" value="'.$row['tel_no_'].'">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Mobile No</label>
                        <input class="form-control" name="mobile_no_" value="'.$row['mobile_no_'].'">
                    </div>';
            $html .='<div class="form-group col-lg-6">
                        <label>Email ID</label>
                        <input class="form-control" name="email_id" value="'.$row['email_id'].'">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Pan Number</label>
                        <input class="form-control" name="pan_number" value="'.$row['pan_number'].'">
                    </div>';

            $html .='<div class="form-group col-lg-12">
                        <label>Temporary Address</label>
                        <input class="form-control" name="temp_address" value="'.$row['temp_address'].'">
                    </div>
                    <div class="form-group col-lg-12">
                        <label>Permanent Address</label>
                        <input class="form-control" name="perm_address" value="'.$row['perm_address'].'">
                    </div>';

		}else{
			$html .='<h3>No Details Available</h3>';
		}
		return $html;
	}

	function leave_type (){
		$sql = "SELECT code,
					   name
				FROM leave_types
				ORDER BY code";
		$res=$this->db_result($sql);
		$options = '';
		if(count($res)) {
			$options .= '<option value="" >Select Any Value</option>';
			foreach($res as $leave) {
				$options .= '<option value="'.$leave['code'].'">'.$leave['name'].'('.$leave['code'].')</option>';
			}
		} else {
			$options .= '<option value="" >No Leaves available</option>';
		}
		return $options;
	}

	function emp_leave_total_details(){
		$sql = "SELECT code,
				       name,
       				   total
				FROM leave_types
				ORDER BY name,total";
		$result=$this->db_result($sql);		
		$details = array();
		
		$html ='';
		if(count($result)){
			foreach($result as $val){
				$details[$val['code']]['name'] = $val['name'];
				$details[$val['code']]['total'] = $val['total'];
				$details[$val['code']]['taken'] = 0;
			}

			$sql = "SELECT type,
					       sum(`leave`) as taken
					FROM emp_leave_ledger  
					WHERE emp_id = '".$_SESSION['emp_id']."'
					AND calc_year = '".$_SESSION['leave_year']."'
					AND status IN ('APPROVED','CONSUMED')
					GROUP BY `type`";
			$res=$this->db_result($sql);		
			foreach ($res as $row){
				$details[$row['type']]['taken'] = $row['taken'];
			}
			// Pending
			$html .='<div class="panel-body">
					 <div id ="div_calendar">
					 <table align="center" id ="total_leaves" border="1" class="table table-striped table-bordered table-hover" style="border-collapse:collapse; width:auto; height:auto;">';
			$html .= '<tr class="ui-widget-header"><td colspan='.(count($result)+2).' style="font-weight:bold;">Leave Details for year: '.$_SESSION['leave_year'].'</td></tr>';
			$html .='<tr class="ui-widget-header ui-corner-all">';
			$html .='<th>Type</th>';
			$lhtml = '<tr class="ui-widget-content ui-corner-all"><th>Opening Bal</th>';
			$thtml = '<tr class="ui-widget-content ui-corner-all"><th>Consumed</th>';
			$bhtml = '<tr class="ui-widget-content ui-corner-all"><th>Remaining</th>';
			$total =0;
			$taken =0;
			foreach($details as $key=>$child_details){
				$html .='<th>'.$key.' - ('.$child_details["name"].')</th>';
				$lhtml .='<td align="right">'.$child_details['total'].'</td>';
				$thtml .='<td align="right">'.$child_details['taken'].'</td>';
				$bhtml .='<td align="right"><b>'.($child_details['total'] - $child_details['taken'] ).'</b></td>';
				
				$total += $child_details['total'];
				$taken += $child_details['taken'];
			}
			$html .='<th>Total</th>';
			$html .='</tr>';

			$lhtml .='<td align="right"><b>'.$total.'</b></td></tr>';
			$thtml .='<td align="right"><b>'.$taken.'</b></td></tr>';
			$bhtml .='<td align="right"><b>'.($total - $taken).'</b></td></tr>';
			$html .= $lhtml;
			$html .= $thtml;
			$html .= $bhtml;
			$html .='</table></div></div>';
		}else{
			$html .='<span style="font-weight:bold;align:center;">Pleae Check your leave master</span>';
		}
		return $html;
	}

	function emp_leave_details(){
		$sql = "SELECT  date_format(posting_date,'%d-%m-%Y')as posting_date,
						type,
						date_format(start_date,'%d-%m-%Y')as start_date,
						date_format(end_date,'%d-%m-%Y')as end_date,
						`leave`,
						description,
						status,
						remarks
				FROM emp_leave_ledger
				WHERE emp_id = '".$_SESSION['emp_id']."'
				AND calc_year = '".$_SESSION['leave_year']."'
				ORDER BY ts DESC";
		$res=$this->db_result($sql);		
		$html ='';
		if(count($res)){
			$html .='<div id ="div_calendar">';
			$html .='<table border=1 style="width:auto; height:auto; border-collapse:collapse;" class="table table-striped table-bordered table-hover" align="center">';
			$html .='<tr><th colspan="9">My Leave Details for year: '.$_SESSION['leave_year'].'</th></tr>';
			$html .='<tr class="ui-widget-header ui-corner-all">
						<th>#</th>
						<th>Apply Date</th>
						<th>Type</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>Leave Days</th>
						<th>Description</th>
						<th>Status</th>
						<th>Remarks</th>
					</tr>';
			$count =1;
			foreach ($res as $row){
				$html .='<tr class="ui-widget-content ui-corner-all">
							 <td>'.$count.'</td>				
							 <td>'.$row['posting_date'].'</td>				
							 <td>'.$row['type'].'</td>				
							 <td>'.$row['start_date'].'</td>				
							 <td>'.$row['end_date'].'</td>				
							 <td align="right">'.$row['leave'].'</td>';
				if(strlen($row['description'])<=30){
    					$html .='<td style="cursor:pointer;" title="'.$row['description'].'">'.$row['description'].'</td>';
  				}else{
  						$html .='<td style="cursor:pointer;" title="'.$row['description'].'">'.substr($row['description'],0,30).' ...</td>';  	
  				}
				$html .='<td>'.$row['status'].'</td>';
				if(strlen($row['remarks'])<=30){
    					$html .='<td style="cursor:pointer;" title="'.$row['remarks'].'">'.$row['remarks'].'</td>';
  				}else{
  						$html .='<td style="cursor:pointer;" title="'.$row['remarks'].'">'.substr($row['remarks'],0,30).' ...</td>';  	
  				}	
				$html .='</tr>';					
				$count++;
			}
			$html .='</table></div>';
		}else{
			$html .="<h3>No Leave Applied in the year ".$_SESSION['leave_year']."</h3>";
		}
		return $html;
	}	

	function team_leave_details(){
		$sql = "SELECT  el.slno as slno,
						el.emp_id,
						e.name,
						date_format(el.posting_date,'%d-%m-%Y')as posting_date,
						el.type,
						date_format(el.start_date,'%d-%m-%Y')as start_date,
						date_format(el.end_date,'%d-%m-%Y')as end_date,
						el.`leave`,
						el.description,
						el.status
		FROM emp_leave_ledger el
		LEFT JOIN employee e ON el.emp_id = e.id
		WHERE el.approved_by = '".$_SESSION['emp_id']."'
		AND status = 'APPLIED'
		AND el.calc_year = '".$_SESSION['leave_year']."'
		ORDER BY el.emp_id,el.posting_date";
		$res=$this->db_result($sql);
		$html ='';
		if(count($res)){
			$html .='<div class="panel-body">
					<div class="dataTable_wrapper">';
			$html .='<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
			$html .='<tr class="ui-widget-header ui-corner-all">
						<th><input type="checkbox" class="selected_all_box" id="selected_all_box" /></th>
						<th>Employee ID</th>
						<th>Employee Name</th>
						<th>Posting Date</th>
						<th>Type</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>Day Leave</th>
						<th>Description</th>
						<th>Status</th>
					</tr>';
			$count =1;
			foreach ($res as $row){
				$html .='<tr class="ui-widget-content ui-corner-all">
							 <td><input type="checkbox" class="get_selected_box" name="'.$row['slno'].'"></td>				
							 <td>'.$row['emp_id'].'</td>				
							 <td>'.$row['name'].'</td>				
							 <td>'.$row['posting_date'].'</td>				
							 <td>'.$row['type'].'</td>				
							 <td>'.$row['start_date'].'</td>				
							 <td>'.$row['end_date'].'</td>				
							 <td align="right">'.$row['leave'].'</td>				
							 <td>'.$row['description'].'</td>				
							 <td>'.$row['status'].'</td>				
						 </tr>';					
				$count++;
			}
			$html .='</table>';
			$html .='</div></div>';
			$html .='<div class="form-group col-lg-12">&nbsp;</div>';
			$html .='<div class="form-group col-lg-12">
                		<label>Remarks</label>
                		<textarea class="form-control" rows="3" id="action_remarks"></textarea>
            	  	</div>';
			$html .='<div class="form-group col-lg-11">
                        <button class="btn btn-primary pull-right" id="leave_approve">Approve</button>
                     </div>
                     <div class="form-group col-lg-1">
                        <button class="btn btn-danger pull-right" id="leave_reject">Reject</button>
                     </div>';
		}else{
			$html .="<h3>No Details available</h3>";
		}
		return $html;
	}

}

?>