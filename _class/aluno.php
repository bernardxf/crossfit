<?php

include 'sql.php';

$methodToCall = $_POST['methodToCall'];

if ($methodToCall == 'loadData'){
    // $rows = DB::get_rows(DB::query('SELECT * FROM aluno'));
    $rows = DB::get_rows(DB::query('SELECT id_aluno, nome, DATE_FORMAT(data_nasc, "%d/%m/%Y") as data_nasc, rg, cpf, estado_civil, email, logradouro, complemento, bairro, 
            cidade, uf, tel_fixo, tel_celular, pessoa_ref, tel_ref, plano_saude, atestado_medico, id_plano_fk, forma_pagamento, id_desconto_fk, valor_total, status, 
            DATE_FORMAT(data_cad, "%d/%m/%Y") as data_cad, DATE_FORMAT(data_alt, "%d/%m/%Y") as data_alt FROM aluno'));

    echo json_encode($rows);
}

if ($methodToCall == 'save'){
    
    $id = $_POST['dataset']['id_aluno'];
    $nome = $_POST['dataset']['nome'];
    $data_nasc = $_POST['dataset']['data_nasc'];
    $rg = $_POST['dataset']['rg'];
    $cpf = $_POST['dataset']['cpf'];
    $estado_civil = $_POST['dataset']['estado_civil'];
    $email = $_POST['dataset']['email'];
    $logradouro = $_POST['dataset']['logradouro'];
    $complemento = $_POST['dataset']['complemento'];
    $bairro = $_POST['dataset']['bairro'];
    $cidade = $_POST['dataset']['cidade'];
    $uf = $_POST['dataset']['uf'];
    $cep = $_POST['dataset']['cep'];
    $telfixo = $_POST['dataset']['tel_fixo'];
    $telcel = $_POST['dataset']['tel_celular'];
    $pessoaref = $_POST['dataset']['pessoaref'];
    $telref = $_POST['dataset']['telref'];
    $plano_saude = $_POST['dataset']['plano_saude'];
    $atestado_medico = $_POST['dataset']['atestado_medico'];
    $plano = $_POST['dataset']['id_plano_fk'];
    $forma_pagamento = $_POST['dataset']['forma_pagamento'];
    $desconto = $_POST['dataset']['id_desconto_fk'];
    $valor_total = $_POST['dataset']['valor_total'];
    $status = $_POST['dataset']['status'];
    $dataAtual = DATE('Y-m-d');
    $state = $_POST['dataset']['_STATE'];

    $exp_data = explode('/', $data_nasc);
    $data_nasc = $exp_data[2]."-".$exp_data[1]."-".$exp_data[0];

    if($state == 'I')  {
        
        DB::query('INSERT INTO aluno (nome, data_nasc, rg, cpf, estado_civil, email, logradouro, complemento, bairro, 
            cidade, uf, tel_fixo, tel_celular, pessoa_ref, tel_ref, plano_saude, atestado_medico, id_plano_fk, forma_pagamento, id_desconto_fk, valor_total, status, data_cad, data_alt) 
                    VALUES (%s, %s, %d, %d, %s, %s, %s, %s, %s, %s, %s, %d, %s, %s, %d, %d, %d, %d, %s, %d, %d, %d, %s, %s)', 
                    $nome, $data_nasc, $rg, $cpf, $estado_civil, $email, $logradouro, $complemento, $bairro, $cidade, $uf, 
                    $telfixo, $telcel, $pessoaref, $telref, $plano_saude, $atestado_medico, $plano, $forma_pagamento, $desconto, $valor_total, $status, $dataAtual, $dataAtual);

        $response['type'] = 'success';
        $response['message'] = 'Cadastro editado com sucesso';
        echo json_encode($response);

    } else if($state == 'U') {

        DB::query('UPDATE aluno SET nome = %s, data_nasc = %s, rg = %d, cpf = %d, estado_civil = %s, email = %s, logradouro = %s, complemento = %s,
        bairro = %s, cidade = %s, uf = %s, tel_fixo = %d, tel_celular = %d, pessoa_ref = %s, tel_ref = %d,
        plano_saude = %d, atestado_medico = %d, id_plano_fk = %d, forma_pagamento = %s, id_desconto_fk = %d, valor_total = %d, status = %d, data_alt = %s WHERE id_aluno = %d', 
            $nome, $data_nasc, $rg, $cpf, $estado_civil, $email, $logradouro, $complemento, $bairro, $cidade, $uf, $telfixo, $telcel, $pessoaref, 
            $telref, $plano_saude, $atestado_medico, $plano, $forma_pagamento, $desconto, $valor_total, $status, $dataAtual, $id);

        $response['type'] = 'success';
        $response['message'] = 'Cadastro efetuado com sucesso';
        echo json_encode($response);
        
    } else {
        
        $response['type'] = 'error';
        $response['message'] = 'Ocorreu algum erro.';
        echo json_encode($response);
        
    }
}

if($methodToCall == 'delete'){
    $id_aluno = $_POST['dataset']['id_aluno'];
    DB::query('DELETE from aluno where id_aluno = %s',$id_aluno);

    $response['type'] = 'success';
    $response['message'] = 'Excluido com sucesso!';
    echo json_encode($response);
}

if($methodToCall == 'loadSelects'){
    $plano = DB::get_rows(DB::query('SELECT id_plano, nome from plano'));
    $desconto = DB::get_rows(DB::query('SELECT id_desconto, nome from desconto'));
    $selects = array('plano' => $plano, 'desconto' => $desconto);

    echo json_encode($selects);
}

?>