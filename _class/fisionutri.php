<?php

include 'sql.php';

$SELECT = 'SELECT id_fisionutri, nome, DATE_FORMAT(data, "%d/%m/%Y") as data, telefone, tipo, servico, valor FROM fisionutri ORDER BY id_fisionutri ASC';

$INSERT = 'INSERT INTO fisionutri (nome, data, telefone, tipo, servico, valor) VALUES (%s,%d,%s,%d,%s,%d)';

$UPDATE = 'UPDATE fisionutri SET nome = %s, data = %s, telefone = %s, tipo = %s, servico = %s, valor = %d WHERE id_fisionutri = %d';

$methodToCall = $_POST['methodToCall'];

if ($methodToCall == 'loadData'){
    $rows = DB::get_rows(DB::query($SELECT));
    echo json_encode($rows);
}

if ($methodToCall == 'save'){

    $id = $_POST['dataset']['id_fisionutri'];  
    $nome = $_POST['dataset']['nome'];
	$data = $_POST['dataset']['data'];
	$telefone = $_POST['dataset']['telefone'];
	$tipo = $_POST['dataset']['tipo'];
	$servico = $_POST['dataset']['servico'];
	$valor = strval($_POST['dataset']['valor']);
    $state = $_POST['dataset']['_STATE'];

    if($state == 'I')  {
        
        DB::query($INSERT, $nome, $data, $telefone, $tipo, $servico, $valor);

        $response['type'] = 'success';
        $response['message'] = 'Cadastro efetuado com sucesso';
        echo json_encode($response);

    } else if($state == 'U') {
        
        DB::query($UPDATE, $nome, $data, $telefone, $tipo, $servico, $valor, $id);

        $response['type'] = 'success';
        $response['message'] = 'Cadastro editado com sucesso';
        echo json_encode($response);

    } else {
        
        $response['type'] = 'error';
        $response['message'] = 'Ocorreu algum erro.';
        echo json_encode($response);

    }
}

if($methodToCall == 'delete'){
    $id_fisionutri = $_POST['dataset']['id_fisionutri'];
    DB::query('DELETE from fisionutri where id_fisionutri = %d',$id_fisionutri);

    $response['type'] = 'success';
    $response['message'] = 'Excluido com sucesso!';
    echo json_encode($response);
}

if($methodToCall == 'pesquisa'){
    $dataset = $_POST['dataset'];

    $pesquisa = $SELECT;

    foreach ($dataset as $key => $value) {
        if ($value != 'null') {
            $pesquisa .= " AND $key like '$value%'";    
        }
    }

    $rows = DB::get_rows(DB::query($pesquisa));
    echo json_encode($rows);
}

?>