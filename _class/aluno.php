<?php

include 'sql.php';

$SELECT = 'SELECT id_aluno, nome, DATE_FORMAT(data_nasc, "%d/%m/%Y") as data_nasc, rg, cpf, estado_civil, email, cep, logradouro, complemento, bairro, 
           cidade, uf, tel_fixo, tel_celular, pessoa_ref, tel_ref, plano_saude, atestado_medico, horario_economico, id_plano_fk, id_forma_pagamento_fk, id_desconto_fk, valor_total, aluno_status, 
            DATE_FORMAT(data_cad, "%d/%m/%Y") as data_cad, DATE_FORMAT(data_alt, "%d/%m/%Y") as data_alt FROM aluno WHERE 1 = 1';

$INSERT = 'INSERT INTO aluno (nome, data_nasc, rg, cpf, estado_civil, email, cep, logradouro, complemento, bairro, cidade, uf, tel_fixo, tel_celular, pessoa_ref, tel_ref, plano_saude, atestado_medico, horario_economico, id_plano_fk, id_forma_pagamento_fk, id_desconto_fk, valor_total, aluno_status, data_cad, data_alt) 
                  VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d, %d, %d, %s, %s, %s)';

$UPDATE = 'UPDATE aluno SET nome = %s, data_nasc = %s, rg = %s, cpf = %s, estado_civil = %s, email = %s, cep = %s, logradouro = %s, complemento = %s,
        bairro = %s, cidade = %s, uf = %s, tel_fixo = %s, tel_celular = %s, pessoa_ref = %s, tel_ref = %s,
        plano_saude = %s, atestado_medico = %s, horario_economico = %s, id_plano_fk = %d, id_forma_pagamento_fk = %d, id_desconto_fk = %d, valor_total = %d, aluno_status = %s, data_alt = %s WHERE id_aluno = %d';

$methodToCall = $_POST['methodToCall'];

if ($methodToCall == 'loadData'){
    $query = $SELECT." ORDER BY nome ASC";
    $rows = DB::get_rows(DB::query($query));
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
    $cep = $_POST['dataset']['cep'];
    $logradouro = $_POST['dataset']['logradouro'];
    $complemento = $_POST['dataset']['complemento'];
    $bairro = $_POST['dataset']['bairro'];
    $cidade = $_POST['dataset']['cidade'];
    $uf = $_POST['dataset']['uf'];
    $tel_fixo = $_POST['dataset']['tel_fixo'];
    $tel_celular = $_POST['dataset']['tel_celular'];
    $pessoa_ref = $_POST['dataset']['pessoa_ref'];
    $tel_ref = $_POST['dataset']['tel_ref'];
    $plano_saude = $_POST['dataset']['plano_saude'];
    $atestado_medico = $_POST['dataset']['atestado_medico'];
    $horario_economico = $_POST['dataset']['horario_economico'];
    $plano = $_POST['dataset']['id_plano_fk'];
    $forma_pagamento = $_POST['dataset']['id_forma_pagamento_fk'];
    $desconto = $_POST['dataset']['id_desconto_fk'];
    $valor_total = $_POST['dataset']['valor_total'];
    $aluno_status = $_POST['dataset']['aluno_status'];
    $dataAtual = DATE('Y-m-d');
    $state = $_POST['dataset']['_STATE'];

    $exp_data = explode('/', $data_nasc);
    $data_nasc = $exp_data[2]."-".$exp_data[1]."-".$exp_data[0];

    if($state == 'I')  {
        
        DB::query($INSERT, $nome, $data_nasc, $rg, $cpf, $estado_civil, $email, $cep, $logradouro, $complemento, $bairro, $cidade, $uf, $tel_fixo, $tel_celular, $pessoa_ref, $tel_ref, $plano_saude, $atestado_medico, $horario_economico, $plano, $forma_pagamento, $desconto, $valor_total, $aluno_status, $dataAtual, $dataAtual);

        $response['type'] = 'success';
        $response['message'] = 'Cadastro efetuado com sucesso';
        echo json_encode($response);

    } else if($state == 'U') {

        DB::query($UPDATE, $nome, $data_nasc, $rg, $cpf, $estado_civil, $email, $cep, $logradouro, $complemento, $bairro, $cidade, $uf, $tel_fixo, $tel_celular, $pessoa_ref, $tel_ref, $plano_saude, $atestado_medico, $horario_economico, $plano, $forma_pagamento, $desconto, $valor_total, $aluno_status, $dataAtual, $id);
                            
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
    $id_aluno = $_POST['dataset']['id_aluno'];
    DB::query('DELETE from aluno where id_aluno = %d',$id_aluno);

    $response['type'] = 'success';
    $response['message'] = 'Excluido com sucesso!';
    echo json_encode($response);
}

if($methodToCall == 'loadSelects'){
    $plano = DB::get_rows(DB::query('SELECT id_plano, nome from plano'));
    $desconto = DB::get_rows(DB::query('SELECT id_desconto, nome from desconto'));
    $forma_pagamento = DB::get_rows(DB::query('SELECT id_forma_pagamento, nome from forma_pagamento'));
    $selects = array('plano' => $plano, 'desconto' => $desconto, 'formaPagamento' => $forma_pagamento);

    echo json_encode($selects);
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