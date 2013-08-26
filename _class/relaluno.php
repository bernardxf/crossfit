<?php

include 'sql.php';

$methodToCall = $_POST['methodToCall'];
$dataset = $_POST['dataset'];

if($methodToCall == 'loadSelects'){
	$aluno = DB::get_rows(DB::query('SELECT id_aluno, nome from aluno'));
	$selects = array('aluno' => $aluno);

	echo json_encode($selects);
}

if($methodToCall == 'pesquisa'){
	$id_aluno = $dataset['id_aluno'];

	$SQL = "SELECT a.nome as aluno , p.nome as plano FROM aluno as a 
			join plano as p on a.id_plano_fk = p.id_plano
			where a.id_aluno = $id_aluno";
	$aluno = DB::get_rows(DB::query($SQL));

	$SQL = "SELECT count(*) as num_presencas, DATE_FORMAT(au.data, '%m/%Y') as data FROM aluno as a
			join alunos_aula as aa on a.id_aluno = aa.id_aluno_fk
			join aula as au on aa.id_aula_fk = au.id_aula
			WHERE a.id_aluno = $id_aluno
			GROUP BY DATE_FORMAT(au.data, '%m/%Y')";

	$pesquisa = $SQL;

    foreach ($dataset as $key => $value) {
        if ($value != 'null') {
            if($key == 'data'){ 
                $data_ini = $dataset['data']['data_ini'];
                $data_fim = $dataset['data']['data_fim'];
                if (!$data_ini) continue;
                
                $exp_data = explode('/', $data_ini);   
                $data_ini = $exp_data[2]."-".$exp_data[1]."-".$exp_data[0];
                
                $exp_data = explode('/', $data_fim);   
                $data_fim = $exp_data[2]."-".$exp_data[1]."-".$exp_data[0];

                $pesquisa .= " AND data between '$data_ini' AND '$data_fim'";    
            } else {
                $pesquisa .= " AND $key = $value";    
            }
            
        }
    }

	$presencas = DB::get_rows(DB::query($SQL));

	$result = ["aluno" => $aluno, "presencas" => $presencas];
	echo json_encode($result);
}
?>