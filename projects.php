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
$get_all_employee = $objPortal->get_all_employee();
$get_all_projects = $objPortal->get_all_projects();
$get_emp_projects = $objPortal->get_emp_projects("all");
$get_emp_projects_history = $objPortal->get_emp_projects("history");

?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Projects Details</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Projects</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#emp_project_model"><i class="fa fa-plus fa-fw"></i> Add Projects</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="modal fade" id="emp_project_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title" id="myModalLabel">Add Projects</h4>
                                                </div>
                                                <form class="form-horizontal" role="form" name="project_form" id="project_form" >
                                                    <div class="modal-body">
                                                        <div class="form-group row">
                                                            <div class="col-sm-4"> 
                                                                <label>Project</label>
                                                            </div>
                                                            <div class="col-sm-7"> 
                                                                <select class="form-control" id="project_code" data-rule-required="true" data-msg-required="Please select project ">
                                                                    <?php echo $get_all_projects ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row"> 
                                                            <div class="col-sm-4">                                           
                                                                <label>Company Name</label>
                                                            </div>
                                                            <div class="col-sm-7">
                                                                <input class="form-control" type="text" id="company_name" data-rule-required="true" data-msg-required="Please enter company name" placeholder="MSys">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-4">                                           
                                                                <label>Reporting To</label>
                                                            </div>
                                                            <div class="col-sm-7"> 
                                                                <select class="form-control" id="reporting_to" data-rule-required="true" data-msg-required="Please select name">
                                                                    <?php echo $get_all_employee ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <!--
                                                        <div class="form-group row">
                                                            <div class="col-sm-4">                                           
                                                                <label>Is Completed</label>
                                                            </div>
                                                            <div class="col-sm-7">           
                                                                <input class="form-control" type="checkbox" id="is_completed" size="30">
                                                            </div>
                                                        </div> 
                                                         -->                                                  
                                                        <div class="form-group row">
                                                            <div class="col-sm-12">
                                                                <label>Remarks</label>
                                                            
                                                                <textarea class="form-control" rows="3" id="remarks" ></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>    
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary" id="save_emp_projects">Save changes</button>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>

                                    <div class="col-lg-12">
                                        <?php echo $get_emp_projects ?>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php echo $get_emp_projects_history ?>
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
                $("#total_leaves").DataTable({
                    responsive: true
                });
            });
        </script>';
//echo $html;

}
?>