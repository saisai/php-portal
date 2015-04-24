<?php
 if(!isset($_SESSION)){ 
        session_start(); 
 } 
include_once ("database.cls.php");

class Portal extends DataBase 
{
	function Portal()
	{
		parent::DataBase();
	}

	function get_all_documents (){
		$sql = "SELECT code,
					   name
				FROM document
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

	function get_all_qualifications (){
		$sql = "SELECT code,
					   name
				FROM qualifications
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

	function get_all_projects (){
		$sql = "SELECT code,
					   name
				FROM projects
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

	function get_all_employee (){
		$sql = "SELECT id as code,
					   name
				FROM employee
				ORDER BY name";
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

	function save_apply_leave($data){
		$json = json_decode($data);
		$sql ="SELECT reporting_to FROM employee WHERE id='".$_SESSION['emp_id']."'";
		$res = $this->db_result($sql);

		$sql = 'INSERT INTO emp_leave_ledger 
							(emp_id,  posting_date,
							type,  calc_year,
							start_date, end_date,
							`leave`,description,
							approved_by,
							status) VALUES';
		$sql .= "('".addslashes($_SESSION['emp_id'])."',
				  '".$this->dmy2ymd($_SESSION['workDate'])."',
				  '".addslashes($json->leave_types)."',
				  '".addslashes($_SESSION['leave_year'])."',
				  '".$this->dmy2ymd($json->start_date)."',
				  '".$this->dmy2ymd($json->end_date)."',
				  '".addslashes($json->leave_days)."',
				  '".addslashes($json->leave_remarks)."',
				  '".addslashes($res[0]['reporting_to'])."',
				  'APPLIED')";
		$res = $this->db_write($sql);
		if($res > 0 ){
			return '1';
		}else{
			return '0';
		}
	}

	function save_team_leave_status($data){
		$json = json_decode($data);
		$slno=implode("', '",$json->sl_no);
		$sql = "UPDATE emp_leave_ledger 
				SET status = '".$json->status."',
				remarks = '".$json->action_remarks."'
				WHERE slno IN ('$slno')";
		$res = $this->db_write($sql);
		if(count($res) > 0){
			return '1';
		}else{
			return '0';
		}
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
		if(!count($res)){
			$html .='<div class="row">
                		<div class="col-lg-6 col-md-offset-3">
                    		<div class="panel-body">
								<div class="alert alert-info" align="center" style="font-weight:bold;">
					            	No Details Available
					        	</div>
							</div>
						</div>
					</div>';
			return $html;
		}

		$details = array();
		foreach($res as $val){
			$details[$val['emp_id']]['name'] = $val['name']; 
			$details[$val['emp_id']]['leave_details'][$val['slno']]['posting_date'] = $val['posting_date'];
			$details[$val['emp_id']]['leave_details'][$val['slno']]['type'] = $val['type'];
			$details[$val['emp_id']]['leave_details'][$val['slno']]['start_date'] = $val['start_date'];
			$details[$val['emp_id']]['leave_details'][$val['slno']]['end_date'] = $val['end_date'];
			$details[$val['emp_id']]['leave_details'][$val['slno']]['leave'] = $val['leave'];
			$details[$val['emp_id']]['leave_details'][$val['slno']]['description'] = $val['description'];
		}
		$html .='<div class="panel-body">';
        $html .='<div class="col-lg-12">';
        foreach ($details as $key => $emp_details){
        	$html .='<div class="panel panel-default">
        				<div class="panel-heading">'.$key.'-('.$emp_details['name'].')</div>';
        	$html .='<div class="panel-body">
                            <div class="table-responsive">
					        	<table class="table table-striped table-bordered table-hover team_view_leaves" >
			                            <thead>
			                            	<tr>
				                                <th><input type="checkbox" class="selected_all_box" /></th>
												<th>Posting Date</th>
												<th>Type</th>
												<th>Start Date</th>
												<th>End Date</th>
												<th>Day Leave</th>
												<th>Description</th>
											</tr>
			                            </thead>';
        	foreach ($emp_details['leave_details'] as $slno => $values) {
        		$html .='<tbody>
        					<tr>
								<td><input type="checkbox" class="get_selected_box" name="'.$slno.'"></td>
								<td>'.$values['posting_date'].'</td>
								<td>'.$values['type'].'</td>
								<td>'.$values['start_date'].'</td>
								<td>'.$values['end_date'].'</td>
								<td>'.$values['leave'].'</td>';
				  if(strlen($values['description'])<=30){
    					$html .='<td style="cursor:pointer;" title="'.$values['description'].'">'.$values['description'].'</td>';
  				  }else{
  						$html .='<td style="cursor:pointer;" title="'.$values['description'].'">'.substr($values['description'],0,30).' ...</td>';  	
  				  }
        		$html .='</tr>
        				 </tbody>';
        	}
        	$html .='</table>
        			 </div>
        			 </div>';
        	$html .='</div>';
        }
        $html .='
        		 </div>';
		$html .='</div>';
		$html .='<div class="panel-body">';
		$html .='<div class="form-group col-lg-12">
					<label>Remarks</label>
						<textarea class="form-control" rows="3" id="action_remarks"></textarea>
					</div>';
		$html .='<div class="form-group col-lg-11">
					<button class="btn btn-primary pull-right" id="leave_approve">Approve</button>
				</div>
				<div class="form-group col-lg-1">
				<button class="btn btn-danger pull-right" id="leave_reject">Reject</button>
				</div>
				</div>';
		return $html;
	}

	/* start settings.php */
	function update_user_password($data){
		$json = json_decode($data);
		$sql ="UPDATE employee 
			   SET password = password('".$json->new_password."')
			   WHERE id='".$_SESSION['emp_id']."'
			   AND user_id='".$_SESSION['user_id']."'";
		$res = $this->db_write($sql);
		if(count($res) > 0 ){
			return '1';
		}else{
			return '0';
		}

	}
	/* end settings.php */

	function get_emp_documents(){
		$sql ="SELECT ed.code,
					  d.name as doc_name,
					  course,ed.doc_type,
					  doc_file,
					  remarks,
					  in_hand,
					  date_format(return_date,'%d-%m-%Y')as return_date,
					  verified,
					  returned 
				FROM emp_documents ed
				LEFT JOIN document d ON d.code = ed.code
				WHERE emp_id ='".$_SESSION['emp_id']."'
				ORDER BY ed.ts DESC ";
		$res=$this->db_result($sql);
		$html ='';
		if(!count($res)){
			$html .='<div class="row">
                		<div class="col-lg-6 col-md-offset-3">
                    		<div class="panel-body">
								<div class="alert alert-info" align="center" style="font-weight:bold;">
					            	No Details Available
					        	</div>
							</div>
						</div>
					</div>';
			return $html;
		}

		$html .='<div class="panel-body">';
        $html .='<div class="col-lg-12">';
    	$html .='<div class="panel panel-default">
    				<div class="panel-heading">Uploaded Documents ('.count($res).')</div>';
    	$html .='<div class="panel-body">
                        <div class="table-responsive">
				        	<table class="table table-striped table-bordered table-hover team_view_leaves" >
		                            <thead>
		                            	<tr>
			                                <th>#</th>
											<th>Name of the Document</th>
											<th>Course</th>
											<th>File Name</th>
											<th>In Hand</th>
											<th>Returned date</th>
											<th>Verified</th>
											<th>Returned</th>
											<th>Remarks</th>
										</tr>
		                            </thead>';
        $count =1;
        foreach ($res as $values) {
        		$hand = ($values['in_hand'] == 'org') ? "Original":"Copy";
        		$course = ($values['course'] == 'REGULAR') ? "Regular":"Distance";
        		$verified = ($values['verified']) ? "checked":"";
        		$returned = ($values['returned']) ? "checked":"";
        		$html .='<tbody>
        					<tr>
								<td>'.$count.'</td>
								<td>'.$values['doc_name'].'</td>
								<td>'.$course.'</td>
								<td>'.$values['doc_file'].'</td>
								<td>'.$hand.'</td>
								<td>'.$values['return_date'].'</td>
								<td><input type="checkbox" '.$verified.' disabled></td>
								<td><input type="checkbox" '.$returned.' disabled></td>';
				  if(strlen($values['remarks'])<=30){
    					$html .='<td style="cursor:pointer;" title="'.$values['remarks'].'">'.$values['remarks'].'</td>';
  				  }else{
  						$html .='<td style="cursor:pointer;" title="'.$values['remarks'].'">'.substr($values['remarks'],0,30).' ...</td>';  	
  				  }
        		$html .='</tr>
        				 </tbody>';
        	$count++;
        }
    	$html .='</table>
    			 </div>
    			 </div>';
    	$html .='</div>';
    	return $html;
	}

	function save_emp_documents($data){
 		$json = json_decode($data);
 		$sql = 'INSERT INTO emp_documents 
							(emp_id, upload_date,
							 code, doc_file,
							course,  in_hand,line_no_,
							remarks) VALUES';
		$sql .= "('".addslashes($_SESSION['emp_id'])."',
				  '".$this->dmy2ymd($_SESSION['workDate'])."',
				  '".addslashes($json->doc_code)."',
				  '".addslashes($json->doc_file)."',
				  '".addslashes($json->course)."',
				  '".addslashes($json->in_hand)."',
				  '10000',
				  '".addslashes($json->remarks)."')";
		 $res = $this->db_write($sql);
		if($res > 0 ){
			return '1';
		}else{
			return '0';
		}
	}

	function get_emp_qualification(){
		$sql ="SELECT 
				board,
				type,
				course_name,
				total_mark,
				obtained_mark,
				percentage,
				year_of_passing,
				grade,
				remarks 
				FROM emp_qualification
				WHERE emp_id ='".$_SESSION['emp_id']."' ";
		$res=$this->db_result($sql);
		$html ='';
		if(!count($res)){
			$html .='<div class="row">
                		<div class="col-lg-6 col-md-offset-3">
                    		<div class="panel-body">
								<div class="alert alert-info" align="center" style="font-weight:bold;">
					            	No Details Available
					        	</div>
							</div>
						</div>
					</div>';
			return $html;
		}

		$html .='<div class="panel-body">';
        $html .='<div class="col-lg-12">';
    	$html .='<div class="panel panel-default">
    				<div class="panel-heading">Qualifications ('.count($res).')</div>';
    	$html .='<div class="panel-body">
                        <div class="table-responsive">
				        	<table class="table table-striped table-bordered table-hover team_view_leaves" >
		                            <thead>
		                            	<tr>
			                                <th>#</th>
											<th>Board</th>
											<th>Type</th>
											<th>Course Name</th>
											<th>Total Mark</th>
											<th>Obtained Mark</th>
											<th>Percentage</th>
											<th>Year Of Passing</th>
											<th>Grade</th>
											<th>Remarks</th>
										</tr>
		                            </thead>';
        $count =1;
        foreach ($res as $values) {
        		$html .='<tbody>
        					<tr>
								<td>'.$count.'</td>
								<td>'.$values['board'].'</td>
								<td>'.$values['type'].'</td>
								<td>'.$values['course_name'].'</td>
								<td>'.$values['total_mark'].'</td>
								<td>'.$values['obtained_mark'].'</td>
								<td>'.$values['percentage'].'</td>
								<td>'.$values['year_of_passing'].'</td>
								<td>'.$values['grade'].'</td>';
				  if(strlen($values['remarks'])<=30){
    					$html .='<td style="cursor:pointer;" title="'.$values['remarks'].'">'.$values['remarks'].'</td>';
  				  }else{
  						$html .='<td style="cursor:pointer;" title="'.$values['remarks'].'">'.substr($values['remarks'],0,30).' ...</td>';  	
  				  }
        		$html .='</tr>
        				 </tbody>';
        	$count++;
        }
    	$html .='</table>
    			 </div>
    			 </div>';
    	$html .='</div>';
    	return $html;
	}

	function save_emp_qualification($data){
 		$json = json_decode($data);
 		$sql = 'INSERT INTO emp_qualification 
							(emp_id, upload_date,line_no_,
							 board,type,
							course_name,
							total_mark,
							obtained_mark,
							percentage,
							year_of_passing,
							grade,remarks) VALUES';
		$sql .= "('".addslashes($_SESSION['emp_id'])."',
				  '".$this->dmy2ymd($_SESSION['workDate'])."',
				  '10000',
				  '".addslashes($json->board_name)."',
				  '".addslashes($json->course_type)."',
				  '".addslashes($json->course_name)."',
				  '".addslashes($json->total_mark)."',
				  '".addslashes($json->obtained_mark)."',
				  '".addslashes($json->percentage)."',
				  '".addslashes($json->year_of_passing)."',
				  '".addslashes($json->grade)."',
				  '".addslashes($json->remarks)."')";
		 $res = $this->db_write($sql);
		if($res > 0 ){
			return '1';
		}else{
			return '0';
		}
	}

	function get_emp_experience(){
		$sql ="SELECT 
				company_name,
				total_exp,
				date_format(doj,'%d-%m-%Y')as doj,
				date_format(dol,'%d-%m-%Y')as dol,
				designation,
				role,
				team_size,
				ctc,
				remarks 
				FROM emp_experience
				WHERE emp_id ='".$_SESSION['emp_id']."' 
				ORDER BY ts DESC";
		$res=$this->db_result($sql);
		$html ='';
		if(!count($res)){
			$html .='<div class="row">
                		<div class="col-lg-6 col-md-offset-3">
                    		<div class="panel-body">
								<div class="alert alert-info" align="center" style="font-weight:bold;">
					            	No Details Available
					        	</div>
							</div>
						</div>
					</div>';
			return $html;
		}

		$html .='<div class="panel-body">';
        $html .='<div class="col-lg-12">';
    	$html .='<div class="panel panel-default">
    				<div class="panel-heading">Experience ('.count($res).')</div>';
    	$html .='<div class="panel-body">
                        <div class="table-responsive">
				        	<table class="table table-striped table-bordered table-hover team_view_leaves" >
		                            <thead>
		                            	<tr>
			                                <th>#</th>
											<th>Company Name</th>
											<th>Total Experience</th>
											<th>Date Of Join </th>
											<th>Date Of Leaving</th>
											<th>Designation </th>
											<th>Role</th>
											<th>Team Size </th>
											<th>CTC</th>
											<th>Remarks</th>
										</tr>
		                            </thead>';
        $count =1;
        foreach ($res as $values) {
        		$html .='<tbody>
        					<tr>
								<td>'.$count.'</td>
								<td>'.$values['company_name'].'</td>
								<td>'.$values['total_exp'].'</td>
								<td>'.$values['doj'].'</td>
								<td>'.$values['dol'].'</td>
								<td>'.$values['designation'].'</td>
								<td>'.$values['role'].'</td>
								<td>'.$values['team_size'].'</td>
								<td>'.$values['ctc'].'</td>';
				  if(strlen($values['remarks'])<=30){
    					$html .='<td style="cursor:pointer;" title="'.$values['remarks'].'">'.$values['remarks'].'</td>';
  				  }else{
  						$html .='<td style="cursor:pointer;" title="'.$values['remarks'].'">'.substr($values['remarks'],0,30).' ...</td>';  	
  				  }
        		$html .='</tr>
        				 </tbody>';
        	$count++;
        }
    	$html .='</table>
    			 </div>
    			 </div>';
    	$html .='</div>';
    	return $html;
	}

	function save_emp_experience($data){
 		$json = json_decode($data);
 		$sql = 'INSERT INTO emp_experience 
							(emp_id, upload_date,line_no_,
							 company_name,
							 total_exp,
							 doj,dol,
							 designation,
							 role,team_size,ctc,
							remarks) VALUES';
		$sql .= "('".addslashes($_SESSION['emp_id'])."',
				  '".$this->dmy2ymd($_SESSION['workDate'])."',
				  '10000',
				  '".addslashes($json->company_name)."',
				  '".addslashes($json->total_experience)."',
				  '".$this->dmy2ymd($json->date_join)."',
				  '".$this->dmy2ymd($json->date_leaving)."',
				  '".addslashes($json->designation)."',
				  '".addslashes($json->role)."',
				  '".addslashes($json->team_size)."',
				  '".addslashes($json->ctc)."',
				  '".addslashes($json->remarks)."')";
		 $res = $this->db_write($sql);
		if($res > 0 ){
			return '1';
		}else{
			return '0';
		}
	}


	function get_emp_projects($get){
		if($get == "all"){
			$title = 'Projects';
			$filter ='';
		}else{
			$title = 'Projects History';
			$filter = 'AND is_completed=1';
		}

		$sql ="SELECT 
				ep.code as code,
				ep.name as name,
				e.name as reporting_to,
				is_completed,
				remarks
				FROM emp_projects ep
				LEFT JOIN employee e ON e.id = ep.reporting_to 
				WHERE emp_id ='".$_SESSION['emp_id']."'
				 ".$filter." 
				 ORDER BY ep.ts DESC";
		$res=$this->db_result($sql);
		$html ='';
		if(!count($res)){
			$html .='<div class="row">
                		<div class="col-lg-6 col-md-offset-3">
                    		<div class="panel-body">
								<div class="alert alert-info" align="center" style="font-weight:bold;">
					            	No Details Available
					        	</div>
							</div>
						</div>
					</div>';
			return $html;
		}

		$html .='<div class="panel-body">';
        $html .='<div class="col-lg-12">';
    	$html .='<div class="panel panel-default">
    				<div class="panel-heading"> '.$title.' ('.count($res).')</div>';
    	$html .='<div class="panel-body">
                        <div class="table-responsive">
				        	<table class="table table-striped table-bordered table-hover team_view_leaves" >
		                            <thead>
		                            	<tr>
			                                <th>#</th>
											<th>Code</th>
											<th>Name</th>
											<th>Reporting To</th>
											<!-- <th>Is Completed</th> -->
											<th>Remarks</th>
										</tr>
		                            </thead>';
        $count =1;
        foreach ($res as $values) {
			// $complete = ($values['is_completed']) ? "checked":"";
        		$html .='<tbody>
        					<tr>
								<td>'.$count.'</td>
								<td>'.$values['code'].'</td>
								<td>'.$values['name'].'</td>
								<td>'.$values['reporting_to'].'</td>';
						//$html .='<td><input type="checkbox" '.$complete.' disabled></td>';
				  if(strlen($values['remarks'])<=30){
    					$html .='<td style="cursor:pointer;" title="'.$values['remarks'].'">'.$values['remarks'].'</td>';
  				  }else{
  						$html .='<td style="cursor:pointer;" title="'.$values['remarks'].'">'.substr($values['remarks'],0,30).' ...</td>';  	
  				  }
        		$html .='</tr>
        				 </tbody>';
        	$count++;
        }
    	$html .='</table>
    			 </div>
    			 </div>';
    	$html .='</div>';
    	return $html;
	}


	function save_emp_projects($data){
 		$json = json_decode($data);
 		$sql = 'INSERT INTO emp_projects 
							(emp_id, upload_date,line_no_,
							 code, name,
							reporting_to,  is_completed,
							remarks) VALUES';
		$sql .= "('".addslashes($_SESSION['emp_id'])."',
				  '".$this->dmy2ymd($_SESSION['workDate'])."',
				  '10000',
				  '".addslashes($json->project_code)."',
				  '".addslashes($json->company_name)."',
				  '".addslashes($json->reporting_to)."',
				  '".addslashes($json->is_completed)."',
				  '".addslashes($json->remarks)."')";
		 $res = $this->db_write($sql);
		if($res > 0 ){
			return '1';
		}else{
			return '0';
		}
	}


	function get_emp_visa(){
		$sql ="SELECT 
				type,
				country,
				start_date,
				end_date,
				remarks
				FROM emp_visa
				WHERE emp_id ='".$_SESSION['emp_id']."' 
				ORDER BY ts DESC";
		$res=$this->db_result($sql);
		$html ='';
		if(!count($res)){
			$html .='<div class="row">
                		<div class="col-lg-6 col-md-offset-3">
                    		<div class="panel-body">
								<div class="alert alert-info" align="center" style="font-weight:bold;">
					            	No Details Available
					        	</div>
							</div>
						</div>
					</div>';
			return $html;
		}

		$html .='<div class="panel-body">';
        $html .='<div class="col-lg-12">';
    	$html .='<div class="panel panel-default">
    				<div class="panel-heading">Visa ('.count($res).')</div>';
    	$html .='<div class="panel-body">
                        <div class="table-responsive">
				        	<table class="table table-striped table-bordered table-hover team_view_leaves" >
		                            <thead>
		                            	<tr>
			                                <th>#</th>
											<th>Type</th>
											<th>Country</th>
											<th>Start Date Name</th>
											<th>End Date Mark</th>
											<th>Remarks</th>
										</tr>
		                            </thead>';
        $count =1;
        foreach ($res as $values) {
        		$html .='<tbody>
        					<tr>
								<td>'.$count.'</td>
								<td>'.$values['type'].'</td>
								<td>'.$values['country'].'</td>
								<td>'.$values['start_date'].'</td>
								<td>'.$values['end_date'].'</td>';
				  if(strlen($values['remarks'])<=30){
    					$html .='<td style="cursor:pointer;" title="'.$values['remarks'].'">'.$values['remarks'].'</td>';
  				  }else{
  						$html .='<td style="cursor:pointer;" title="'.$values['remarks'].'">'.substr($values['remarks'],0,30).' ...</td>';  	
  				  }
        		$html .='</tr>
        				 </tbody>';
        	$count++;
        }
    	$html .='</table>
    			 </div>
    			 </div>';
    	$html .='</div>';
    	return $html;
	}

	function save_emp_visa($data){
 		$json = json_decode($data);
 		$sql = 'INSERT INTO emp_visa
							(emp_id, upload_date,line_no_,
							 type,country,
							 start_date,end_date,
							remarks) VALUES';
		$sql .= "('".addslashes($_SESSION['emp_id'])."',
				  '".$this->dmy2ymd($_SESSION['workDate'])."',
				  '10000',
				  '".addslashes($json->type)."',
				  '".addslashes($json->country)."',
				  '".$this->dmy2ymd($json->start_date)."',
				  '".$this->dmy2ymd($json->end_date)."',
				  '".addslashes($json->remarks)."')";
		 $res = $this->db_write($sql);
		if($res > 0 ){
			return '1';
		}else{
			return '0';
		}
	}

	function get_my_team_members(){
		$sql ="SELECT id,
					  name,
					  designation,
					  client_name,
					  project_name,
					  msys_email,
					  mobile_2,
					  skill_set 
				FROM employee
				WHERE reporting_to ='".$_SESSION['emp_id']."' ";
		$res=$this->db_result($sql);
		$html ='';
		if(!count($res)){
			$html .='<div class="row">
                		<div class="col-lg-6 col-md-offset-3">
                    		<div class="panel-body">
								<div class="alert alert-info" align="center" style="font-weight:bold;">
					            	No Details Available
					        	</div>
							</div>
						</div>
					</div>';
			return $html;
		}

				$html .='<div class="panel-body">';
        $html .='<div class="col-lg-12">';
    	$html .='<div class="panel panel-default">
    				<div class="panel-heading">Team Size ('.count($res).')</div>';
    	$html .='<div class="panel-body">
                        <div class="table-responsive">
				        	<table class="table table-striped table-bordered table-hover team_view_leaves" >
		                            <thead>
		                            	<tr>
			                                <th>#</th>
											<th>Employee ID</th>
											<th>Name</th>
											<th>Designation</th>
											<th>Client Name</th>
											<th>Project Name</th>
											<th>Email</th>
											<th>Mobile</th>
											<th>Skill Set</th>
										</tr>
		                            </thead><tbody>';
        $count =1;
        foreach ($res as $values) {
        		$html .='<tr>
								<td>'.$count.'</td>
								<td>'.$values['id'].'</td>
								<td>'.$values['name'].'</td>
								<td>'.$values['designation'].'</td>
								<td>'.$values['client_name'].'</td>
								<td>'.$values['project_name'].'</td>
								<td>'.$values['msys_email'].'</td>
								<td>'.$values['mobile_2'].'</td>';
				  if(strlen($values['skill_set'])<=10){
    					$html .='<td style="cursor:pointer;" title="'.$values['skill_set'].'">'.$values['skill_set'].'</td>';
  				  }else{
  						$html .='<td style="cursor:pointer;" title="'.$values['skill_set'].'">'.substr($values['skill_set'],0,10).' ...</td>';  	
  				  }
        		$html .='</tr>
        				 ';
        	$count++;
        }
    	$html .='</tbody></table>
    			 </div>
    			 </div>';
    	$html .='</div>';
    	return $html;
	}

	/*Start Dashbord*/
	function get_current_month_birth_days(){
		$sql ="SELECT name,
					  date_format(dob,'%d-%m-%Y')as dob,
					  client_name,
					  msys_email,
					  location,
					  IF(DAY(dob) = DAY(CURDATE()),1,0) as is_today
			   FROM employee 
			   WHERE MONTH(dob) = MONTH(NOW()) 
			   AND  DAY(dob) >= DAY(CURDATE())
			   ORDER BY DAY(dob)";
	    $res=$this->db_result($sql);
		$html ='';
		if(!count($res)){
			$html .='<div class="row">
	            		<div class="col-lg-6 col-md-offset-3">
	                		<div class="panel-body">
								<div class="alert alert-info" align="center" style="font-weight:bold;">
					            	No Birthdays Available
					        	</div>
							</div>
						</div>
					</div>';
			return $html;
		}
		$html .='<div class="panel-heading">
	                <i class="fa fa-gift fa-fw"></i> Upcoming Birthdays
	             </div>
	                <div class="panel-body">
	                    <div class="table-responsive">
	                        <table class="table table-striped table-bordered table-hover">
	                            <thead>
	                                <tr>
	                                    <th>#</th>
	                                    <th>Date</th>
	                                    <th>Name</th>
	                                    <th>Client Name</th>
	                                    <th>Location</th>
	                                    <th>Email</th>
	                                </tr>
	                            </thead><tbody>';

        $count =1;
        foreach ($res as $values) {
        		$is_today = ($values['is_today'])?"class='info' title='Today Birthday' ":"";
        		$html .='<tr '.$is_today.'>
								<td>'.$count.'</td>
								<td>'.$values['dob'].'</td>
								<td>'.$values['name'].'</td>
								<td>'.$values['client_name'].'</td>
								<td>'.$values['location'].'</td>
								<td>'.$values['msys_email'].'</td>';
        		$html .='</tr>';
        	$count++;
        }
	                                    
	         $html.='</tbody>
	         				</table>
	                    </div>
	                </div>';
		return $html;
	}

	function list_of_holidays(){
		$sql ="SELECT date_format(date,'%d-%m-%Y')as date,
					  day,
					  title,
					  is_optional 
			   FROM holidays 
			   WHERE date >= CURDATE() 
			   ORDER BY date";
	    $res=$this->db_result($sql);
		$html ='';
		if(!count($res)){
			$html .='<div class="row">
	            		<div class="col-lg-6 col-md-offset-3">
	                		<div class="panel-body">
								<div class="alert alert-info" align="center" style="font-weight:bold;">
					            	No Holidays Available
					        	</div>
							</div>
						</div>
					</div>';
			return $html;
		}
		$html .='<div class="panel-heading">
	                <i class="fa fa-smile-o fa-fw"></i> Upcoming Holidays
	             </div>
	                <div class="panel-body">
	                    <div class="table-responsive">
	                        <table class="table table-striped table-bordered table-hover">
	                            <thead>
	                                <tr>
	                                    <th>#</th>
	                                    <th>Date</th>
	                                    <th>Day</th>
	                                    <th>Holiday</th>
	                                </tr>
	                            </thead><tbody>';

        $count =1;
        foreach ($res as $values) {
        		$is_optional = ($values['is_optional'])?"class='danger' title='Optional Holiday'":"";
        		$html .='<tr '.$is_optional.'>
								<td>'.$count.'</td>
								<td>'.$values['date'].'</td>
								<td>'.$values['day'].'</td>
								<td>'.$values['title'].'</td>';
        		$html .='</tr>';
        	$count++;
        }
	                                    
	         $html.='</tbody>
	         				</table>
	                    </div>
	                </div>';
		return $html;
	}

	function get_widget_data (){
		$sql ="SELECT 
					(
						SELECT count(code) 
					 	FROM emp_documents 
					 	WHERE emp_id= '".$_SESSION['emp_id']."'
					) AS doc_count,
					(
						SELECT count(*) 
						FROM circulars 
						WHERE date >= CURDATE() 
					) AS circulars,
					(
						SELECT count(code) 
						FROM emp_projects 
						WHERE emp_id= '".$_SESSION['emp_id']."'
					) AS project_count,
					(
						SELECT count(emp_id) 
						FROM emp_leave_ledger 
						WHERE emp_id='".$_SESSION['emp_id']."' 
						AND status ='APPLIED' 
						AND calc_year ='".$_SESSION['leave_year']."'
					) AS leave_apply,
					(
						SELECT count(emp_id) 
						FROM emp_leave_ledger
					 	WHERE approved_by = '".$_SESSION['emp_id']."' 
					 	AND status ='APPLIED' 
					 	AND calc_year ='".$_SESSION['leave_year']."'
					 ) AS leave_approve";
		$res=$this->db_result($sql);
		return $res[0];
	}

	function get_circulars_details(){
		$sql = "SELECT slno,
					   date_format(date,'%d-%m-%Y')as cdate,
					   title,
					   description,
					   IF(DAY(date) = DAY(CURDATE()),1,0) as is_today
			    FROM circulars 
				WHERE date >= CURDATE() 
				ORDER BY date,priority";
		$res=$this->db_result($sql);
		$html ='';
		if(!count($res)){
			$html .='<div class="row">
                		<div class="col-lg-6 col-md-offset-3">
                    		<div class="panel-body">
								<div class="alert alert-info" align="center" style="font-weight:bold;">
					            	No Details Available
					        	</div>
							</div>
						</div>
					</div>';
			return $html;
		}

				$html .='<div class="panel-body">';
        $html .='<div class="col-lg-12">';
    	$html .='<div class="panel panel-default">
    				<div class="panel-heading">Team Size ('.count($res).')</div>';
    	$html .='<div class="panel-body">
                        <div class="table-responsive">
				        	<table class="table table-striped table-bordered table-hover team_view_leaves" >
		                            <thead>
		                            	<tr>
			                                <th>#</th>
											<th>Date</th>
											<th>Title</th>
											<th>Description</th>
											<th>View</th>
										</tr>
		                            </thead><tbody>';
        $count =1;
        foreach ($res as $values) {
        	 	$is_today = ($values['is_today'])?"class='info' title='Last Day' ":"";
        		$html .='<tr '.$is_today.'>
								<td>'.$count.'</td>
								<td>'.$values['cdate'].'</td>
								<td>'.$values['title'].'</td>';
				  if(strlen($values['description'])<=40){
    					$html .='<td style="cursor:pointer;" title="'.$values['description'].'">'.$values['description'].'</td>';
  				  }else{
  						$html .='<td style="cursor:pointer;" title="'.$values['description'].'">'.substr($values['description'],0,40).' ...</td>';  	
  				  }
  				$html .= '<td>
  								<button class="btn btn-primary" data-toggle="modal" data-target="#circulars_model_'.$values['slno'].'">
                                	Details
                            	</button>
                          </td>';
        		$html .='</tr>';
        	$count++;
        	$html .='<div class="modal fade" id="circulars_model_'.$values['slno'].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="myModalLabel">'.$values['title'].' - ('.$values['cdate'].')</h4>
                                    </div>
                                    <div class="modal-body">
                                        '.$values['description'].'
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>';
        }
    	$html .='</tbody></table>
    			 </div>
    			 </div>';
    	$html .='</div>';
    	return $html;
	}
	/*End Dashbord*/
}

?>