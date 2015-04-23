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
$get_emp_projects = $objPortal->get_emp_projects();
$get_all_projects = $objPortal->get_all_projects();

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
                                        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#emp_doc_model"><i class="fa fa-plus fa-fw"></i> Add Projects</button>
                                    </div>
                                    <div class="modal fade" id="emp_doc_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title" id="myModalLabel">Add Projects</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group row">
                                                        <div class="col-sm-4"> 
                                                            <label>Code</label>
                                                        </div>
                                                        <div class="col-sm-7"> 
                                                            <select class="form-control" id="code">
                                                                <?php echo $get_all_projects ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row"> 
                                                        <div class="col-sm-4">                                           
                                                            <label>Name</label>
                                                        </div>
                                                        <div class="col-sm-7">           
                                                            <input class="form-control" type="text" id="Project_name" size="30">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">                                           
                                                            <label>Reporting To</label>
                                                        </div>
                                                        <div class="col-sm-7">           
                                                            <input class="form-control" type="text" id="report" size="30">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">                                           
                                                            <label>Is Completed</label>
                                                        </div>
                                                        <div class="col-sm-7">           
                                                            <input class="form-control" type="checkbox" id="is_completed" size="30">
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
                                                    <button type="button" class="btn btn-primary" id="save_emp_documents">Save changes</button>
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