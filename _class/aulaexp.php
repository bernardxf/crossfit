<?php

include 'sql.php';

$SELECT = 'SELECT id_aulaexp, nome, DATE_FORMAT(data, "%d/%m/%Y") as data, telefone, confirmado, presente FROM aulaexp WHERE 1 = 1';

$INSERT = 'INSERT INTO aulaexp (nome, data, telefone, confirmado, presente) VALUES (%s, %s, %s, %d, %d)';

$UPDATE = 'UPDATE aulaexp SET nome = %s, data = %s, telefone = %s, confirmado = %d, presente = %d WHERE id_aulaexp = %d';

$methodToCall = $_POST['methodToCall'];
$dataset = $_POST['dataset'];

if ($methodToCall == 'loadData'){
    $rows = DB::get_rows(DB::query($SELECT));
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

    $exp_data = explode('/', $data);
    $data = $exp_data[2]."-".$exp_data[1]."-".$exp_data[0];

    if($state == 'I')  {

        DB::query($INSERT, $nome, $data, $telefone, $confirmado, $presente);

        $response['type'] = 'success';
        $response['message'] = 'Cadastro efetuado com sucesso';
        echo json_encode($response);

    } else if ($state == 'U') {

        DB::query($UPDATE, $nome, $data, $telefone, $confirmado, $presente, $id);

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
    $id_aulaexp = $_POST['dataset']['id_aulaexp'];
    DB::query('DELETE from aulaexp where id_aulaexp = %d',$id_aulaexp);

    $response['type'] = 'success';
    $response['message'] = 'Excluido com sucesso!';
    echo json_encode($response);
}

if($methodToCall == 'pesquisa'){
    $data = $dataset['data'];
    $exp_data = explode('/', $data);
    $data = $exp_data[2]."-".$exp_data[1]."-".$exp_data[0];

    $SQL = "SELECT id_aulaexp, nome, data, telefone, confirmado, presente from aulaexp 
            where data = '%s'";
    $rows = DB::get_rows(DB::query($SQL, $data));
    echo json_encode($rows);
}

?>