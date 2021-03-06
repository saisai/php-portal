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
$get_my_team_members = $objPortal->get_my_team_members();

?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Team Details</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">Members</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php echo $get_my_team_members ?>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once("footer.php");

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