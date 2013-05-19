<?php

include 'sql.php';

$methodToCall = $_POST['methodToCall'];

if ($methodToCall == 'loadData'){
    $rows = DB::get_rows(DB::query('SELECT * FROM desconto ORDER BY id_desconto ASC'));
    echo json_encode($rows);
}

if ($methodToCall == 'save'){

    $id = $_POST['dataset']['id_desconto'];
    $nome = $_POST['dataset']['nome'];
    $porcDesc = $_POST['dataset']['porcDesc'];
    $state = $_POST['dataset']['_STATE'];

    if($state == 'I'){

        DB::query('INSERT INTO desconto (nome, porcDesc) VALUES (%s,%d)', $nome, $porcDesc);

        $response['type'] = 'success';
        $response['message'] = 'Cadastro efetuado com sucesso';
        echo json_encode($response);

    } else if($state == 'U'){

        DB::query('UPDATE desconto SET nome = %s, porcDesc = %d WHERE id_desconto = %d', $nome, $porcDesc, $id);

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