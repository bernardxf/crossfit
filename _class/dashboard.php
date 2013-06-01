<?php 
	$methodToCall = $_POST['methodToCall'];
	if($methodToCall == 'loadData'){
		echo json_encode(array());
	}
	if($methodToCall == 'loadSelects'){
		echo json_encode(array());
	}
?>