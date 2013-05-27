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
    $porc_desc = $_POST['dataset']['porc_desc'];
    $state = $_POST['dataset']['_STATE'];

    if($state == 'I'){

        DB::query('INSERT INTO desconto (nome, porc_desc) VALUES (%s,%d)', $nome, $porc_desc);

        $response['type'] = 'success';
        $response['message'] = 'Cadastro efetuado com sucesso';
        echo json_encode($response);

    } else if($state == 'U'){

        DB::query('UPDATE desconto SET nome = %s, porc_desc = %d WHERE id_desconto = %d', $nome, $porc_desc, $id);

        $response['type'] = 'success';
        $response['message'] = 'Cadastro editado com sucesso';
        echo json_encode($response);

    } else {

        $response['type'] = 'error';
        $response['message'] = 'Não foi possível editar o cadastro';
        echo json_encode($response);

    }
}

if($methodToCall == 'delete'){
    $id_desconto = $_POST['dataset']['id_desconto'];
    DB::query('DELETE from desconto where id_desconto = %d',$id_desconto);

    $response['type'] = 'success';
    $response['message'] = 'Excluido com sucesso!';
    echo json_encode($response);
}

?>