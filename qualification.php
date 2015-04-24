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
include_once("portal.cls.php");

$objPortal = new Portal;
$get_emp_qualification = $objPortal->get_emp_qualification();
$get_all_qualifications = $objPortal->get_all_qualifications();

?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Qualification Details</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <!-- /.row -->
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Qualifications</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#emp_qualification_model"><i class="fa fa-plus fa-fw"></i> Add New</button>
                                    </div>
                                    <div class="modal fade" id="emp_qualification_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title" id="myModalLabel">Add New Qualifications</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group row">
                                                        <div class="col-sm-4"> 
                                                            <label>Course Type</label>
                                                        </div>
                                                        <div class="col-sm-7"> 
                                                            <select class="form-control" id="course_type">
                                                                <?php echo $get_all_qualifications ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row"> 
                                                        <div class="col-sm-4">                                           
                                                            <label>Board Name</label>
                                                        </div>
                                                        <div class="col-sm-7">           
                                                            <input class="form-control" type="text" id="board_name" size="30" placeholder="University Name">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">                                           
                                                            <label>Course Name</label>
                                                        </div>
                                                        <div class="col-sm-7">           
                                                            <input class="form-control" type="text" id="course_name" size="30">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">                                           
                                                            <label>Total Mark</label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label>Earned Mark</label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label>Percentage</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">           
                                                            <input class="form-control" type="text" id="total_mark" size="30">
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input class="form-control" type="text" id="obtained_mark" size="30">
                                                       </div> 
                                                        <div class="col-sm-4">
                                                            <input class="form-control" type="text" id="percentage" size="30" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">
                                                            <label>Passing Year</label>
                                                        </div>
                                                        <div class="col-sm-7">
                                                            <input class="form-control" type="text" id="year_of_passing" size="30">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">
                                                            <label>Grade</label>
                                                        </div>
                                                        <div class="col-sm-7">
                                                            <input class="form-control" type="text" id="grade" size="30">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <label>Remarks</label>
                                                        
                                                            <textarea class="form-control" rows="3" id="remarks" cols="30" ></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary" id="save_emp_qualification">Save changes</button>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <div class="col-lg-12">
                                    	<?php echo $get_emp_qualification ?>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
<?php
include_once("fotter.php");

$html ='<script type="text/javascript">
        $(document).ready(function () {
            /*$("#total_leaves").DataTable({
                responsive: true
            });*/

            $("#obtained_mark").focusout(function() {
                var total_mark= parseFloat(($("#total_mark").val()))
                var obtained_mark= parseFloat(($("#obtained_mark").val()))
                if(total_mark>obtained_mark){
                    var result = (obtained_mark/ total_mark)*100;
                    var res=result.toFixed(2);
                    if (!isFinite(result)) result = 0;
                }
                 $("#percentage").val(res);
            });
        });
        </script>';
echo $html;

}
?>