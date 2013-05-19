<?php

include 'sql.php';

$methodToCall = $_POST['methodToCall'];

if ($methodToCall == 'loadData'){
    $rows = DB::get_rows(DB::query('SELECT * FROM aulaexp ORDER BY idAulaexp ASC'));
    echo json_encode($rows);
}

if ($methodToCall == 'save'){

    $id = $_POST['dataset']['id_aulaexp'];
    $nome = $_POST['dataset']['nome'];
    $data = $_POST['dataset']['data'];
    $telefone = $_POST['dataset']['telefone'];
    $confirmado = $_POST['dataset']['confirmado'];
    $presente = $_POST['dataset']['presente'];
    $state = $_POST['dataset']['_STATE'];

    if($state == 'I')  {

        DB::query('INSERT INTO aulaexp (nome, data, telefone, confirmado, presente) VALUES (%s, %d, %d, %d, %d)', $nome, $data, $telefone, $confirmado, $presente);

        $response['type'] = 'success';
        $response['message'] = 'Cadastro efetuado com sucesso';
        echo json_encode($response);

    } else if ($state == 'U') {

        DB::query('UPDATE aulaexp SET nome = %s, data = %d, telefone = %d, confirmado = %d, presente = %d WHERE id_aulaexp = %d', $nome, $data, $rg, $telefone, $confirmado, $presente, $id);

        $response['type'] = 'success';
        $response['message'] = 'Cadastro editado com sucesso';
        echo json_encode($response);

    } else {

        $response['type'] = 'error';
        $response['message'] = 'Não foi possível editar o cadastro';
        echo json_encode($response);

    }
}
?>