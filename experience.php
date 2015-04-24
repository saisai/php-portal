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
$get_emp_experience = $objPortal->get_emp_experience();
//$get_all_experience = $objPortal->get_all_experience();

?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Experience Details</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Experience</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#emp_exp_model"><i class="fa fa-plus fa-fw"></i> Add Experience</button>
                                    </div>
                                    <div class="modal fade" id="emp_exp_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title" id="myModalLabel">Add Experience</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group row">
                                                        <div class="col-sm-4"> 
                                                            <label>Company Name</label>
                                                        </div>
                                                        <div class="col-sm-7"> 
                                                            <input class="form-control" type="text" id="company_name" size="30">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row"> 
                                                        <div class="col-sm-4">                                           
                                                            <label>Total Experience</label>
                                                        </div>
                                                        <div class="col-sm-4">                                           
                                                            <label>Joining Date</label>
                                                        </div>
                                                         <div class="col-sm-4">                                           
                                                            <label>Leaving Date</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                         <div class="col-sm-4">           
                                                            <input class="form-control" type="text" id="total_experience" size="30">
                                                        </div>
                                                        <div class="col-sm-4">           
                                                            <input class="form-control date_picker" type="text" id="date_join" size="30">
                                                        </div>
                                                        <div class="col-sm-4">           
                                                            <input class="form-control date_picker" type="text" id="date_leaving" size="30">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">
                                                            <label>Designation</label>
                                                        </div>
                                                        <div class="col-sm-7">
                                                            <input class="form-control" type="text" id="designation" size="30">
                                                       </div> 
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">
                                                            <label>Role</label>
                                                        </div>
                                                        <div class="col-sm-7">
                                                            <input class="form-control" type="text" id="role" size="30">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">
                                                            <label>Team Size</label>
                                                        </div>
                                                        <div class="col-sm-7">
                                                            <input class="form-control" type="text" id="team_size" size="30">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">
                                                            <label>CTC</label>
                                                        </div>
                                                        <div class="col-sm-7">
                                                            <input class="form-control" type="text" id="ctc" size="30">
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
                                                    <button type="button" class="btn btn-primary" id="save_emp_experience">Save changes</button>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <div class="col-lg-12">
                                        <?php echo $get_emp_experience ?>
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
                
                var date = new Date();
                date.setDate(date.getDate());
                $(".date_picker").datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true,
                    todayHighlight: true,
                    setDate:new Date()
                });
            });
        </script>';
echo $html;

}
?>