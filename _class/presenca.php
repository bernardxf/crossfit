<?php

include 'sql.php';

$SELECT = '';

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
	$data = $dataset['data'];
	$exp_data = explode('/', $data);
	$data = $exp_data[2]."-".$exp_data[1]."-".$exp_data[0];
	
	$horario = $dataset['horario'];
	$alunos = $dataset['alunos'];

	foreach ($alunos as $key => $aluno) {
		
		$num_senha = $key + 1;
		$id_aluno = $aluno['id_aluno'];


		DB::query($INSERT, $data, $horario, $num_senha, $id_aluno);
	}
}

if($methodToCall == 'delete'){

}

if($methodToCall == 'pesquisaAluno'){
	$nomeAluno = $dataset['nomeAluno'];

	if ($nomeAluno) {
		$query = "SELECT * from aluno WHERE nome like '".$nomeAluno."%'";
		$rows = DB::get_rows(DB::query($query));


		echo json_encode($rows);	
	} else {
		$query = "SELECT * from aluno";
		$rows = DB::get_rows(DB::query($query));
		
		echo json_encode($rows);	
	}
	
}

if($methodToCall == 'pesquisaPresenca'){
	$horario = $dataset['horario'];
	$data = $dataset['data'];

	
}

?>