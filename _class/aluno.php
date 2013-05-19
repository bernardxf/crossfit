<?php

include 'sql.php';

$methodToCall = $_POST['methodToCall'];

if ($methodToCall == 'loadData'){
    $rows = DB::get_rows(DB::query('SELECT * FROM aluno ORDER BY idAluno ASC'));
    echo json_encode($rows);
}

if ($methodToCall == 'save'){
    
    $id = $_POST['dataset']['idAluno'];
    $nome = $_POST['dataset']['nome'];
    $dataNasc = $_POST['dataset']['dataNasc'];
    $rg = $_POST['dataset']['rg'];
    $cpf = $_POST['dataset']['cpf'];
    $estadoCivil = $_POST['dataset']['estadoCivil'];
    $email = $_POST['dataset']['email'];
    $logradouro = $_POST['dataset']['logradouro'];
    $complemento = $_POST['dataset']['complemento'];
    $bairro = $_POST['dataset']['bairro'];
    $cidade = $_POST['dataset']['cidade'];
    $uf = $_POST['dataset']['uf'];
    $cep = $_POST['dataset']['cep'];
    $telfixo = $_POST['dataset']['telfixo'];
    $telcel = $_POST['dataset']['telcel'];
    $pessoaref = $_POST['dataset']['pessoaref'];
    $telref = $_POST['dataset']['telref'];
    $planosaude = $_POST['dataset']['planosaude'];
    $atestadoMedico = $_POST['dataset']['atestadoMedico'];
    $plano = $_POST['dataset']['plano'];
    $formaPagamento = $_POST['dataset']['formaPagamento'];
    $desconto = $_POST['dataset']['desconto'];
    $valorTotal = $_POST['dataset']['valorTotal'];
    $status = $_POST['dataset']['status'];
    $dataAtual = DATE('Y/m/d');
    $state = $_POST['dataset']['_STATE'];

    if($state == 'I')  {
        
        DB::query('INSERT INTO aluno (nome, dataNasc, rg, cpf, estadoCivil, email, logradouro, complemento, bairro, cidade, uf, cep, telefoneFixo, telefoneCelular, pessoaReferencia, telefoneReferencia, planoSaude, atestadoMedico, plano, formaPagamento, desconto, valorTotal, status, dataCad, dataAlt) 
                    VALUES (%s, %d, %d, %d, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d, %s, %d, %d, %d, %d, %s, %d, %d, %d, %s, %s)', 
                    $nome, $dataNasc, $rg, $cpf, $estadoCivil, $email, $logradouro, $complemento, $bairro, $cidade, $uf, $cep, $telfixo, $telcel, $pessoaref, $telref, $planoSaude, $atestadoMedico, $plano, $formaPagamento, $desconto, $valorTotal, $status, $dataAtual, $dataAtual;

        $response['type'] = 'success';
        $response['message'] = 'Cadastro editado com sucesso';
        echo json_encode($response);

    } else if($state == 'U') {
        
        DB::query('UPDATE aluno SET nome = %s, dataNasc = %d, rg = %d, cpf = %d, estadoCivil = %s, email = %s, logradouro = %s, complemento = %s,
        bairro = %s, cidade = %s, uf = %s, cep = %s, telefoneFixo = %d, telefoneCelular = %d, pessoaReferencia = %s, telefoneReferencia = %d,
        planoSaude = %d, atestadoMedico = %d, plano = %d, formaPagamento = %s, desconto = %d, valorTotal = %d, status = %d, dataAlt = %s WHERE idAluno = %d', 
            $nome, $dataNasc, $rg, $cpf, $estadoCivil, $email, $logradouro, $complemento, $bairro, $cidade, $uf, $cep, $telfixo, $telcel, $pessoaref, 
            $telref, $planoSaude, $atestadoMedico, $plano, $formaPagamento, $desconto, $valorTotal, $status, $dataAtual, $id);

        $response['type'] = 'success';
        $response['message'] = 'Cadastro efetuado com sucesso';
        echo json_encode($response);
        
    } else {
        
        $response['type'] = 'error';
        $response['message'] = 'Ocorreu algum erro.';
        echo json_encode($response);
        
    }
}

?>