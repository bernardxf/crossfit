<?php

include 'sql.php';

$methodToCall = $_POST['methodToCall'];

if ($methodToCall == 'loadData'){
    $rows = DB::get_rows(DB::query('SELECT * FROM plano ORDER BY idPlano ASC'));

    echo json_encode($rows);
}

if ($methodToCall == 'save'){
    
    $id = $_POST['dataset']['idPlano'];  
    $nome = $_POST['dataset']['nome'];
    $tipo = $_POST['dataset']['tipo'];
    $valor = floatval($_POST['dataset']['valor']);
    $state = $_POST['dataset']['_STATE'];

    if($state == 'I')  {

         DB::query('INSERT INTO plano (nome, tipo, valor) VALUES (%s,%s,%d)', $nome, $tipo, $valor);

        $response['type'] = 'success';
        $response['message'] = 'Cadastro efetuado com sucesso';
        echo json_encode($response);

    } else if($state == 'U') {
       
        DB::query('UPDATE plano SET nome = %s, tipo = %s, valor = %d WHERE idPlano = %d', $nome, $tipo, $valor, $id);

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
    $idPlano = $_POST['dataset']['idPlano'];
    DB::query('DELETE from plano where idPlano = %d',$idPlano);

    $response['type'] = 'success';
    $response['message'] = 'Excluido com sucesso!';
    echo json_encode($response);
}
?>