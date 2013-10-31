<?php

include 'sql.php';

$methodToCall = $_POST['methodToCall'];
$dataset = $_POST['dataset'];

if($methodToCall == 'pesquisa'){

    $dataini = $dataset['dataini'];
	$exp_data = explode('/', $dataini);
	$dataini = $exp_data[2]."-".$exp_data[1]."-".$exp_data[0];

	$datafim = $dataset['datafim'];
	$exp_data = explode('/', $datafim);
	$datafim = $exp_data[2]."-".$exp_data[1]."-".$exp_data[0];

	$horario = $dataset['horario'];
    
    $pesquisa = "SELECT count(*) num_presentes, DATE_FORMAT(a.data, '%d/%m/%Y') as data, a.excedente from alunos_aula as aa
                join aula a on aa.id_aula_fk = a.id_aula 
                WHERE horario = '".$horario."' AND data BETWEEN '".$dataini."' AND '".$datafim."'
                GROUP BY data";

    $rows = DB::get_rows(DB::query($pesquisa));

    echo json_encode($rows);
}

?>