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
<div ng-view></div>
<?php
include_once("footer.php");
}
?>