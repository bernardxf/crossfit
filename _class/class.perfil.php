<?php

include 'sql.php';

class Perfil {

    public function __construct(){}

    public function alterarSenha(){

        $params = $_POST['params'];
        $id = $params['id'];
        $senhaAtual = $params['senhaatual'];
        $senhaNova = $params['senhanova'];

        $verifica = DB::query('SELECT senha FROM usuario WHERE id = %d', $id);

        if ($verifica == $senhaAtual){
            DB::query('UPDATE usuario SET senha = %s WHERE id = %d',$senhaNova, $id);
            echo 'true';
        } else {
            echo 'Senha atual está incorreta!';
            die();
        }
    }

    public function alterarNome($id, $senhaAtual,$nome){

        $verifica = DB::query('SELECT senha FROM usuario WHERE id = %d', $id);

        if ($verifica == $senhaAtual){
            DB::query('UPDATE usuario SET nome = %s WHERE id = %d',$nome, $id);
            echo 'true';
        } else {
            echo 'Senha está incorreta!';
        }
    }
}

$methodToCall = $_POST['methodToCall'];

$instance = new Perfil();
$instance->{$methodToCall};


?>