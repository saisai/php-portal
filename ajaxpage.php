<?php
include_once('portal.cls.php');
include_once('database.cls.php');
if (isset($_POST['submit'])) {
	switch($_POST['submit']){
		case "commonAction":
			$json = json_decode($_POST['data']);
			$className = (string) $json->className; 
			$methodName = (string) $json->methodName;
			$object = new $className ;
			$result = $object -> $methodName($_POST['data']);
			echo $result;
			unset($object);
		break;
	}
}
?>