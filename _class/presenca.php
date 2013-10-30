<?php

include 'sql.php';

$INSERT = 'INSERT INTO presenca (data, horario, num_senha, id_aluno_fk) VALUES (%s, %s, %s, %s)';



$methodToCall = $_POST['methodToCall'];
$dataset = $_POST['dataset'];

if($methodToCall == 'loadSelects'){
	echo json_encode(array());
}

if($methodToCall == 'loadData'){
	echo json_encode(array());
}


if($methodToCall == 'save'){
	$aula = $dataset['aula'];
	$alunoAula = $dataset['aluno_aula'];
	if($aula['id_aula_fk']) {
		$alunosRemovidos = $dataset['alunos_removidos'];
		
		foreach ($alunosRemovidos as $aluno) {
			$SQL = 'DELETE from alunos_aula WHERE id_aluno_aula = %s';
			DB::query($SQL, $aluno['id_aluno_aula']);
		}

		foreach ($alunoAula as $key => $aluno) {
			if(!$aluno['id_aluno_aula']){
				$SQL = 'INSERT INTO alunos_aula(num_senha, id_aluno_fk, id_aula_fk) VALUES (%s, %s, %s)';
				DB::query($SQL, ($key+1), $aluno['id_aluno'], $aula['id_aula_fk']);
			}
		}

	} else {
		$horario = $aula['horario'];
		$data = $aula['data'];
		$exp_data = explode('/', $data);
		$data = $exp_data[2]."-".$exp_data[1]."-".$exp_data[0];

		$SQL = 'INSERT INTO aula(data, horario) VALUES (%s, %s)';
		DB::query($SQL, $data, $horario);

		$result = DB::get_rows(DB::query('SELECT MAX(id_aula) as lastId from aula'));

		foreach ($alunoAula as $key => $aluno) {
			$SQL = 'INSERT INTO alunos_aula(num_senha, id_aluno_fk, id_aula_fk) VALUES (%s, %s, %s)';
			DB::query($SQL, ($key+1), $aluno['id_aluno'], $result[0]['lastId']);
		}

	}
}

if($methodToCall == 'pesquisaAluno'){
	$nomeAluno = $dataset['nomeAluno'];

	if ($nomeAluno) {
		//$query = "SELECT id_aluno, nome from aluno WHERE nome like '".$nomeAluno."%'";
		$query = "SELECT A.id_aluno, A.nome, A.id_plano_fk, A.aluno_status, P.nome AS plano from aluno AS A INNER JOIN plano AS P ON (A.id_plano_fk = P.id_plano) WHERE A.nome like '%".$nomeAluno."%'";
		$rows = DB::get_rows(DB::query($query));


		echo json_encode($rows);	
	} else {
		$query = "SELECT A.id_aluno, A.nome, A.id_plano_fk, A.aluno_status, P.nome AS plano from aluno AS A INNER JOIN plano AS P ON (A.id_plano_fk = P.id_plano)";
		$rows = DB::get_rows(DB::query($query));

		echo json_encode($rows);	
	}
	
}

if($methodToCall == 'pesquisaAula'){
	$data = $dataset['data'];
	$exp_data = explode('/', $data);
	$data = $exp_data[2]."-".$exp_data[1]."-".$exp_data[0];

	$SQl = "SELECT aa.id_aula_fk, aa.id_aluno_aula, al.id_aluno, aa.num_senha, DATE_FORMAT(au.data, '%d/%m/%Y') as data, au.horario, al.nome from alunos_aula aa 
			join aula au on aa.id_aula_fk = au.id_aula
			join aluno al on aa.id_aluno_fk = al.id_aluno where data = '".$data."'";

	$rows['alunos_aula'] = DB::get_rows(DB::query($SQl));

	$SQl = "SELECT aa.id_aula_fk, count(aa.id_aluno_aula) as alunos_aula, DATE_FORMAT(au.data, '%d/%m/%Y') as data, au.horario from alunos_aula aa 
			join aula au on aa.id_aula_fk = au.id_aula
			join aluno al on aa.id_aluno_fk = al.id_aluno where data = '".$data."' GROUP BY aa.id_aula_fk";	

	$rows['aula_dia'] = DB::get_rows(DB::query($SQl));
	echo json_encode($rows);
}

?>