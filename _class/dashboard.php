<?php 
	$methodToCall = $_POST['methodToCall'];
	if($methodToCall == 'loadData'){
		echo json_encode(array());
	}
?>