<?php

session_start();

include 'sql.php';

$methodToCall = $_POST['methodToCall'];
$dataset = $_POST['dataset'];

if($methodToCall == 'valida'){
	$senha = md5($dataset['password']);
	
	$check = DB::query('SELECT id_usuario, nome FROM usuario WHERE usuario = %s AND senha = %s',$dataset['username'],$senha);

	$check = DB::get_row($check);

	if ($check == '') {
	    echo false;
	} else {
		$_SESSION['user'] = $check;
	    echo json_encode($check);
	}	
}