<?php 
	include 'sql.php';

	$methodToCall = $_POST['methodToCall'];
	$dataset = $_POST['dataset'];

	if($methodToCall == 'loadData'){
		$SQL = "SELECT nome, DATE_FORMAT(data_nasc, '%d/%m/%Y') as data_nasc FROM aluno WHERE DATE_FORMAT(data_nasc, '%m') = DATE_FORMAT(SYSDATE(), '%m') ORDER BY data_nasc";
	    $aniversarios = DB::get_rows(DB::query($SQL));

	    $plano = DB::get_rows(DB::query("SELECT nome, DATE_FORMAT(plano_fim, '%d/%m/%Y') as plano_fim FROM aluno WHERE aluno_status = 'ativo' AND to_days(plano_fim) - to_days(NOW()) <= 7 ORDER BY plano_fim"));
	    
	    $estacionamento = DB::get_rows(DB::query("SELECT E.id_aluno_fk, DATE_FORMAT(E.plano_fim, '%d/%m/%Y') as plano_fim, A.nome FROM estacionamento AS E INNER JOIN aluno AS A ON (E.id_aluno_fk = A.id_aluno) WHERE to_days(E.plano_fim) - to_days(NOW()) <= 7 ORDER BY E.plano_fim"));

	    $aluno = DB::get_rows(DB::query("SELECT nome, aluno_status FROM aluno WHERE aluno_status = 'trancado' ORDER BY nome ASC"));

	    $result = array('aniversarios' => $aniversarios, 'plano' => $plano, 'estacionamento' => $estacionamento, 'aluno' => $aluno);
	    echo json_encode($result);
	}
?>