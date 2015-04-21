<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
if(!isset($_SESSION['user_id']) || $_SESSION['user_id']==''){
	header("location: index.php");
}
else
{
include_once("header.php");
include_once("database.cls.php");
$objDetails = new Database;
$objleave_type = $objDetails->leave_type();

$today = $_SESSION['workDate'];

?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Apply Leave</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Apply Leave</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            	<div class="form-group col-lg-6 ">
							                        <label>Start Date</label>
							                        <div class="input-group date date_picker">
								                        <input class="form-control" name="start_date" id="start_date" value="<?php echo $_SESSION['workDate'] ?>" >
								                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
								                    </div>
							                    </div>
							                    <div class="form-group col-lg-6">
							                        <label>End Date</label>
							                        <div class="input-group date date_picker">
							                        	<input class="form-control" name="end_date" id="end_date" value="<?php echo $_SESSION['workDate'] ?>" >
							                    		<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
								                    </div>
							                    </div>
							                    <div class="form-group col-lg-6">
                                            		<label>Leave types</label>
                                            		<select class="form-control" name="leave_types" id="leave_types">
                                            			<?php echo $objleave_type ?>
                                            		</select>
                                        	  </div>
                                        	  <div class="form-group col-lg-6">
							                        <label>Leaves</label>
							                        <input class="form-control" name="leave_days" id="leave_days">
							                  </div>
                                        	  <div class="form-group col-lg-12">
                                            	<label>Remarks</label>
                                            	<textarea class="form-control" rows="3" id="leave_remarks"></textarea>
                                        	  </div>
                                        	  <div class="form-group col-lg-12">
                                        	  	<button class="btn btn-primary pull-right" id="apply_leave">Save</button>
                                        	  </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <!-- /.row -->
        </div>
            <!-- /.container-fluid -->

<?php
include_once("fotter.php");
$html ='<script type="text/javascript">

$(document).ready(function () {                                  
	var date = new Date();
	date.setDate(date.getDate());
	$(".date_picker").datepicker({
	    format: "dd-mm-yyyy",
	    autoclose: true,
	    todayHighlight: true,
	    startDate: date,
	    daysOfWeekDisabled: [0,6],
	    setDate:new Date()
	});
});
</script>';
echo $html;
}
?>