<?php

include 'sql.php';

$methodToCall = $_POST['methodToCall'];

if($methodToCall == 'pesquisa'){
    // $pesquisa = 'SELECT count(*) as num_presentes, DATE_FORMAT(data, "%d/%m/%y") as data from presenca group by data';
    // $rows = DB::get_rows(DB::query($pesquisa));

    // echo json_encode($rows);

    $dataini = $dataset['dataini'];
	$exp_data = explode('/', $dataini);
	$dataini = $exp_data[2]."-".$exp_data[1]."-".$exp_data[0];

	$datafim = $dataset['datafim'];
	$exp_data = explode('/', $datafim);
	$datafim = $exp_data[2]."-".$exp_data[1]."-".$exp_data[0];

	$horario = $dataset['horario'];


    $pesquisa = "SELECT count(*) as num_presentes, DATE_FORMAT(data, '%d/%m/%y') as data FROM presenca 
    			WHERE horario = '".$horario."' AND data BETWEEN '".$dataini."' AND '".$datafim."' GROUP BY data";
    $rows = DB::get_rows(DB::query($pesquisa));

    echo json_encode($rows);
}

?>