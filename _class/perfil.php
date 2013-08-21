<?php

include 'sql.php';

$methodToCall = $_POST['methodToCall'];
$dataset = $_POST['dataset'];

if ($methodToCall == "alterarSenha"){

    $id = $dataset['id_usuario'];
    $senhaAtual = md5($dataset['senhaatual']);
    $senhaNova = md5($dataset['novasenha']);
    
    $verifica = DB::get_row(DB::query('SELECT senha FROM usuario WHERE id_usuario = %d', $id));

    if ($verifica['senha'] == $senhaAtual){

        DB::query('UPDATE usuario SET senha = %s WHERE id_usuario = %d',$senhaNova, $id);

        $response['type'] = 'success';
        $response['message'] = 'Senha alterada com sucesso';
        echo json_encode($response);

    } else {
        $response['type'] = 'error';
        $response['message'] = 'Senha atual está incorreta';
        echo json_encode($response);
        die();
    }

} 
if ($methodToCall == "alterarNome"){

    $id = $dataset['id_usuario'];
    $senha = md5($dataset['senha']);
    $nome = $dataset['nome'];

    $verifica = DB::get_row(DB::query('SELECT senha FROM usuario WHERE id_usuario = %d', $id));

    if ($verifica['senha'] == $senha){

        DB::query('UPDATE usuario SET nome = %s WHERE id_usuario = %d',$nome, $id);
        
        $response['type'] = 'success';
        $response['message'] = 'Senha alterada com sucesso';
        echo json_encode($response);

    } else {
        
        $response['type'] = 'error';
        $response['message'] = 'Senha está incorreta';
        echo json_encode($response);

    }

}

?>