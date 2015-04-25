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
include_once("portal.cls.php");
$objDetails = new Database;
$emp_leave_total_details = $objDetails->emp_leave_total_details();
$emp_leave_details = $objDetails->emp_leave_details();
// $team_leave_details = $objDetails->team_leave_details();

$objPortal = new Portal;
$team_leave_details = $objPortal->team_leave_details();

?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Leave Details</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">My Leaves</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                            <?php echo $emp_leave_total_details ?>
                                            <?php echo $emp_leave_details ?>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
<?php
/*
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Leave Details
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#my_leave" data-toggle="tab">My Leaves</a>
                                </li>
                                <li><a href="#team_leave" data-toggle="tab">Team Leaves</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="my_leave">
                                    <?php echo $emp_leave_total_details ?>
                                    <?php echo $emp_leave_details ?>
                                </div>
                                <div class="tab-pane fade" id="team_leave">
                                    <?php echo $team_leave_details ?>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
            </div>
*/
?>
<?php
include_once("footer.php");

$html ='<script type="text/javascript">
            $(document).ready(function () {
                $("#total_leaves").DataTable({
                    responsive: true
                });
            });
        </script>';
echo $html;

}
?>