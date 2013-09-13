<?php 
	include 'sql.php';

	$methodToCall = $_POST['methodToCall'];
	$dataset = $_POST['dataset'];

	if($methodToCall == 'loadData'){
		$SQL = "SELECT nome, DATE_FORMAT(data_nasc, '%d/%m/%Y') as data_nasc FROM aluno WHERE DATE_FORMAT(data_nasc, '%m') = DATE_FORMAT(SYSDATE(), '%m') ORDER BY data_nasc";
	    $aniversarios = DB::get_rows(DB::query($SQL));

	    $plano = DB::get_rows(DB::query("SELECT nome, DATE_FORMAT(plano_fim, '%d/%m/%Y') as plano_fim FROM aluno WHERE aluno_status = 'ativo' AND to_days(plano_fim) - to_days(NOW()) <= 7;"));

	    $result = array('aniversarios' => $aniversarios, 'plano' => $plano);
	    echo json_encode($result);
	}
?>