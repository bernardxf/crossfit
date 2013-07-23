<?php

include 'sql.php';

$methodToCall = $_POST['methodToCall'];

if($methodToCall == 'loadSelects'){
	$aluno = DB::get_rows(DB::query('SELECT id_aluno, nome from aluno'));
	$selects = array('aluno' => $aluno);

	echo json_encode($selects);
}
?>