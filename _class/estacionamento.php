<?php

include 'sql.php';

$SELECT = 'SELECT id_estacionamento, modelo, cor, placa, id_aluno_fk, plano_ini, plano_fim, valor, estacionamento_status, observacao FROM estacionamento WHERE 1 = 1';

$INSERT = 'INSERT INTO estacionamento (id_aluno_fk, modelo, cor, placa, plano_ini, plano_fim, valor, estacionamento_status, observacao) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)';

$UPDATE = 'UPDATE estacionamento SET id_aluno_fk = %d, modelo = %s, cor = %s, placa = %s, plano_ini = %s, plano_fim = %s, valor = %s, estacionamento_status = %s, observacao = %s WHERE id_estacionamento = %d';

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
    $plano_ini = $_POST['dataset']['plano_ini'];
    $plano_fim = $_POST['dataset']['plano_fim'];
    $valor = $_POST['dataset']['valor'];
    $estacionamento_status = $_POST['dataset']['estacionamento_status'];
    $observacao = $_POST['dataset']['observacao'];
    $state = $_POST['dataset']['_STATE'];

    $exp_data = explode('/', $plano_ini);
    $plano_ini = $exp_data[2]."-".$exp_data[1]."-".$exp_data[0];

    $exp_data = explode('/', $plano_fim);
    $plano_fim = $exp_data[2]."-".$exp_data[1]."-".$exp_data[0];

    if($state == 'I'){
        
        DB::query($INSERT, $aluno, $modelo, $cor, $placa, $plano_ini, $plano_fim, $valor, $estacionamento_status, $observacao);

        $response['type'] = 'success';
        $response['message'] = 'Cadastro efetuado com sucesso';
        echo json_encode($response);

    } else if($state == 'U'){

        DB::query($UPDATE, $aluno, $modelo, $cor, $placa, $plano_ini, $plano_fim, $valor, $estacionamento_status, $observacao, $id);

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
    
    if ($id_aluno_fk != ''){

        $SQL = 'SELECT E.id_estacionamento, E.modelo, E.cor, E.placa, E.id_aluno_fk, E.plano_ini, E.plano_fim, E.valor, E.estacionamento_status, E.observacao, A.nome FROM estacionamento AS E INNER JOIN aluno AS A ON (E.id_aluno_fk = A.id_aluno) WHERE id_aluno_fk = %s';
        $rows = DB::get_rows(DB::query($SQL,$id_aluno_fk));
    
    } else {

        $SQL = 'SELECT E.id_estacionamento, E.modelo, E.cor, E.placa, E.id_aluno_fk, E.plano_ini, E.plano_fim, E.valor, E.estacionamento_status, E.observacao, A.nome FROM estacionamento AS E INNER JOIN aluno AS A ON (E.id_aluno_fk = A.id_aluno) ORDER BY A.nome ASC';
        $rows = DB::get_rows(DB::query($SQL));

    }
    
    echo json_encode($rows);
}

?>