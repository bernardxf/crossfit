<?php 
	include 'sql.php';

	$methodToCall = $_POST['methodToCall'];
	$dataset = $_POST['dataset'];

	if($methodToCall == 'loadData'){
		$SQL = "SELECT nome, DATE_FORMAT(data_nasc, '%d/%m/%Y') as data_nasc FROM aluno WHERE DATE_FORMAT(data_nasc, '%m') = DATE_FORMAT(SYSDATE(), '%m') ORDER BY data_nasc";
	    $aniversarios = DB::get_rows(DB::query($SQL));

	    $result = ['aniversarios' => $aniversarios];
	    echo json_encode($result);
	}
?>