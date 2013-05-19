<?php

include 'sql.php';

class Desconto {

    public function __construct(){}

    /**
     * Inserer um desconto banco
     *
     * @param string $nome, $porcDesc
     * @return boolean o resultado da query
     */
    public function inserir($nome,$porcDesc){

        return DB::query('INSERT INTO desconto (nome,porcDesc) VALUES (%s,%s)', $nome, $porcDesc);
    }

    /**
     * Edita um desconto
     *
     * @param string $idDesconto, $nome, $porcDesc
     * @return boolean o resultado da query
     */
    public function editar($idDesconto,$nome,$porcDesc){

        return DB::query('UPDATE desconto SET nome = %s, $porcDesc = %s WHERE idDesconto = %d',$nome, $porcDesc, $idDesconto);
    }

    /**
     * Exclui desconto banco
     *
     * @param string $idDesconto
     * @return boolean o resultado da query
     */
    public function excluir($idDesconto) {
        return DB::query('DELETE FROM desconto WHERE idDesconto = %d', $idDesconto);
    }

    /**
     * Lista todas os descontos
     *
     * @return boolean o resultado da query
     */
    public function listar() {
        return DB::query('SELECT * FROM desconto');
    }
}
?>