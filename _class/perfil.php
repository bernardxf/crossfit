<?php

include 'sql.php';

$methodToCall = $_POST['methodToCall'];

if ($methodToCall == "alterarSenha"){

    $id = $_POST['dataset']['id'];
    $senhaAtual = md5($_POST['dataset']['senhaatual']);
    $senhaNova = md5($_POST['dataset']['senhanova']);
    
    $verifica = DB::get_row(DB::query('SELECT senha FROM usuario WHERE id = %d', $id));

    if ($verifica['senha'] == $senhaAtual){

        DB::query('UPDATE usuario SET senha = %s WHERE id = %d',$senhaNova, $id);

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

    $id = $_POST['dataset']['id'];
    $senha = md5($_POST['dataset']['senha']);
    $nome = $_POST['dataset']['nome'];

    $verifica = DB::get_row(DB::query('SELECT senha FROM usuario WHERE id = %d', $id));

    if ($verifica['senha'] == $senha){

        DB::query('UPDATE usuario SET nome = %s WHERE id = %d',$nome, $id);
        
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