<?php

include 'sql.php';

$methodToCall = $_POST['methodToCall'];

if ($methodToCall == 'loadData'){
    $rows = DB::get_rows(DB::query('SELECT * FROM estacionameto ORDER BY id_estacionamento ASC'));
    echo json_encode($rows);
}

if ($methodToCall == 'save'){

    $id = $_POST['dataset']['id_estacionamento'];
    $aluno = $_POST['dataset']['aluno'];
    $modelo = $_POST['dataset']['modelo'];
    $cor = $_POST['dataset']['cor'];
    $placa = $_POST['dataset']['placa'];
    $plano = $_POST['dataset']['plano'];
    $state = $_POST['dataset']['_STATE'];

    if($state == 'I'){
        
        DB::query('INSERT INTO estacionameto (id_aluno_fk, modelo, cor, placa, id_plano_fk) VALUES (%s, %s, %s, %s, %d)', $aluno, $modelo, $cor, $placa, $plano);

        $response['type'] = 'success';
        $response['message'] = 'Cadastro efetuado com sucesso';
        echo json_encode($response);

    } else if($state == 'U'){

        DB::query('UPDATE estacionameto SET id_aluno_fk = %s, modelo = %s, cor = %s, placa = %s, id_plano_fk = %d WHERE id_estacionamento = %d', $aluno, $modelo, $cor, $placa, $plano, $id);

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
    $id_estacionamento = $_POST['dataset']['id_estacionamento'];
    DB::query('DELETE from estacionamento where id_estacionamento = %d',$id_estacionamento);

    $response['type'] = 'success';
    $response['message'] = 'Excluido com sucesso!';
    echo json_encode($response);
}

if($methodToCall == 'loadSelects'){
    $plano = DB::get_rows(DB::query('SELECT id_plano, nome from plano'));
    $selects = array('plano' => $plano);

    echo json_encode($selects);
}

?>