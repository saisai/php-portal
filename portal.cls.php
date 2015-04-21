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
			$html .="<h3>No Details available</h3>";
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
		/*echo '<pre>';
		print_r($details);
		echo '</pre>';
		exit();*/
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
}

?>