<?php

include 'sql.php';

$SELECT = 'SELECT id_estacionamento, id_plano_fk, modelo, cor, placa, id_aluno_fk FROM estacionamento WHERE 1 = 1';

$INSERT = 'INSERT INTO estacionamento (id_aluno_fk, modelo, cor, placa, id_plano_fk) VALUES (%s, %s, %s, %s, %d)';

$UPDATE = 'UPDATE estacionamento SET id_aluno_fk = %d, modelo = %s, cor = %s, placa = %s, id_plano_fk = %d WHERE id_estacionamento = %d';

$methodToCall = $_POST['methodToCall'];
$dataset = $_POST['dataset'];

if ($methodToCall == 'loadData'){
    $query = $SELECT." ORDER BY id_estacionamento ASC";
    $rows = DB::get_rows(DB::query($query));
    echo json_encode($rows);
}   

if ($methodToCall == 'save'){

    $id = $_POST['dataset']['id_estacionamento'];
    $aluno = $_POST['dataset']['id_aluno_fk'];
    $modelo = $_POST['dataset']['modelo'];
    $cor = $_POST['dataset']['cor'];
    $placa = $_POST['dataset']['placa'];
    $plano = $_POST['dataset']['id_plano_fk'];
    $state = $_POST['dataset']['_STATE'];

    if($state == 'I'){
        
        DB::query($INSERT, $aluno, $modelo, $cor, $placa, $plano);

        $response['type'] = 'success';
        $response['message'] = 'Cadastro efetuado com sucesso';
        echo json_encode($response);

    } else if($state == 'U'){

        DB::query($UPDATE, $aluno, $modelo, $cor, $placa, $plano, $id);

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
    $aluno = DB::get_rows(DB::query('SELECT id_aluno, nome from aluno'));
    $selects = array('plano' => $plano, 'aluno' => $aluno);

    echo json_encode($selects);
}

if($methodToCall == 'pesquisa'){
    $id_aluno_fk = $dataset['id_aluno'];
    
    $SQL = 'SELECT * FROM estacionamento where id_aluno_fk = %s';

    $rows = DB::get_rows(DB::query($SQL,$id_aluno_fk));
    echo json_encode($rows);
}

?>