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
?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Settings</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Settings</div>
                                <div class="panel-body">
                                    <div class="col-lg-12">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Password Settings</div>
                                            <!-- /.panel-heading -->
                                            <form id="my_settings_form" data-toggle="validator" method="post">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <input class="form-control" placeholder="New Password" name="new_password" id="new_password" type="password" value="" data-minlength="6" data-error="Please enter your new password" required >
                                                                <span class="help-block">Minimum of 6 characters</span>
                                                                <div class="help-block with-errors"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <input class="form-control" placeholder="Confirm Password" name="confirm_password" id="confirm_password" type="password" data-minlength="6" value="" data-match="#new_password" data-match-error="Whoops, Password doesn't match"required >
                                                                <div class="help-block with-errors"></div>
                                                            </div>
                                                        </div>
                                                    </div>                                         
                                                    <div class="form-group col-md-offset-10">
                                                        <button class="btn btn-success" id="update_password">Save Password</button>
                                                    </div>
                                                </div>
                                            </form>
                                            <!-- /.panel-body -->
                                        </div>
                                        <!-- /.panel -->
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
include_once("footer.php");
$html ='<script type="text/javascript">

$(document).ready(function () {                                  
    $("#my_settings_form").validator() ;
});
</script>';
echo $html;
}
?>