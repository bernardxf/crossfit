<?php 
	include 'sql.php';

	$methodToCall = $_POST['methodToCall'];
	$dataset = $_POST['dataset'];

	if($methodToCall == 'loadData'){
		$SQL = "SELECT nome, DATE_FORMAT(data_nasc, '%d/%m/%Y') as data_nasc FROM aluno WHERE DATE_FORMAT(data_nasc, '%m') = DATE_FORMAT(SYSDATE(), '%m') ORDER BY data_nasc";
	    $aniversarios = DB::get_rows(DB::query($SQL));

	    $plano = DB::get_rows(DB::query("SELECT nome, DATE_FORMAT(plano_fim, '%d/%m/%Y') as plano_fim FROM aluno WHERE aluno_status = 'ativo' AND to_days(plano_fim) - to_days(SYSDATE()) <= 7 ORDER BY YEAR(plano_fim) ASC, MONTH(plano_fim) ASC, DAY(plano_fim) ASC"));
	    
	    $estacionamento = DB::get_rows(DB::query("SELECT E.id_aluno_fk, DATE_FORMAT(E.plano_fim, '%d/%m/%Y') as plano_fim, A.nome FROM estacionamento AS E INNER JOIN aluno AS A ON (E.id_aluno_fk = A.id_aluno) WHERE estacionamento_status = 'trancado' OR to_days(E.plano_fim) - to_days(NOW()) <= 0 ORDER BY YEAR(E.plano_fim) ASC, MONTH(E.plano_fim) ASC, DAY(E.plano_fim) ASC"));

	    $aluno = DB::get_rows(DB::query("SELECT nome, aluno_status FROM aluno WHERE aluno_status = 'trancado' ORDER BY nome ASC"));

	    $alunos = DB::get_rows(DB::query("SELECT (SELECT count(*) FROM aluno WHERE aluno_status = 'ativo') as ativo, (SELECT count(*) FROM aluno WHERE id_desconto_fk = 14) as bolsa50, (SELECT count(*) FROM aluno WHERE id_desconto_fk = 15) as bolsa100, (SELECT count(*) FROM aluno WHERE aluno_status = 'inativo') as inativo, (SELECT count(*) FROM aluno WHERE aluno_status = 'trancado') as trancado FROM aluno LIMIT 0,1"));

	    $result = array('aniversarios' => $aniversarios, 'plano' => $plano, 'estacionamento' => $estacionamento, 'aluno' => $aluno, 'alunos' => $alunos);
	    echo json_encode($result);
	}
?>