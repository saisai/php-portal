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

	function save_apply_leave($data){
		$json = json_decode($data);
		$sql = 'INSERT INTO emp_leave_ledger 
							(emp_id,  posting_date,
							type,  calc_year,
							start_date, end_date,
							`leave`,description,
							status) VALUES';
		$sql .= "('".addslashes($_SESSION['emp_id'])."',
				  '".$this->dmy2ymd($_SESSION['workDate'])."',
				  '".addslashes($json->leave_types)."',
				  '".addslashes($_SESSION['leave_year'])."',
				  '".$this->dmy2ymd($json->start_date)."',
				  '".$this->dmy2ymd($json->end_date)."',
				  '".addslashes($json->leave_days)."',
				  '".addslashes($json->leave_remarks)."',
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
		echo '<pre>';
		print_r($json);
		echo '</pre>';
		$slno=implode("', '",$json->sl_no);
		$sql = "UPDATE emp_leave_ledger 
				SET status = '".$json->status."',
				remarks = '".$json->action_remarks."'
				WHERE slno IN ('$slno')";
		$res = $this->db_write($sql);
		if($res > 0 ){
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
				                                <th><input type="checkbox" class="selected_all_box" id="selected_all_box" /></th>
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
		return $json->new_password;
		$sql ="UPDATE employee 
			   SET password = password('".$json->new_password."')
			   WHERE id='".$_SESSION['emp_id']."'
			   AND user_id='".$_SESSION['user_id']."'";
		$res = $this->db_write($sql);
		if($res > 0 ){
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
}

?>