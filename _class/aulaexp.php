<?php

include 'sql.php';

$SELECT = 'SELECT id_aulaexp, nome, DATE_FORMAT(data_aula, "%d/%m/%Y") as data_aula, telefone, email, confirmado, presente FROM aulaexp';

$INSERT = 'INSERT INTO aulaexp (nome, data_aula, telefone, email, confirmado, presente) VALUES (%s, %s, %s, %s, %s, %s)';

$UPDATE = 'UPDATE aulaexp SET nome = %s, data_aula = %s, telefone = %s, email = %s, confirmado = %s, presente = %s WHERE id_aulaexp = %d';

$methodToCall = $_POST['methodToCall'];
$dataset = $_POST['dataset'];

if ($methodToCall == 'loadData'){
    $rows = DB::get_rows(DB::query($SELECT));
    echo json_encode($rows);
}

if ($methodToCall == 'save'){

    $id = $_POST['dataset']['id_aulaexp'];
    $nome = $_POST['dataset']['nome'];
    $data_aula = $_POST['dataset']['data_aula'];
    $telefone = $_POST['dataset']['telefone'];
    $email = $_POST['dataset']['email'];
    $confirmado = $_POST['dataset']['confirmado'];
    $presente = $_POST['dataset']['presente'];
    $state = $_POST['dataset']['_STATE'];

    $exp_data = explode('/', $data_aula);
    $data_aula = $exp_data[2]."-".$exp_data[1]."-".$exp_data[0];

    if($state == 'I')  {

        DB::query($INSERT, $nome, $data_aula, $telefone, $email, $confirmado, $presente);

        $response['type'] = 'success';
        $response['message'] = 'Cadastro efetuado com sucesso';
        echo json_encode($response);

    } else if ($state == 'U') {

        DB::query($UPDATE, $nome, $data_aula, $telefone, $email, $confirmado, $presente, $id);

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
    $data_aula = $dataset['data_aula'];
    $dataset = $_POST['dataset'];
    $pesquisa = $SELECT;

    if ($data_aula != '') {

        foreach ($dataset as $key => $value) {
            if ($value != 'null') {
                if($key == 'data_aula'){ 
                    $data_aula = $dataset['data_aula'];
                    
                    $exp_data = explode('/',$data_aula);   
                    $data_aula = $exp_data[2]."-".$exp_data[1]."-".$exp_data[0];
                    
                    $pesquisa .= " WHERE data_aula = '" . $data_aula . "'";    
                } else {
                    $pesquisa .= " AND $key = $value";    
                }
            }
        }

        $rows = DB::get_rows(DB::query($pesquisa));

    } else {

        $rows = DB::get_rows(DB::query($SELECT));      

    }
    echo json_encode($rows);

}

?>