<?php

include 'sql.php';

$methodToCall = $_POST['methodToCall'];

if($methodToCall == 'pesquisa'){
    $pesquisa = 'SELECT count(*) as num_presentes, DATE_FORMAT(data, "%d/%m/%y") as data from presenca group by data';
    $rows = DB::get_rows(DB::query($pesquisa));

    echo json_encode($rows);
}

?>