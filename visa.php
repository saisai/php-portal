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
$get_emp_visa = $objPortal->get_emp_visa();
//$get_all_qualifications = $objPortal->get_all_qualifications();

?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Visa Details</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Visa</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#emp_visa_model"><i class="fa fa-plus fa-fw"></i> Add New</button>
                                    </div>
                                    <div class="modal fade" id="emp_visa_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title" id="myModalLabel">Add New</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group row">
                                                        <div class="col-sm-4"> 
                                                            <label>Type</label>
                                                        </div>
                                                        <div class="col-sm-7"> 
                                                           <input class="form-control" type="text" id="type" placeholder="H1B">
                                                                
                                                        </div>
                                                    </div>
                                                    <div class="form-group row"> 
                                                        <div class="col-sm-4">                                           
                                                            <label>Country</label>
                                                        </div>
                                                        <div class="col-sm-7">           
                                                            <input class="form-control" type="text" id="country" placeholder="USA">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">                                           
                                                            <label>Start Date</label>
                                                        </div>
                                                        <div class="col-sm-7">  
                                                            <div class="input-group date date_picker">          
                                                                <input class="form-control" name="start_date" id="start_date" placeholder="Starting Date">
                                                                <span  class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                            </div>  
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">                                           
                                                            <label>Expired Date</label>
                                                        </div>
                                                        <div class="col-sm-7"> 
                                                            <div class="input-group date date_picker">          
                                                                <input class="form-control" name="end_date" id="end_date" placeholder="Expired Date">
                                                                <span  class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                            </div>  
                                                        </div>
                                                    </div>                                                    
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <label>Remarks</label>
                                                            <textarea class="form-control" rows="3" id="remarks" ></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary" id="save_emp_visa">Save changes</button>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <div class="col-lg-12">
                                        <?php echo $get_emp_visa ?>
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